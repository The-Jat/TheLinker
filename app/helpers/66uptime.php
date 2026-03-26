<?php
/*
 * Copyright (c) 2025 AltumCode (https://altumcode.com/)
 *
 * This software is licensed exclusively by AltumCode and is sold only via https://altumcode.com/.
 * Unauthorized distribution, modification, or use of this software without a valid license is not permitted and may be subject to applicable legal actions.
 *
 * 🌍 View all other existing AltumCode projects via https://altumcode.com/
 * 📧 Get in touch for support or general queries via https://altumcode.com/contact
 * 📤 Download the latest version via https://altumcode.com/downloads
 *
 * 🐦 X/Twitter: https://x.com/AltumCode
 * 📘 Facebook: https://facebook.com/altumcode
 * 📸 Instagram: https://instagram.com/altumcode
 */

defined('ALTUMCODE') || die();

function ping($monitor) {

    /* Error details */
    $error = null;

    /* Local request, native server */
    switch($monitor->type) {

            /* Fsockopen */
            case 'port':

                $ping = new \JJG\Ping($monitor->target);
                $ping->setTimeout($monitor->settings->timeout_seconds);
                $ping->setPort($monitor->port);
                $latency = $ping->ping('fsockopen');

                if($latency !== false) {
                    $response_status_code = 0;
                    $response_time = $latency;

                    /*  :)  */
                    $is_ok = 1;
                } else {
                    $response_status_code = 0;
                    $response_time = 0;

                    /*  :)  */
                    $is_ok = 0;
                }

                break;

            /* Ping check */
            case 'ping':

                $ping = new \JJG\Ping($monitor->target);
                $ping->setTimeout($monitor->settings->timeout_seconds);
                $latency = $ping->ping('fsockopen');

                if($latency !== false) {
                    $response_status_code = 0;
                    $response_time = $latency;

                    /*  :)  */
                    $is_ok = 1;
                } else {
                    $response_status_code = 0;
                    $response_time = 0;

                    /*  :)  */
                    $is_ok = 0;
                }

                break;

            /* Websites check */
            case 'website':

                /* Set timeout */
                \Unirest\Request::timeout($monitor->settings->timeout_seconds);

                try {

                    /* Set auth */
                    \Unirest\Request::auth($monitor->settings->request_basic_auth_username ?? '', $monitor->settings->request_basic_auth_password ?? '');

                    /* Make the request to the website */
                    $method = mb_strtolower($monitor->settings->request_method ?? 'get');

                    if(in_array($method, ['post', 'put', 'patch'])) {
                        $response = \Unirest\Request::{$method}($monitor->target, $monitor->settings->request_headers ?? [], $monitor->settings->request_body ?? []);
                    } else {
                        $response = \Unirest\Request::{$method}($monitor->target, $monitor->settings->request_headers ?? []);
                    }

                    /* Get info after the request */
                    $info = \Unirest\Request::getInfo();

                    /* Some needed variables */
                    $response_status_code = $info['http_code'];
                    $response_time = $info['total_time'] * 1000;

                    /* Check the response to see how we interpret the results */
                    $is_ok = 1;

                    if($response_status_code != ($monitor->settings->response_status_code ?? 200)) {
                        $is_ok = 0;
                        $error = ['type' => 'response_status_code'];
                    }

                    if(isset($monitor->settings->response_body) && $monitor->settings->response_body && mb_strpos($response->raw_body, $monitor->settings->response_body) === false) {
                        $is_ok = 0;
                        $error = ['type' => 'response_body'];
                    }

                    if(isset($monitor->settings->response_headers)) {
                        foreach($monitor->settings->response_headers as $response_header) {
                            $response_header->name = mb_strtolower($response_header->name);

                            if(!isset($response->headers[$response_header->name]) || (isset($response->headers[$response_header->name]) && $response->headers[$response_header->name] != $response_header->value)) {
                                $is_ok = 0;
                                $error = ['type' => 'response_header'];
                                break;
                            }
                        }
                    }

                } catch (\Exception $exception) {
                    $response_status_code = 0;
                    $response_time = 0;
                    $error = [
                        'type' => 'exception',
                        'code' => curl_errno(\Unirest\Request::getCurlHandle()),
                        'message' => curl_error(\Unirest\Request::getCurlHandle()),
                    ];

                    /*  :)  */
                    $is_ok = 0;
                }

                break;
        }

    return [
        'is_ok' => $is_ok,
        'response_time' => $response_time,
        'response_status_code' => $response_status_code,
        'error' => $error
    ];

}


