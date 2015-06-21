<?php

include_once 'rest.php';

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE');

error_reporting(-1);

$path = $_REQUEST['q'];
$method = $_SERVER['REQUEST_METHOD'];
$parameters = $_GET;
unset($parameters['q']);
$headers = getallheaders();
$body = json_decode(file_get_contents('php://input'));

$rest = new Rest();

$rest->go($path, $method, $headers, $parameters, $body);