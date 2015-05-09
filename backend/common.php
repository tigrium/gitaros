<?php

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

class dbOperation {

    public function query($sql_command) {
        $conn = mysql_connect("127.0.0.1", "gitarosmise", "890511");
        mysql_select_db("gitarosmise", $conn);
        mysql_query('SET CHARACTER SET utf8');

        $sql = mysql_query($sql_command) or die(mysql_error());

        $results = array();
        $i = 0;
        while ($row = mysql_fetch_array($sql)) {
            foreach($row as $key => $value) {
                if (!is_numeric($key)) {
                    $results[$i][$key] = $value;
                }
            }
            $i++;
        }

        mysql_close($conn);

        return $this->jsonRemoveUnicodeSequences($results);
    }
    
    private function jsonRemoveUnicodeSequences($struct) {
        return preg_replace("/\\\\u([a-f0-9]{4})/e", "iconv('UNICODE','UTF-8', pack('V', hexdec('U$1')))", json_encode($struct));
//        return preg_replace("/\\\\u([a-f0-9]{4})/e", "iconv('UCS-4LE','UTF-8',pack('V', hexdec('U$1')))", json_encode($struct));
    }

}

class dataLoader {
    public function load($post) {
        return json_decode(json_encode($post));
        //return json_encode($post)
    }
}

$db = new dbOperation();
$loader = new dataLoader();
?>
