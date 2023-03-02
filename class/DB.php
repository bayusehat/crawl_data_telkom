<?php

class DB {

    // private $dbname = 'postgres';
    // private $host = 'localhost';
    // private $username = 'postgres';
    // private $password = 'telkom135';

    private $dbname = 'ccoper';
    private $host = '10.60.170.169';
    private $username = 'ccoper';
    private $password = 'ccoper2019';

    private $count = 0;
    private $columnName = "*";
    private $orderBy = "";
    private $resource;

    private static $instance = null;
    private $pdo;

    private function __construct() {
        try {
            $this->pdo = new PDO('pgsql:host='.$this->host.';dbname='.$this->dbname, $this->username, $this->password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch (PDOException $e) {
            die("Koneksi / Query bermasalah: ".$e->getMessage()." (".$e->getCode().")");
        }
    }

    public static function getInstance() {
        if (!isset(self::$instance)) {
            self::$instance = new DB();
        }
        return self::$instance;
    }

    public function runQuery($query, $bindValue = []) {
        try {
            // echo $query;
            $stmt = $this->pdo->prepare($query);
            $stmt->execute($bindValue);
        }
        catch (PDOException $e) {
            if (!empty($bindValue)) {
                var_dump($bindValue);
            }
            die("Koneksi / Query bermasalah: ".$e->getMessage()." (".$e->getCode().")");
        }
        return $stmt;
    }

    public function prepareQuery($query) {
        return $this->resource = $this->pdo->prepare($query);
    }

    public function executeQuery($bindValue = []) {
        try {
            $this->resource->execute($bindValue);
        }
        catch (PDOException $e) {
            die("Koneksi / Query bermasalah: ".$e->getMessage()." (".$e->getCode().")");
        }
    }

    public function getQuery($query, $bindValue = []) {
        return $this->runQuery($query, $bindValue)->fetchAll(PDO::FETCH_OBJ);
    }

    public function get($tableName, $condition = "", $bindValue = []) {
        $query = "SELECT {$this->columnName} FROM {$tableName} {$condition} {$this->orderBy}";
        $this->columnName = "*";
        $this->orderBy = "";
        return $this->getQuery($query, $bindValue);
    }

    public function select($columnName) {
        $this->columnName = $columnName;
        return $this;
    }

    public function orderBy($columnName, $sortType = 'ASC') {
        $this->orderBy = "ORDER BY {$columnName} {$sortType}";
        return $this;
    }

    public function getWhere($tableName, $condition) {
        $queryCondition = "WHERE {$condition[0]} {$condition[1]} :{$condition[0]}";
        return $this->get($tableName, $queryCondition, [$condition[0] => $condition[2]]);
    }

    public function getWhereOnce($tableName, $condition) {
        $result = $this->getWhere($tableName, $condition);
        if (!empty($result)) {
            return $result[0];
        } else {
            return false;
        }
    }

    public function getMax($tableName, $columnName) {
        
        $query = "SELECT MAX({$columnName}) max_value FROM {$tableName}";

        $result = $this->getQuery($query);
        return $result[0]->max_value;
    }

    function update($tableName, $data, $condition) {

        $query = "UPDATE {$tableName} SET ";
        $bv = [];
        foreach ($data as $val) {
            if ($val[0] === 'date') {
                $query .= $val[1] . " = TO_DATE(:" . $val[1] . ", '" . $val[3] . "'), ";
            } else {
                $query .= $val[1] . " = :" . $val[1] . ", ";
            }
    
            $bv[$val[1]] = $val[2];
        }
        $query = substr($query, 0, -2);
        $query .= " WHERE {$condition[0]} {$condition[1]} :{$condition[0]}";
    
        $bv[$condition[0]] = $condition[2];
        // echo "query update" . $query . "<br>";
        // echo "bv update <br>";
        // var_dump($bv);
        return $this->runQuery($query, $bv);
    }

    public function getWhereOnceQuery($query, $bindValue = []) {
        
        $result = $this->getQuery($query, $bindValue);

        if (!empty($result)) {
            return $result[0];
        } else {
            return false;
        }
    }
}