<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['protocol'] = 'smtp';
$config['smtp_host'] = 'ssl://smtp.googlemail.com'; // Gunakan SSL
$config['smtp_port'] = 465; // Port standar SSL
$config['smtp_user'] = 'rahmatzkk10@gmail.com';
$config['smtp_pass'] = 'symk llsd zsnc rvua'; // Bukan password akun, tapi App Password
$config['mailtype'] = 'html'; // Wajib HTML untuk link reset
$config['charset'] = 'utf-8';
$config['newline'] = "\r\n";
$config['wordwrap'] = TRUE;
$config['crlf'] = "\r\n";
$config['smtp_timeout'] = 5; // Timeout 5 detik