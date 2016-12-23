<?php

class Auth {
    /*
     * Ide jön majd az autentikáció lényege.
     *  - Adatbázis kommunikációval eldönteni, hogy jó-e a név-jelszó páros
     *  - Token előállítása
     *  - Kapott token ellenőrzése: adat kinyerése, adatbázissal ellenőrizni
     * Figyelni kell arra is, hogy ha változik a kulcs, akkor is "rossz token" választ adjon.
     */
    
    public static function createToken($data) {
            $token = JWT::encode(array(
                'login' => $data['login'],
                'pass' => $data['pass']), Auth::loadKey());
            if (Auth::isValidToken($token)) {
                return $token;
            }
//            $res = Auth::userDataFromToken($token);
//            $res['token'] = $token;
//            
//            return $res;
            
//            return array(
//                'id' => $res['id']
//            );
    }
    
     public static function isValidToken($token) {
        try {
            $decoded = JWT::decode($token, Auth::loadKey());
            $sql = 'SELECT count(*) as count FROM users WHERE login = ? AND pass = ?';
            $res = DB::queryFirst($sql, array($decoded->login, $decoded->pass));

            if ($res['count'] == 1) {
                return true;
            } else {
                throw new Exception('név-jelszó');
            }
        } catch (Exception $ex) {
            if ($ex->getMessage() == 'Signature verification failed' || $ex->getMessage() == 'Null result with non-null input' 
                    || $ex->getMessage() == 'név-jelszó') {
                throw new Exception('Érvénytelen token.');
            } else {
                throw $ex;
            }
        }
    }
    
    private static function loadKey() {
        return File::readTextFile('./secret/keyfile.txt');
    }
}
