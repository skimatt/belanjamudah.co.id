<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Path File: application/config/midtrans.php

// Pastikan kunci ini adalah kunci Server Sandbox Anda
$config['midtrans_server_key'] = 'Mid-server-qVPJHgpjc22G7RLRDUXToAF7'; 

// Pastikan kunci ini adalah kunci Client Sandbox Anda
$config['midtrans_client_key'] = 'Mid-client-Jwz9qaMdisGOorfE'; 

// Pengaturan Environment
$config['midtrans_is_production'] = FALSE; // TRUE untuk Live/Production

// Pengaturan Keamanan
$config['midtrans_is_sanitized'] = TRUE; 
$config['midtrans_is_3ds'] = TRUE; 