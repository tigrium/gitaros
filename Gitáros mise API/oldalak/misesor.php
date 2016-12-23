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

}
