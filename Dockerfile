# ============================================================
# Stage 1 — build MaxMind libraries & PHP extension
# ============================================================
FROM php:8.4-apache AS builder

# Build dependencies only
RUN apt-get update && apt-get install -y \
    git \
    build-essential \
    cmake \
    autoconf \
    automake \
    libtool \
    pkg-config \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /tmp

# Build libmaxminddb
RUN git clone --depth=1 https://github.com/maxmind/libmaxminddb.git \
 && cd libmaxminddb \
 && ./bootstrap \
 && ./configure --disable-tests \
 && make \
 && make install

# Build PHP maxmind extension (no install here)
RUN git clone --depth=1 https://github.com/maxmind/MaxMind-DB-Reader-php.git \
 && cd MaxMind-DB-Reader-php/ext \
 && phpize \
 && ./configure \
 && make

# ============================================================
# Stage 2 — runtime (clean, fast, production)
# ============================================================
FROM php:8.4-apache

# Apache configuration
RUN a2enmod rewrite headers expires \
 && sed -i 's/ServerTokens OS/ServerTokens Prod/' /etc/apache2/conf-available/security.conf \
 && sed -i 's/ServerSignature On/ServerSignature Off/' /etc/apache2/conf-available/security.conf

# System runtime dependencies + PHP extensions
RUN apt-get update && apt-get install -y \
    curl \
    wget \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libicu-dev \
    libzip-dev \
 && docker-php-ext-configure gd --with-freetype --with-jpeg \
 && docker-php-ext-install \
    mysqli \
    gd \
    intl \
    zip \
    bcmath \
    opcache \
 && rm -rf /var/lib/apt/lists/*

# Copy compiled artifacts
COPY --from=builder /usr/local/lib/libmaxminddb* /usr/local/lib/
COPY --from=builder /usr/local/include/maxminddb.h /usr/local/include/
COPY --from=builder /tmp/MaxMind-DB-Reader-php/ext/modules/maxminddb.so /tmp/

RUN ldconfig \
 && PHP_EXT_DIR="$(php -r 'echo ini_get("extension_dir");')" \
 && cp /tmp/maxminddb.so "$PHP_EXT_DIR" \
 && echo 'extension=maxminddb.so' > /usr/local/etc/php/conf.d/maxminddb.ini

# PHP configuration
RUN { \
    echo 'upload_max_filesize = 10M'; \
    echo 'post_max_size = 10M'; \
    echo 'max_input_vars = 20000'; \
} > /usr/local/etc/php/conf.d/custom.ini

# OPcache configuration
RUN { \
    echo 'opcache.enable=1'; \
    echo 'opcache.enable_cli=0'; \
    echo 'opcache.memory_consumption=128'; \
    echo 'opcache.interned_strings_buffer=16'; \
    echo 'opcache.max_accelerated_files=20000'; \
    echo 'opcache.validate_timestamps=1'; \
    echo 'opcache.revalidate_freq=3'; \
} > /usr/local/etc/php/conf.d/opcache.ini

RUN printf '<Directory /var/www/html>\nAllowOverride All\n</Directory>\n' \
 >> /etc/apache2/apache2.conf

WORKDIR /var/www/html
