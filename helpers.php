<?php
// helpers.php

function get_bearer_token() {
    $headers = apache_request_headers();
    if (isset($headers['Authorization'])) {
        $matches = [];
        if (preg_match('/Bearer\s(\S+)/', $headers['Authorization'], $matches)) {
            return $matches[1];
        }
    }
    return null;
}

function json_response($data, $status_code = 200) {
    header_remove();
    header("Content-Type: application/json");
    http_response_code($status_code);
    echo json_encode($data);
    exit();
}
