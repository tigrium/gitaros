<?php

class Communication {

    public static function writeJsonResponse($app, $res) {
        $app->response()->header('Content-Type', 'application/json; charset=utf-8');
        $app->response()->status(200);
        echo json_encode($res, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }

//    public static function writeUnauthorizedResponse($app, $missedToken) {
//        $app->response()->header('Content-Type', 'text/plain');
//        $app->response()->status(401);
//        if ($missedToken) {
//            echo 'Azonosítás szükséges! Hiányzó token.';
//        } else {
//            echo 'Érvénytelen token.';
//        }
//    }

    public static function writeDefaultError($app, $ex) {
        $status = 400;
        if ($ex->getMessage() == 'Érvénytelen token.') {
            $status = 401;
        }
        Communication::writeJsonResponse($app, array(
            'error' => true,
            'status' => $status,
            'data' => $ex->getMessage()
        ));
    }

    public static function getRequestHeaderData($app, $keyOrKeys) {
        if (is_array($keyOrKeys)) {
            $data = array();
            foreach ($keyOrKeys as $key) {
                $data[$key] = $app->request->headers->get($key);
            }
            return $data;
        } else {
            return $app->request->headers->get($keyOrKeys);
        }
    }

    public static function getRequestBodyData($app, $keyOrKeys = null) {
        $obj = json_decode($app->request->getBody());

        if ($keyOrKeys == null) {
            return $obj;
        } else if (is_array($keyOrKeys)) {
            $data = array();
            foreach ($keyOrKeys as $key) {
                try {
                    $data[$key] = $obj->$key;
                } catch (Exception $ex) {
                    
                }
            }
            return $data;
        } else {
            try {
                return $obj->$keyOrKeys;
            } catch (Exception $ex) {
                
            }
        }
    }
    
    public static function getRequestParameters($app, $keyOrKeys = null) {
        if (is_array($keyOrKeys)) {
            $data = array();
            foreach ($keyOrKeys as $key) {
                $data[$key] = $app->request->get($key);
            }
            return $data;
        } else if ($keyOrKeys != null) {
            return $app->request->get($keyOrKeys);
        } else {
            return [];
        }
    }

}
