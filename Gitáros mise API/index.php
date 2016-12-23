<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(-1);

require 'Slim/Slim.php';
\Slim\Slim::registerAutoloader();

require './Slim/Middleware/CorsSlim.php';
require './lib/jwt_helper.php';

require './shell/file.php';
require './shell/database.php';
require './shell/auth.php';
require './shell/communication.php';

require './oldalak/login.php';
require './oldalak/enek.php';
require './oldalak/dia.php';
require './oldalak/misesor.php';

$app = new \Slim\Slim();
$corsOptions = array(
    "origin" => "*"
);
$cors = new \CorsSlim\CorsSlim($corsOptions);

$app->add($cors);
//$login = new Login();

$app->get('/', function() use ($app) {
    $app->response()->header('Content-Type', 'text/html; charset=utf-8');
    $app->response()->write('Gitáros mise API');
});

$app->get('/login', function() use ($app) {
    Login::getToken($app);
});
$app->get('/login/check', function() use ($app) {
    Login::checkToken($app);
});
$app->post('/login/signup', function() use ($app) {
    Login::signup($app);
});

//$app->get('/enekek:p', function() use ($app) {
////    try {
//        Auth::isValidToken(Communication::getRequestHeaderData($app, 'token'));
//        Enek::getList($app);
////    } catch (Exception $ex) {
////        Communication::writeDefaultError($app, $ex);
////    }
//});

$app->get('/enekek', function() use ($app) {
    Auth::isValidToken(Communication::getRequestHeaderData($app, 'token'));
    $filter = json_decode(Communication::getRequestHeaderData($app, 'filter'));
    if (isset($filter)) {
        Enek::getList($app, $filter);
    } else {
        Enek::getAll($app);
    }
});


$app->post('/enek', function() use ($app) {
    Auth::isValidToken(Communication::getRequestHeaderData($app, 'token'));
    Enek::uj($app);
});

$app->get('/enek/:id', function($id) use ($app) {
    Auth::isValidToken(Communication::getRequestHeaderData($app, 'token'));
    Enek::adatok($app, $id);
});

$app->post('/enek/:id/ujdia', function($id) use ($app) {
    Auth::isValidToken(Communication::getRequestHeaderData($app, 'token'));
    Enek::addDia($app, $id);
});

$app->post('/enek/:id/ujdiaref', function($id) use ($app) {
    Auth::isValidToken(Communication::getRequestHeaderData($app, 'token'));
    Enek::addDiaRef($app, $id);
});

$app->get('/dia/create', function() use ($app) {
    Dia::createDia($app);
});

$app->post('/dia/create', function() use ($app) {
    Dia::createDia($app);
});

$app->get('/misereszek', function() use ($app) {
    $res = DB::query('select * from misereszek order by nr;');
    $res = array(
        'error' => false,
        'status' => 200,
        'data' => $res
    );
    Communication::writeJsonResponse($app, $res);
});

$app->get('/misesor/szoveg/:ids', function($ids) use ($app) {
    Misesor::getSzoveg($app, $ids);
});


//$app->get('/enekek/:mode', function($mode) use ($app) {
//    $headers = $app->request->headers;
//    $req = $app->request();
//
//    $parameters = array(
//        'szam' => $req->get('szam'),
//        'cim' => $req->get('cim'),
//        'mise' => $req->get('mise'),
//        'alkalom' => $req->get('alkalom'),
//        'alleluja' => $req->get('alleluja'),
//        'megj' => $req->get('megj')
//    );
//
//    $enekek = new Enekek();
//    $enekek->getList($mode, $parameters);
//});


/* TESZT */
$app->get('/hello/:name', function ($name) {
    header('Content-Type: text/html; charset=utf-8');
    echo "Hello, $name";
});

$app->get('/test', function() {
    echo 'teszt get';
//    Az adatbázis lekérdezés nem az összes eredményt adja vissza, hanem az első sort.
    $res = DB::query('select * from misereszek;');
    print_r($res);
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

//$app->get('/phpinfo', function() {
//    phpinfo();
//});

/* TESZT VÉGE */

$app->run();
