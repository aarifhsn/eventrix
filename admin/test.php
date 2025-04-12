<?php

$hash_password = password_hash('12345678', PASSWORD_DEFAULT);

// echo $hash_password;

// $token = time();
$token = bin2hex(random_bytes(16));
echo $token;