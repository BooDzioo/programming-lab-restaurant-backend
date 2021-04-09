<?php
require_once('../../vendor/autoload.php');
use Firebase\JWT\JWT;


function createJWT($userId) {
    $serverName = 'restaurant-project';
    $secretKey = 'bGS6lzFqvvSQ8ALbOxatm7/Vk7mLQyzqaS34Q4oR1ew=';
    $issuedAt = new DateTimeImmutable();
    $expire = $issuedAt->modify('+10 minutes')->getTimestamp();
    $payload = array('userId' => $userId);

    $data = [
        'iat' => $issuedAt->getTimestamp(),
        'iss' => $serverName,
        'nbf' => $issuedAt->getTimestamp(),
        'exp' => $expire,
        "data" => $payload,
    ];

    return JWT::encode(
        $data,
        $secretKey,
        'HS512'
    );
}

