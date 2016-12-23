<?php

class Users {

    public static function newUser($data) {
        $sql = 'SELECT count(*) as count FROM users WHERE lower(login) = lower(?)';
        $res = DB::queryFirst($sql, array($data['login']));

        if ((int) $res['count'] > 0) {
            throw new Exception('Már létező felhasználónév.');
        }

        $sql = 'INSERT INTO users (login, pass, email';
        if (isset($data['nev'])) {
            $sql .= ', nev';
        }
        if (isset($data['kep'])) {
            $sql .= ', kep';
        }
        $sql .= ') VALUES (:login, :pass, :email';
        if (isset($data['nev'])) {
            $sql .= ', :nev';
        }
        if (isset($data['kep'])) {
            $sql .= ', :kep';
        }
        $sql .= ')';

        $parameters = array(
            ':login' => $data['login'],
            ':pass' => md5($data['pass']),
            ':email' => $data['email']
        );
        if (isset($data['nev'])) {
            $parameters[':nev'] = $data['nev'];
        }
        if (isset($data['kep'])) {
            $parameters[':kep'] = $data['kep'];
        }

        try {
            DB::query($sql, $parameters);
        } catch (Exception $ex) {
            throw $ex;
        }
    }

}
