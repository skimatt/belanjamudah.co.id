<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['google_client_id']     = '997597508849-idssrqrq2322ktnvpli157cj5uo1m3qc.apps.googleusercontent.com'; // Client ID dari Google Console
$config['google_client_secret'] = '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'; // Client Secret dari Google Console
$config['google_redirect_uri'] = 'http://localhost/belanjamudah.co.id/auth/google_callback';
$config['google_scopes']        = array(
    'https://www.googleapis.com/auth/userinfo.email',
    'https://www.googleapis.com/auth/userinfo.profile'
);