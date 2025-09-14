UPDATE `settings` SET `value` = '{\"version\":\"52.0.0\", \"code\":\"5200\"}' WHERE `key` = 'product_info';

-- SEPARATOR --

alter table links add directory_is_enabled tinyint default 1 null after is_verified;
