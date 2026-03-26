<?php
/*
 * Copyright (c) 2025 AltumCode (https://altumcode.com/)
 *
 * This software is licensed exclusively by AltumCode and is sold only via https://altumcode.com/.
 * Unauthorized distribution, modification, or use of this software without a valid license is not permitted and may be subject to applicable legal actions.
 *
 *  View all other existing AltumCode projects via https://altumcode.com/
 *  Get in touch for support or general queries via https://altumcode.com/contact
 *  Download the latest version via https://altumcode.com/downloads
 *
 *  X/Twitter: https://x.com/AltumCode
 *  Facebook: https://facebook.com/altumcode
 *  Instagram: https://instagram.com/altumcode
 */

function session_start_if_not_started() {
	global $session_started;

	if($session_started) {
		return;
	}

	/* Debug output */
//	echo '<pre>Session started from:' . PHP_EOL;
//	debug_print_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
//	echo '</pre>';

	$should_start_session = true;

	if(isset(\Altum\Router::$controller_settings['allow_sessions']) && !\Altum\Router::$controller_settings['allow_sessions']) {
		$should_start_session = false;
	}

	if($should_start_session) {
        session_start();
		$session_started = true;
	} else {
		$session_started = false;
	}
}

/* Setter */
function session_set($key, $value) {
	session_start_if_not_started();
	$_SESSION[$key] = $value;
}

/* Getter */
function session_get($key, $default = null) {
	session_start_if_not_started();
	return isset($_SESSION[$key]) ? $_SESSION[$key] : $default;
}

/* Unsetter */
function session_unset_key($key) {
	session_start_if_not_started();
	if(isset($_SESSION[$key])) {
		unset($_SESSION[$key]);
	}
}

/* Checker */
function session_has($key) {
	session_start_if_not_started();
	return isset($_SESSION[$key]);
}
