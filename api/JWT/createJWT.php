<?php
require_once('../../vendor/autoload.php');
use Firebase\JWT\JWT;


function createJWT($username) {
    $serverName = 'restaurant-project';
    $secretKey = 'bGS6lzFqvvSQ8ALbOxatm7/Vk7mLQyzqaS34Q4oR1ew=';
    $issuedAt = new DateTimeImmutable();
    $expire = $issuedAt->modify('+6 minutes')->getTimestamp();

    $data = [
        'iat' => $issuedAt,
        'iss' => $serverName,
        'nbf' => $issuedAt,
        'exp' => $expire,
        'username' => $username,
    ];

    return JWT::encode(
        $data,
        $secretKey,
        'HS512'
    );
}

function checkJWT($jwt) {
    $serverName = 'restaurant-project';
    $secretKey = 'bGS6lzFqvvSQ8ALbOxatm7/Vk7mLQyzqaS34Q4oR1ew=';
    $token = JWT::decode($jwt, $secretKey, ['HS512']);
    $now = new DateTimeImmutable();

    if ($token->iss !== $serverName ||
        $token->nbf > $now->getTimestamp() ||
        $token->exp < $now->getTimestamp())
    {
        header('HTTP/1.1 401 Unauthorized');
        exit;
    }
}