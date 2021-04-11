<?php
require_once('../../vendor/autoload.php');
use Firebase\JWT\JWT;

function checkJWT($jwt, $userId) {
    $serverName = 'restaurant-project';
    $secretKey = 'bGS6lzFqvvSQ8ALbOxatm7/Vk7mLQyzqaS34Q4oR1ew=';
    $token = JWT::decode($jwt, $secretKey, ['HS512']);
    $now = new DateTimeImmutable();
    if (
        $token->data->userId !== $userId ||
        $token->iss !== $serverName ||
        $token->nbf > $now->getTimestamp() ||
        $token->exp < $now->getTimestamp()
    )
    {
        http_response_code(401);
        echo json_encode(array('message' => 'Auth token expired'));
        die();
    } else {
        return true;
    }
}