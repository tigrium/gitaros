<?php

include 'common.php';

class Enekek {
    public function lista() {
        global $db;
        global $loader;
        $p = $loader->load($_POST);

        $sql = 'SELECT * FROM enekek WHERE TRUE';


        if ($p->szam == ' ') {
            $sql .= " AND (szam IS NULL OR szam = '')";
        } else if (strlen($p->szam) > 0) {
            $sql .= " AND LOWER(szam) like '%" . strtolower($p->szam) . "%'";
        }

        if (strlen($p->cim) > 0) {
            $sql .= " AND LOWER(cim) like '%" . strtolower($p->cim) . "%'";
        }

        if (strlen($p->miseresz) > 0) {
            $sql .= " AND LOWER(alkalom) like '%" . strtolower($p->miseresz) . "%'";
        }

        if (strlen($p->alkalom) > 0) {
            $sql .= " AND LOWER(alkalom) like '%" . strtolower($p->alkalom) . "%'";
        }

        if (strlen($p->kulcs) > 0) {
            $kulcsszavak = explode(',', str_replace(', ', ',', $p->kulcs));
            $sql .= ' AND (';

            foreach ( $kulcsszavak as $kulcsszo ) {
                $sql .= "LOWER(szavak) LIKE '%" . strtolower($kulcsszo) . "%' OR ";
            }
            $sql = substr($sql, 0, -4); // -4: ' OR ' hossza
            $sql .= ')';
        }

        $res = $db->query($sql);

        return $res;
//        return json_encode(array("valasz" => json_decode($res), "sql" => $sql));
    }
}

$enekek = new Enekek();
echo $enekek->lista();

return;

if (isset($_POST['kulcs'])) {
    $kulcsszavak = explode(',', str_replace(', ', ',', $_POST['kulcs']));
    $kulcs = '';
    foreach ( $kulcsszavak as $kulcsszo ) {
        $kulcs .= "LOWER(szavak) LIKE '%" . strtolower(iconv('ASCII', 'UTF-8//IGNORE', $kulcsszo)) . "%' OR ";
    }
    if ( strlen($kulcs) > 0 ) {
        $kulcs = substr($kulcs, 0, -4);
        $kulcs = '(' . $kulcs . ')';
        if (strlen($where) > 0) {
            $where .= " AND ";
        }
        $where .= $kulcs;
    }
}

if (strlen($where) > 0) {
    $where = ' WHERE ' . $where;
}

// if (true) {
  //  echo 'SELECT * FROM `Enekek`' . $where;
//     return;
// }

$res = $db->query('SELECT * FROM `Enekek`' . $where);

echo $res;
