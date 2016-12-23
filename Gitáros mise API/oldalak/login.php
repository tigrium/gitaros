<?php

require './data/users.php';

class Login {
    /*
     * Itt majd csak az Auth osztály hivogatása és válaszadás lesz.
     */

    private static $IMG_DIR = '../data/user-images/';

//    Itt inkábbb tényleg csak tokent ad vissza (a getTokenben is ellenőriz), a felhasználó adatait (megjelenítendő név, kép, login)
//            csak külön kérésre küldi vissza. api tervben is át kell írni!
    public static function getToken($app) {
        $data = Communication::getRequestHeaderData($app, array('login', 'pass'));
        try {
            $token = Auth::createToken($data);
            Communication::writeJsonResponse($app, array(
                'error' => false,
                'status' => 200,
                'data' => $token
            ));
        } catch (Exception $ex) {
            $status = 400;
            $msg = $ex->getMessage();
            if ($msg == 'Érvénytelen token.') {
                $status = 401;
                $msg = 'Érvénytelen név vagy jelszó.';
            }
            Communication::writeJsonResponse($app, array(
                'error' => true,
                'status' => $status,
                'data' => $msg
            ));
        }
    }

    public static function checkToken($app) {
        $token = Communication::getRequestHeaderData($app, 'token');

        try {
            $valid = Auth::isValidToken($token);
            Communication::writeJsonResponse($app, array(
                'error' => false,
                'status' => 200,
                'data' => array('valid' => $valid)
            ));
        } catch (Exception $ex) {
            Communication::writeDefaultError($app, $ex);
            return;
        }
    }

    public static function signup($app) {
        $adatok = Communication::getRequestBodyData($app, array('login', 'pass', 'email', 'nev', 'kep'));

        if (!isset($adatok['login']) || !isset($adatok['pass']) || !isset($adatok['email'])) {
            Communication::writeJsonResponse($app, array(
                'error' => true,
                'status' => 400,
                'data' => 'Hiányos adatok! (login, pass, email kötelező)'
            ));
            return;
        }

        $userData = array(
            'login' => $adatok['login'],
            'pass' => $adatok['pass'],
            'email' => $adatok['email']
        );
        if (isset($adatok['nev'])) {
            $userData['nev'] = $adatok['nev'];
        }
        if (isset($adatok['kep'])) {
            $data = $adatok['kep'];
            $fileName = $data->name;
            $outFile = Login::$IMG_DIR . $fileName;
            while (is_file($outFile)) {
                $fileName = 'r' . rand() . $data->name;
                $outFile = Login::$IMG_DIR . $fileName;
            }
            $userData['kep'] = $fileName;
        }

        try {
            Users::newUser($userData);
            File::writeImage($data->file, $outFile);
            Communication::writeJsonResponse($app, array(
                'error' => false,
                'status' => 201,
                'data' => array('success' => true)
            ));
        } catch (Exception $ex) {
            Communication::writeDefaultError($app, $ex);
            return;
        }
    }

}
