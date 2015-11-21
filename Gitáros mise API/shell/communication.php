<?php

class Communication {

    public static function writeJsonResponse($app, $res) {
        $app->response()->header('Content-Type', 'application/json');
        $app->response()->status(200);
        echo json_encode($res, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }

    public static function writeUnauthorizedResponse($app, $missedToken) {
        $app->response()->header('Content-Type', 'text/plain');
        $app->response()->status(401);
        if ($missedToken) {
            echo 'Azonosítás szükséges! Hiányzó token.';
        } else {
            echo 'Érvénytelen token.';
        }
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

    public static function getRequestBodyData($app, $keyOrKeys) {
        if (is_array($keyOrKeys)) {
            $data = array();
            foreach ($keyOrKeys as $key) {
                $data[$key] = $app->request()->get($key);
            }
            return $data;
        } else {
            return $app->request()->get($keyOrKeys);
        }
    }

}
