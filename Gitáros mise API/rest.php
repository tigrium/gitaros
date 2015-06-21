<?php

class Rest {

    public function go($path, $method, $headers, $parameters, $body) {
        if (preg_match("/\/enekek\/([^?.]*)/i", $path, $matches)) {
            include_once 'oldalak/enekek.php';
            $mode = $matches[1];
            $enekek = new Enekek();
            $enekek->getList($mode, $parameters);
        } else if (preg_match("/\/enek\/(.*)\/delete([^?.]*)/i", $path, $matches)) {
            include_once 'oldalak/enekek.php';
            $id = $matches[1];
            echo "$method ének törlése, id: $id\n";
        } else if (preg_match("/\/enek\/([^?.]*)/i", $path, $matches)) {
            include_once 'oldalak/enekek.php';
            $id = $matches[1];
            echo "$method ének, id: $id\n";
        } else {
            $respose = array(
                'path' => $target,
                'method' => $method,
                'parameters' => $parameters,
                'body' => $body,
                'headers' => $headers
            );
            echo json_encode($respose, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        }
    }

}
