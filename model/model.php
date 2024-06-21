<?php
header("Access-Control-Allow-Origin: " . (empty($_SERVER['HTTP_ORIGIN']) ? '*' : $_SERVER['HTTP_ORIGIN']));
header("Access-Control-Allow-Methods: PUT, GET, POST,DELETE,OPTIONS");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token, content-type, Accept");
include_once "./libs/general.php";
include_once "./libs/dbapi.php";

class Model {
    private $db;
    public function __construct() {
        $this->db = new dbapi();
        $this->db->connect(DBNAME, DBHOST, DBUSER, DBPASS);
    }

    public function get_all_todos() {
        $sql = sprintf("select * from todos where status != 5");
        $rs = $this->db->Execute($sql);
        if ($rs->RecordCount() > 0) {
            while ($s = $rs->FetchNextObject()) {
                $s->completed = $s->status == 2 ? true : false;
                $td[] = $s;
            }
        }
        return $td;
    }
    public function add_todo($text) {
        $sql = sprintf("insert into todos set text='%s' , status = 1", $text);
        $this->db->Execute($sql);
        // $this->db->Insert_ID();
    }
    public function update_todo($data) {
        $sql = sprintf("update todos set status=%d where id = %d", $data->status, $data->id);
        $this->db->Execute($sql);
    }
    public function return_result($result, $error = "", $clean_output = 0) {
        header('Content-Type: application/json; charset=utf-8');
        if ($clean_output == 1) {
            echo json_encode($result);
            die();
        }
        $resultset = new stdClass();
        $resultset->data = $result;
        $resultset->error = $error;
        $rstr = json_encode($resultset);
        echo $rstr;
        die();
    }


}
