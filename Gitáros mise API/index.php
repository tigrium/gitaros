<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

error_reporting(-1);

$target = $_REQUEST['q'];
$headers = getallheaders();
$body = json_decode(file_get_contents('php://input'));

$respose = array(
    'path' => $target,
    'body' => $body,
    'headers' => $headers
);

echo json_encode($respose, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);