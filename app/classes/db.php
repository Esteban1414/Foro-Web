<?php

namespace app\classes;

use \PDO;

class db
{
    private $db_host;
    private $db_name;
    private $db_user;
    private $db_pass;
    private $conne;

    protected $table;
    protected $fillable = [];
    protected $values = [];
    
    public $c = " * ";
    public $w = " 1 ";
    public $j = ""; 
    public $o = "";
    public $l = "";
    public $s = "";

    public function __construct($dbh = DB_HOST, $dbn = DB_NAME, $dbu = DB_USER, $dbp = DB_PASS)
    {
        $this->db_host = $dbh;
        $this->db_name = $dbn;
        $this->db_user = $dbu;
        $this->db_pass = $dbp;
    }

    protected function connect()
    {
        try {
            $this->conne = new PDO("mysql:host=" . $this->db_host . ";dbname=" . $this->db_name, $this->db_user, $this->db_pass);
            $this->conne->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conne->exec("SET CHARACTER SET utf8");
        } catch (\PDOException $err) {
            $code = $err->getCode();
            $message = $err->getMessage();
            echo "Exception: [Code $code] $message";
            die();
        }
        return $this->conne;
    }

    public function select($cc = [])
    {
        if (count($cc) > 0) {
            $this->c = implode(",", $cc);
            return $this;
        }
    }

    public function join($join = "", $on = "", $type="")
    {
        if ($join != "" && $on != "") {
            $this->j .= $type . ' JOIN ' . $join . ' ON ' . $on;
        }
        return $this;
    }

    public function where($ww = [])
    {
        $this->w = "";
        if (count($ww) > 0) {
            foreach ($ww as $where) {
                $this->w .= $where[0] . " LIKE '" . $where[1] . "' " . ' AND ';
            }
        }
        $this->w .= ' 1 ';
        $this->w = ' (' . $this->w . ') ';
        return $this;
    }

    public function orderBy($ob = [])
    {
        if (count($ob) > 0) {
            foreach ($ob as $orderBy) {

                $this->o .= $orderBy[0] . ' ' . $orderBy[1] . ',';
            }
            $this->o = ' ORDER BY ' . trim($this->o, ',');
        }
        return $this;
    }

    public function limit($l = "")
    {
        if ($l != "") {
            $this->l = ' LIMIT ' . $l;
        }

        return $this;
    }

    public function get()
    {
        $sql = "SELECT " . $this->c . " FROM " . str_replace(
            "app\\models\\","",get_class($this)) .
            ($this->j != "" ? " a " . $this->j : "") .
            " WHERE" .
            $this->w .
            $this->o .
            $this->l;

        $r = $this->table->query($sql);
        
        $result = [];
        while ($f = $r->fetch(PDO::FETCH_ASSOC)) {
            $result[] = $f;
        }

        return json_encode($result);
    }


    public function insert()
    {
        $sql = "INSERT INTO " . str_replace(
            "app\\models\\","",get_class($this)) . " (" . implode(",", $this->fillable) . ') values (' . 
            trim(str_replace("&", "?,", str_pad("",count($this->values), "&")), ",") . ');';
            $stmt = $this->table->prepare($sql);
            foreach ($this->values as $v => $value) {
                $stmt->bindValue(($v + 1), $value); 
            }            
            $stmt->execute();
            return $this->table->lastInsertId();
    }

    // public function insert()
    // {
    //     $sql = "INSERT INTO " . str_replace(
    //         "app\\models\\","",get_class($this)) . " (" .
    //         $this->c .
    //         ") VALUES(" .
    //         $this->v .
    //         ")";

    //     $r = $this->table->query($sql);

    //     while ($f = $r->fetch(PDO::FETCH_ASSOC)) {
    //         $result[] = $f;
    //     }

    //     return json_encode($result);
    // }
    // public function update()
    // {
    //     $sql = "UPDATE " . str_replace(
    //         "app\\models\\","",get_class($this)) .
    //         " SET " .
    //         $this->s .
    //         " WHERE" .
    //         $this->w ;

    //     $r = $this->table->query($sql);

    //     while ($f = $r->fetch(PDO::FETCH_ASSOC)) {
    //         $result[] = $f;
    //     }

    //     return json_encode($result);
    // }
}