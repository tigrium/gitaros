<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(-1);

require 'Slim/Slim.php';
\Slim\Slim::registerAutoloader();

require './lib/jwt_helper.php';

require './shell/communication.php';

require './oldalak/login.php';
require './oldalak/enekek.php';

$app = new \Slim\Slim();
$login = new Login();

$app->get('/', function() {
    echo 'Gitáros mise API';
});

$app->get('/login', function() use ($app, $login) {
    $login->getToken($app);
});
$app->get('/login/check', function() use ($app, $login) {
    $login->checkToken($app);
});

$app->get('/enekek/:mode', function($mode) use ($app) {
    $headers = $app->request->headers;
    $req = $app->request();

    $parameters = array(
        'szam' => $req->get('szam'),
        'cim' => $req->get('cim'),
        'mise' => $req->get('mise'),
        'alkalom' => $req->get('alkalom'),
        'alleluja' => $req->get('alleluja'),
        'megj' => $req->get('megj')
    );

    $enekek = new Enekek();
    $enekek->getList($mode, $parameters);
});


/* TESZT */
$app->get('/hello/:name', function ($name) {
    header('Content-Type: text/html; charset=utf-8');
    echo "Hello, $name";
});

$app->get('/test', function() {
    echo 'teszt get';
});

$app->post('/test', function() {
    echo 'teszt post';
});

$app->put('/test', function() {
    echo 'teszt put';
});

$app->delete('/test', function() {
    echo 'teszt delete';
});

$app->get('/test/:id', function($id) {
    echo "teszt get id: $id";
});
/* TESZT VÉGE */

$app->run();
