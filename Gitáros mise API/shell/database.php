<?php

class DB {

    public static function query($sql, $parameters = null) {
        $dbData = json_decode(File::readTextFile('./secret/db.json'));
        try {
            $conn = new PDO('mysql:host=' . $dbData->servername . ';dbname=' . $dbData->dbname, $dbData->username, $dbData->password);
//            if ($parameters == null) {
//                $result = $conn->query($sql, PDO::FETCH_ASSOC);
//                return $result;
//            }
            if ($stmt = $conn->prepare($sql)) {
                $stmt->execute($parameters);
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                return $result;
            } else {
                printf("Errormessage: %s\n", $conn->error);
            }
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    public static function insert($sql, $parameters = null) {
        $dbData = json_decode(File::readTextFile('./secret/db.json'));
        try {
            $conn = new PDO('mysql:host=' . $dbData->servername . ';dbname=' . $dbData->dbname, $dbData->username, $dbData->password);
            if ($stmt = $conn->prepare($sql)) {
                $stmt->execute($parameters);
                $result = $conn->lastInsertId();
                return $result;
            } else {
                printf("Errormessage: %s\n", $conn->error);
            }
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }
    
    public static function queryFirst($sql, $parameters = null) {
        $res = DB::query($sql, $parameters);
        return $res[0];
    }

}
