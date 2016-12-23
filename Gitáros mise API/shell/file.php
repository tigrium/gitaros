<?php

class File {

    public static function readTextFile($file) {
        $myfile = fopen($file, 'r') or die('Unable to open file!');
        $text = fread($myfile, filesize($file));
        fclose($myfile);
        return $text;
    }

    public static function writeImage($base64, $outputFile) {
        $ifp = fopen($outputFile, "wb");

        $data = explode(',', $base64);

        fwrite($ifp, base64_decode($data[1]));
        fclose($ifp);

        return $outputFile;
    }
}
