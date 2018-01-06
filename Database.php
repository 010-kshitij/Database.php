<?php 
// Database Class
class Database {
	private static $host = 'localhost';

    private static $link;

    private static $user = 'greewdyw_sellersummary';
    //private static $user = 'root';
   private static $pass = '123qwe';
   //private static $pass = '';
    private static $database = 'greewdyw_sellersummary';

    protected $_tableName = '';

	public function __construct() {
	    if(!isset(self::$link)) {
            self::$link = mysqli_connect(self::$host, self::$user, self::$pass, self::$database) or die("Error");
            mysqli_set_charset(self::$link , "utf8");
        }
	}
	
	public function __destruct() {
//        mysqli_close(self::$link);
	}
	
	public function query($query) {
	    try {
            return mysqli_query(self::$link, $query);
        }
        catch (Exception $exception) {
            die(mysqli_error(self::$link));
        }
	}

	public function error() {
	    return mysqli_error(self::$link);
    }
	
	public function fetch($result) {
		return mysqli_fetch_object($result);
	}
	
	public function fetchAll($result) {
		$record = array();
		if($result != false) {
			if(mysqli_num_rows($result) > 0) {
				while($row = mysqli_fetch_object($result)) {
					$record[] = $row;
				}
			}
		}
		return $record;
	}
	
	public function lastInsertId() {
		return mysqli_insert_id(self::$link);
	}
	
	public function select($table = "", $columns = "*", $condition = "1=1", $extra="") {
        if($table != '') {
            $this->_tableName = $table;
        }
		$result = $this->query("SELECT ".$columns." FROM ".$this->_tableName." WHERE ".$condition." ".$extra);
		return $this->fetchAll($result);
	}

	public function insert($table = "", $data = array()) {
	    if($table != '') {
	        $this->_tableName = $table;
        }
	    $column = '';
	    $values = '';
	    $comma = 0;
	    foreach($data as $key => $value) {
            if($comma == 0) {
                $column .= "`".$key."`";
                $values .= "'".$value."'";
                $comma = 1;
            }
            else {
                $column .= ", `".$key."`";
                $values .= ", "."'".$value."'";
            }

        }
        $query = "INSERT INTO ".$this->_tableName."(".$column.") VALUES(".$values.")";
	    return $this->query($query);
    }

    public function update($table = "", $data = array(), $where = "1=1") {
	    if($table != '') {
	        $this->_tableName = $table;
        }
	    $command = "";
	    $comma = 0;
	    foreach($data as $key => $value) {
            $column = "`".$key."`";
            $values = "'".$value."'";
            if($comma == 0) {
                $command .= $column.' = '.$values;
                $comma = 1;
            }
            else {
                $command .= ", ".$column.' = '.$values;
            }

        }
        $query = "UPDATE ".$this->_tableName." SET ".$command." WHERE ".$where;
        return $this->query($query);
    }
	
}

