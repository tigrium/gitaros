<?php

class Login {
    /*
     * Itt majd csak az Auth osztály hivogatása és válaszadás lesz.
     */
    public function getToken($app) {
        $login = Communication::getRequestHeaderData($app, array('user', 'pass'));
        // név jelszó ellenőrzés
        if ($login['user'] == 'kata' && $login['pass'] == '12345') {
            $token = array(
                'user' => $login['user'],
                'pass' => $login['pass']
            );
            $res = array(
                'id' => 1,
                'login' => 'kata',
                'name' => 'Kata',
                'token' => JWT::encode($token, Login::loadKey())
            );
            Communication::writeJsonResponse($app, $res);
        } else {
            $app->response()->header('Content-Type', 'text/plain');
            $app->response()->status(401);
            echo 'Érvénytelen név vagy jelszó.';
        }
    }

    public function checkToken($app) {
        $token = Communication::getRequestHeaderData($app, 'token');
        $decoded = JWT::decode($token, Login::loadKey());
        echo json_encode($decoded, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }

    public function signup() {
        
    }

    private static function loadKey() {
        $keyfile = 'keyfile.txt';
        $myfile = fopen($keyfile, 'r') or die('Unable to open file!');
        $key = fread($myfile, filesize($keyfile));
        fclose($myfile);
        return $key;
    }

}
