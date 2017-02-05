<?php

class Misesor {

    public static function getSzoveg($app, $ids) {
        $idsArray = explode(',', $ids);

        if (count($idsArray) === 0) {
            Communication::writeJsonResponse($app, []);
        }

        $sql = '';
        for ($i = 0; $i < count($idsArray); $i++) {
            $sql = $sql . ($i > 0 ? ' UNION ALL ' : '') . '(SELECT enekek.id, szam, cim, szoveg ' .
                'FROM enekek LEFT JOIN enek_diak ON enek_ref = enekek.id LEFT JOIN diak ON dia_ref = diak.id WHERE enekek.id = ' . $idsArray[$i] . ' ORDER BY enek_diak.nr)';
        }
        $res = DB::query($sql);
//        echo $sql;

        $app->response()->header('Content-Type', 'text/plain; charset=utf-8');
        $app->response()->status(200);
        
        $text = '';
        $enekId = 0;
        
        for ($i = 0; $i < count($res); $i++) {
            if ($enekId != $res[$i]['id']) {
                $text = $text . ($res[$i]['szam'] == NULL ? '' : ($res[$i]['szam'] . ' ')) . $res[$i]['cim'] . "\n";
                $enekId = $res[$i]['id'];
            }
            $text = $text . $res[$i]['szoveg'] . "\n\n";
        }
        
        echo $text;
        
//        Communication::writeJsonResponse($app, $res);
    }
    
    public static function getZip($app, $ids, $zipnev) {
        $idsArray = explode(',', $ids);
        if (substr($zipnev, -4) != '.zip') {
            $zipnev = $zipnev . '.zip';
        }
        $zip = new ZipArchive();

        if ($zip->open($zipnev, ZipArchive::CREATE)!==TRUE) {
            exit("cannot open <$zipnev>\n");
        }
        $tmp_fajl = $zip->filename;

        if (count($idsArray) === 0) {
            Communication::writeJsonResponse($app, []);
        }

        $sql = '';
        for ($i = 0; $i < count($idsArray); $i++) {
            $sql = $sql . ($i > 0 ? ' UNION ALL ' : '') . '(SELECT enekek.id, szam, cim, diak.nev, szoveg, enek_diak.nr, ' . $i . ' as sorrend ' .
                'FROM enekek LEFT JOIN enek_diak ON enek_ref = enekek.id LEFT JOIN diak ON dia_ref = diak.id WHERE enekek.id = ' . $idsArray[$i] . ')';
        }
        $sql = $sql . ' ORDER BY sorrend, nr';
        $res = DB::query($sql);
        

        $app->response()->header("Content-disposition: attachment; filename=$zipnev");
        $app->response()->header('Content-Type', 'application/zip');
        $app->response()->status(200);
        
        for ($i = 0; $i < count($res); $i++) {
            $fajlnev = str_pad(($i + 1), strlen((count($res) + 1) . ''), '0', STR_PAD_LEFT) . ' ' 
                    . ($res[$i]['szam'] != null ? $res[$i]['szam'] . ' ' : '') . $res[$i]['cim'] . ' ' . $res[$i]['nev'];
            $fajlnev = str_replace(['!', '?', '/'], [''], $fajlnev) . '.jpg';
            $fajlnev = iconv("UTF-8", "CP852", $fajlnev);
            
            $data = Dia::getDia($res[$i]['szoveg']);
//            echo substr($data, 0, 50) . "\n";
            $data = str_replace('data:image/jpeg;base64,', '', $data);
            $data = str_replace(' ', '+', $data);
            $data = base64_decode($data);
            $zip->addFromString($fajlnev, $data);
//            $zip->addFromString($fajlnev, $data);
        }
        $zip->close();
        
        ob_clean();
        flush();
        
        readfile($tmp_fajl);
        echo $zipnev;
        unlink($tmp_fajl);
    }

}
