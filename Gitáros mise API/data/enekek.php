<?php

class Enekek {

    public static function enekLista($filter) {
        $sql = 'SELECT id, szam, cim, alleluja, alkalom, megjegyzes FROM `enekek` ' //, d.diak FROM `enekek` '
        //        . 'LEFT JOIN (SELECT enek_ref, GROUP_CONCAT(dia_ref) AS diak FROM enek_diak GROUP BY enek_ref) AS d ON d.enek_ref = enekek.id '
                . 'WHERE TRUE';
        $parameters = array();
        if (isset($filter->szam)) {
            $sql .= ' AND szam2 like :szam';
            $parameters[':szam'] = '%' . str_replace([' ', '.', '/'], '', strtolower($filter->szam)) . '%';
        }
        if (isset($filter->cim)) {
            $sql .= ' AND lower(cim) like :cim';
            $parameters[':cim'] = '%' . strtolower($filter->cim) . '%';
        }
        if (isset($filter->alleluja)) {
            $sql .= ' AND alleluja = :alleluja';
            $parameters[':alleluja'] = $filter->alleluja ? 1 : 0;
        }
        if (isset($filter->alkalom)) {
            $alkalmak = explode(';', $filter->alkalom);
            if (count($alkalmak) > 0) {
                $sql .= ' AND (FALSE';
            }
            foreach ($alkalmak as $key => $alkalom) {
                $sql .= " OR lower(alkalom) like :alkalom$key";
                $parameters[":alkalom$key"] = '%' . $alkalom . '%';
            }
            if (count($alkalmak) > 0) {
                $sql .= ')';
            }
        }
//       'megjegyzes', 'miseresz', 'kotta'
        if (isset($filter->megjegyzes)) {
            $sql .= ' AND megjegyzes like :megjegyzes';
            $parameters[':megjegyzes'] = '%' . $filter->megjegyzes . '%';
        }
        if (isset($data->miseresz)) {
            $miseresz_scr = explode(';', $filter->miseresz);
            $miseresz = [];
            foreach ($miseresz_scr as $mr) {
                $miseresz[] = intval($mr);
            }
            $sql .= ' AND id IN (SELECT enek_ref FROM enek_misereszek WHERE miseresz_ref IN (' . implode(',', $miseresz) . '))';
        }
        if (isset($filter->kotta)) {
            //később
        }

        try {
            $enekek = DB::query($sql, $parameters);
            /*$diak = DB::query('SELECT * FROM diak');
            
            $hasheltDiak = array();
            foreach ($diak as $dia) {
                $hasheltDiak[$dia['id']] = $dia;
            }
            
            for ($i = 0; $i < count($enekek); $i++) {
                $diaIds = explode(',', $enekek[$i]['diak']);
                $enekek[$i]['diak'] = array();
                foreach ($diaIds as $id) {
                    if ($id == '') {
                        break;
                    }
                    $dia = $hasheltDiak[$id];
                    $dia['kep'] = Dia::getDia($dia['szoveg']);
                }
                $enekek[$i]['diak'][] = $dia;
            }*/
            
            return $enekek;
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
    public static function all() {
        $sql = ' SELECT * FROM enekek AS e
	LEFT JOIN (SELECT enek_ref, GROUP_CONCAT(miseresz_ref) AS mise
               FROM enek_misereszek 
               GROUP BY enek_ref) AS mr ON mr.enek_ref = e.id 
	LEFT JOIN (SELECT enek_ref, GROUP_CONCAT(dia_ref) AS diak
           FROM enek_diak 
           GROUP BY enek_ref) AS d ON d.enek_ref = e.id ORDER BY szam, cim';
       

        try {
            return DB::query($sql);
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
    public static function uj($data) {
        if (isset($data->szam)) {
            $szam2 = Enekek::szamKonvert($data->szam);
        }
        $sql = 'INSERT INTO enekek (cim, alleluja';
        $values = " VALUES('" . $data->cim . "', " . ($data->alleluja ? 'true' : 'false');
        
        if (isset($data->szam) && $data->szam !== '') {
            $sql .= ', szam, szam2';
            $values .= ", '" . $data->szam . "', '" . $szam2 . "'";
        }
        
        if (isset($data->alkalom) && $data->alkalom !== '') {
            $sql .= ', alkalom';
            $values .= ", '" . $data->alkalom . "'";
        }
        
        if (isset($data->megjegyzes) && $data->megjegyzes !== '') {
            $sql .= ', megjegyzes';
            $values .= ", '" . $data->megjegyzes . "'";
        }
        
        $values .= ')';
        $sql .= ')' . $values;
        
        $enekId = DB::insert($sql);
        
        if (isset($data->misereszek)) {
            foreach ($data->misereszek as $miseresz) {
                DB::insert("INSERT INTO enek_misereszek (enek_ref, miseresz_ref) VALUES ($enekId, $miseresz)\n");
            }
        }
        
        return array('success' => true, 'id' => $enekId);
    }
    
    public static function adatok($id) {
        $res = DB::query('SELECT id, szam, cim, alleluja, alkalom, megjegyzes FROM enekek WHERE id = :id', array('id' => $id));
        $adatok = $res[0];
        $adatok['alleluja'] = $adatok['alleluja'] === 1;
        
        $res = DB::query('SELECT diak.id, nev, szoveg, nr FROM enek_diak ' .
                                                         'LEFT JOIN diak ON diak.id = enek_diak.dia_ref ' .
                         'WHERE enek_ref = :enekId ORDER BY nr', array('enekId' => $id));
        $diak = array();
        
        foreach ($res as $dia) {
            $dia['kep'] = Dia::getDia($dia['szoveg']);
            $diak[] = $dia;
        }
        
        $adatok['diak'] = $diak;
        
        $misereszek = DB::query('SELECT misereszek.* FROM enek_misereszek  ' .
                                                         'LEFT JOIN misereszek ON misereszek.id = enek_misereszek.miseresz_ref ' .
                         'WHERE enek_ref = :enekId ORDER BY nr', array('enekId' => $id));
        
        $adatok['misereszek'] = $misereszek;
        
        return $adatok;
    }
    
    public static function addDia($id, $data) {
        $diaId = DB::insert('INSERT INTO diak (nev, szoveg) VALUES (:nev, :szoveg)', array('nev' => $data->nev, 'szoveg' => $data->szoveg));
        Enekek::addDiaRef($id, $diaId);
    }
    
    public static function addDiaRef($enekId, $diaId) {
        $res = DB::query('SELECT count(*) AS count FROM enek_diak WHERE enek_ref = :id', array('id' => $enekId));
        $nr = $res[0]['count'];
        DB::insert('INSERT INTO enek_diak (enek_ref, dia_ref, nr) VALUES (:enek, :dia, :nr)', array('enek' => $enekId, 'dia' => $diaId, 'nr' => $nr));
    }
    
    
    private static function szamKonvert($szam) {
        return strtolower(preg_replace('/[ \/\.\(\)]/', '', $szam));
    }

}
