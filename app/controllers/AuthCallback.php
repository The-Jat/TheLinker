<?php

namespace Altum\Controllers;

defined('ALTUMCODE') || die();
session_start();

class AuthCallback extends Controller {

    public function index() {


        if (!isset($_GET['state']) || $_GET['state'] !== $_SESSION['oauth_state']) {
            die('Invalid state');
        }

        unset($_SESSION['oauth_state']);

        if(!isset($_GET['code'])) {
            die('Missing code');
        }

        $code = $_GET['code'];

        // 🔁 Exchange code for token
        $token_response = json_decode(file_get_contents('http://localhost:3000/auth/token', false, stream_context_create([
            'http' => [
                'method' => 'POST',
                'header' => "Content-Type: application/x-www-form-urlencoded",
                'content' => http_build_query([
                    'grant_type' => 'authorization_code',
                    'client_id' => AUTH_CLIENT_ID,
                    'client_secret' => AUTH_CLIENT_SECRET,
                    'redirect_uri' => url('auth-callback'),
                    'code' => $code,
                ])
            ]
        ])), true);

        if(!isset($token_response['access_token'])) {
            die('Token failed');
        }

        $access_token = $token_response['access_token'];

        // 👤 Get user
        $opts = [
            "http" => [
                "method" => "GET",
                "header" => "Authorization: Bearer $access_token"
            ]
        ];

        $context = stream_context_create($opts);
        $user = json_decode(file_get_contents(AUTH_BASE_URL . '/auth/me', false, $context), true);

        if(!$user || !isset($user['email'])) {
            die('User fetch failed');
        }

        $db = database();

        $email = $db->real_escape_string($user['email']);
        $name = $db->real_escape_string($user['name']);

        // 🔍 Check existing user
        $existing = $db->query("SELECT * FROM users WHERE email = '$email'")->fetch_object();
        $result = $db->query("SELECT * FROM users WHERE email = '$email'");

        if($result && $result->num_rows > 0) {
            $existing = $result->fetch_object();
            $user_id = $existing->user_id;
        } else {
            $password = password_hash(bin2hex(random_bytes(16)), PASSWORD_DEFAULT);

            $db->query("
            INSERT INTO users (email, name, password, type, status, datetime)
            VALUES ('$email', '$name', '$password', 0, 1, NOW())
            ");


            $user_id = $db->insert_id;
        }
                
        // 🔍 Fetch full user row (IMPORTANT)
        $user_row = $db->query("SELECT * FROM users WHERE user_id = $user_id")->fetch_object();


        session_set('user_id', $user_row->user_id);
        session_set('user_password_hash', md5($user_row->password));

        // 🍪 Optional persistent login (recommended)
        setcookie('user_id', $user_row->user_id, time() + 60*60*24*30, '/');
        setcookie('user_password_hash', md5($user_row->password), time() + 60*60*24*30, '/');

        // 📊 Update login metadata (Altum internal)
        (new \Altum\Models\User())->login_aftermath_update($user_row->user_id);

        // 🚀 Redirect
        header('Location: ' . url('dashboard'));
        exit;
    }
}