<?php

require './data/enekek.php';

class Enek {
    public static function getList($app, $filter) {
        $res = Enekek::enekLista($filter);
        $res = array(
            'error' => false,
            'status' => 200,
            'data' => $res
        );
        Communication::writeJsonResponse($app, $res);
    }
    
    public static function getAll($app) {
        $res = Enekek::all();
        $res = array(
            'error' => false,
            'status' => 200,
            'data' => $res
        );
        Communication::writeJsonResponse($app, $res);
    }

    public static function uj($app) {
        $data = Communication::getRequestBodyData($app);
        $res = Enekek::uj($data);
        $res = array(
            'error' => false,
            'status' => 200,
            'data' => $res
        );
        Communication::writeJsonResponse($app, $res);
    }

    public static function adatok($app, $id) {
        $res = Enekek::adatok($id);
        $res = array(
            'error' => false,
            'status' => 200,
            'data' => $res
        );
        Communication::writeJsonResponse($app, $res);
    }

    public static function addDia($app, $id) {
        $data = Communication::getRequestBodyData($app);
        $res = Enekek::addDia($id, $data);
        $res = array(
            'error' => false,
            'status' => 200,
            'data' => $res
        );
        Communication::writeJsonResponse($app, $res);
    }

    public static function addDiaRef($app, $id) {
        $data = Communication::getRequestBodyData($app);
        $res = Enekek::addDiaRef($id, $data->dia);
        $res = array(
            'error' => false,
            'status' => 200,
            'data' => $res
        );
        Communication::writeJsonResponse($app, $res);
    }
    
//    public function getList($mode, $parameters) {
//        $selectList;
//        if ($mode == 'quick') {
//            $selectList = 'e.id, e.szam ';
//        } elseif ($mode == 'normal') {
//            $selectList = 'e.id, e.szam, e.cim, e.alkalom, e.alleluja, e.megjegyzes, mr.mise, d.diak ';
//        } elseif ($mode == 'count') {
//            $selectList = 'count(*) ';
//        } else {
//            return;
//        }
//
//        $sql = 'SELECT ' . $selectList
//                . 'FROM enekek AS e '
//                . 'LEFT JOIN (SELECT enek_ref, GROUP_CONCAT(miseresz_ref) AS mise '
//                . 'FROM enek_misereszek '
//                . 'GROUP BY enek_ref) AS mr ON mr.enek_ref = e.id '
//                . 'LEFT JOIN (SELECT enek_ref, GROUP_CONCAT(dia_ref) AS diak '
//                . 'FROM enek_diak '
//                . 'GROUP BY enek_ref) AS d ON d.enek_ref = e.id '
//                . 'WHERE TRUE';
//
//        if ($parameters['szam'] != null) {
//            if ($parameters['szam'] == '-') {
//                $sql .= ' AND e.szam IS NULL';
//            } else {
//                $sql .= ' AND UPPER(e.szam) LIKE \'%?%\'';
//            }
//        }
//
//        if ($parameters['cim'] != null) {
//            $sql .= ' AND UPPER(e.cim) LIKE \'%?%\'';
//        }
//
//        echo $sql;
////        echo '[
////  {
////    "id": 2,
////    "szam": "1",
////    "cim": "A béke napja",
////    "alkalom" :"advent",
////    "alleluja": false,
////    "megj": "Első sor jöhet ide, ha különleges",
////    "mise": [1, 3, 5],
////    "diak": [6, 7, 8, 7]
////  }
////]';
//    }

}
