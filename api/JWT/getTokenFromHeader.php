<?php

function getTokenFromHeader () {
    $token = '';
    $matches = array();
    foreach (getallheaders() as $name => $value) {
        if($name == 'Authorization') {
            $token = $value;
        }
    }
    if (preg_match('/Bearer\s(\S+)/', $token, $matches)) {
        return $matches[1];
    }
    return null;
}