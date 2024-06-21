<?php
class dbapi {
    public $conn;
    public $db_type = 'mysql';

    public function Connect($DBNAME, $DBHOST, $DBUSER, $DBPASS) {
        $this->conn = mysqli_connect($DBHOST, $DBUSER, $DBPASS);
        if (mysqli_select_db($this->conn, $DBNAME)) {
            return $this->conn;
        } else {
            die(mysqli_error($this->conn));
            return false;
        }

    }

    public function Execute($sql) {
        $rs = new RecordSet($sql, $this->db_type, $this->conn);
        return $rs;
    }

    public function Close() {
        // return @odbc_close($this->conn);
        return mysqli_close($this->conn);
    }

    public function Insert_ID() {
        return @mysqli_insert_id($this->conn);
    }

    public function Escape($txt) {
        if ($this->db_type == 'mysql') {
            return @mysqli_real_escape_string($this->conn, $txt);
        }
    }

}
class RecordSet {
    public $rs;
    public $db_type;
    public $conn;

    private $__recordCount;
    private $__records;

    public function __construct($sql, $db_type, $conn) {
        $this->db_type = $db_type;
        $this->conn = $conn;

        $rsx = mysqli_query($this->conn, $this->__sql_escape($sql));
        if (!$rsx) {
            $this->__queryError(@mysqli_error($this->conn), $sql);
        }

        // queryLog("Query End");
        $this->rs = $rsx;
    }

    public function __destruct() {
        $this->__recordCount = 0;
    }

    private function __queryError($err, $sql) {
        die($err . '<br/>' . $sql);
    }

    public function RecordCount() {
        if ($this->__recordCount > 0) {
            return $this->__recordCount;
        }
        $this->__recordCount = @mysqli_num_rows($this->rs);
        return $this->__recordCount;
    }

    public function FetchNextObject($class = '') {
        if (!$this->RecordCount() > 0) {
            return false;
        }
        if ($class != '') {
            return @mysqli_fetch_object($this->rs, $class);
        } else {
            return @mysqli_fetch_object($this->rs);
        }

    }

    public function GetArray() {
        return @mysqli_fetch_array($this->rs, MYSQLI_ASSOC);
    }

    private function __sql_escape($var) {
        return ($var);
        $temp = $var;
        if (stristr($temp, "mysql")) {
            $temp = "";
        } elseif (stristr($temp, "load_file")) {
            $temp = "";
        } elseif (stristr($temp, "union")) {
            $temp = "";
        }
        return ($temp);
    }

}
?>