function get_website_certificate($url, $port = 443, $enabled = false) {
    try {
        $domain = parse_url($url, PHP_URL_HOST);

        $get = stream_context_create([
            'ssl' => [
                'capture_peer_cert' => TRUE,
                'verify_peer' => $enabled,
                'verify_peer_name' => $enabled,
                'allow_self_signed' => true
            ]
        ]);

        $read = @stream_socket_client('ssl://' . $domain . ':' . $port, $errno, $errstr, 10, STREAM_CLIENT_CONNECT, $get);

        if(!$read || $errstr) return null;

        $certificate_params = stream_context_get_params($read);

        $certificate = openssl_x509_parse($certificate_params['options']['ssl']['peer_certificate']);

        if(empty($certificate)) return null;

        $start_datetime = $certificate['validFrom_time_t'] ? (new \DateTime())->setTimestamp($certificate['validFrom_time_t']) : null;
        $end_datetime = $certificate['validTo_time_t'] ? (new \DateTime())->setTimestamp($certificate['validTo_time_t']) : null;
        $current_datetime = (new \DateTime());
        $is_valid = $start_datetime && $end_datetime && $current_datetime > $start_datetime && $current_datetime < $end_datetime;

        return empty($certificate) ? null : [
            'organization' => $certificate['issuer']['O'] ?? null,
            'common_name' => $certificate['issuer']['CN'] ?? null,
            'issuer_country' => $certificate['issuer']['C'] ?? null,
            'start_datetime' => $start_datetime ? $start_datetime->format('Y-m-d H:i:s') : null,
            'end_datetime' => $end_datetime ? $end_datetime->format('Y-m-d H:i:s') : null,
            'signature_type' => $certificate['signatureTypeSN'] ?? null,
            'is_valid' => $is_valid,
        ];

    } catch (\Exception $exception) {
        return null;
    }
}

function get_rdap_dns_map() {
	$cache_file = UPLOADS_PATH . 'cache/rdap_dns.json';

	/* Cache for 30 days */
	if (!file_exists($cache_file) || filemtime($cache_file) < time() - 2592000) {
		$json = file_get_contents('https://data.iana.org/rdap/dns.json');

		if($json) {
			file_put_contents($cache_file, $json);
		}
	}

	$data = json_decode(file_get_contents($cache_file), true);
	if (!is_array($data)) {
		return [];
	}

	$map = [];

	foreach ($data['services'] as $service) {
		$tlds      = $service[0];
		$endpoints = $service[1];

		if (empty($endpoints)) {
			continue;
		}

		$endpoint = rtrim($endpoints[0], '/');

		foreach ($tlds as $tld) {
			$map[strtolower($tld)] = $endpoint;
		}
	}

	return $map;
}

function get_domain_info_rdap($rdap_server, $domain) {
	$rdap_url = $rdap_server . '/domain/' . urlencode($domain);

	$headers = [
		'Accept'     => 'application/rdap+json',
	];

    try {
        $response = \Unirest\Request::get($rdap_url, $headers);
    } catch (\Unirest\Exception $exception) {
        return null;
    }

	if ($response->code !== 200) {
		return null;
	}

	$rdap_data = json_decode($response->raw_body, true);
	if (!is_array($rdap_data)) {
		return null;
	}

	$start_datetime   = null;
	$updated_datetime = null;
	$end_datetime     = null;
	$registrar        = null;
	$nameservers      = [];

	/* registration / last changed / expiration */
	if (!empty($rdap_data['events']) && is_array($rdap_data['events'])) {
		foreach ($rdap_data['events'] as $event) {
			if (empty($event['eventAction']) || empty($event['eventDate'])) {
				continue;
			}

			$event_action = $event['eventAction'];

			try {
				$dt = new DateTime($event['eventDate']);
				$dt->setTimezone(new DateTimeZone('UTC'));
				$event_date = $dt->format('Y-m-d H:i:s');
			} catch (Exception $e) {
				continue;
			}

			if ($event_action === 'registration') {
				$start_datetime = $event_date;

			} elseif ($event_action === 'expiration') {
				$end_datetime = $event_date;

			} elseif ($event_action === 'last changed') {
				$updated_datetime = $event_date;

			} elseif ($event_action === 'last update of RDAP database' && $updated_datetime === null) {
				$updated_datetime = $event_date;
			}
		}
	}

	/* Registrar */
	if (!empty($rdap_data['entities']) && is_array($rdap_data['entities'])) {
		foreach ($rdap_data['entities'] as $entity) {

			if (empty($entity['roles']) || !is_array($entity['roles'])) {
				continue;
			}

			if (!in_array('registrar', $entity['roles'], true)) {
				continue;
			}

			if (!empty($entity['vcardArray'][1]) && is_array($entity['vcardArray'][1])) {
				foreach ($entity['vcardArray'][1] as $vcard_item) {
					// vcard item format: [ 'fn', {props}, 'text', 'GoDaddy.com, LLC' ]
					if (!is_array($vcard_item) || count($vcard_item) < 4) {
						continue;
					}

					if ($vcard_item[0] === 'fn') {
						$registrar = $vcard_item[3];
						break 2; // found registrar, break out of both loops
					}
				}
			}
		}
	}

	/* Nameservers */
	if (!empty($rdap_data['nameservers']) && is_array($rdap_data['nameservers'])) {
		foreach ($rdap_data['nameservers'] as $nameserver_item) {
			if (!empty($nameserver_item['ldhName'])) {
				$nameservers[] = $nameserver_item['ldhName'];
			} elseif (!empty($nameserver_item['unicodeName'])) {
				$nameservers[] = $nameserver_item['unicodeName'];
			}
		}
	}

	return [
		'start_datetime'   => $start_datetime,
		'updated_datetime' => $updated_datetime,
		'end_datetime'     => $end_datetime,
		'registrar'        => $registrar,
		'nameservers'      => $nameservers,
		'raw'              => $rdap_data,
	];
}
