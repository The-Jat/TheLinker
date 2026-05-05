<?php

namespace Altum\Controllers;

defined('ALTUMCODE') || die();
session_start();

class AuthRedirect extends Controller {

    public function index() {
        $state = bin2hex(random_bytes(16));
        $_SESSION['oauth_state'] = $state;

        $query = http_build_query([
            'client_id' => AUTH_CLIENT_ID,
            'redirect_uri' => url('auth-callback'),
            'response_type' => 'code',
            'scope' => 'profile email',
            'state' => $state,
        ]);

        header('Location: ' . AUTH_BASE_URL . '/auth/login?' . $query);
        exit;
    }
}