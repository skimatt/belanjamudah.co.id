<?php
// Password yang Anda inginkan untuk Admin
$plain_password = 'admin123'; 

// Hasilkan hash yang aman menggunakan algoritma BCRYPT (standar PHP)
$hashed_password = password_hash($plain_password, PASSWORD_DEFAULT);

echo "Password Teks Biasa (JANGAN SIMPAN INI!): " . $plain_password . "\n";
echo "Hash Password Admin (SALIN INI): " . $hashed_password . "\n";
?>