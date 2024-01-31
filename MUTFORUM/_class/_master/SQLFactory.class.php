<?php

class SQLFactoryV2
{
    private $PDO;
    private $tables;
    private $routine_fnc;
    private $ini;
    public function __construct($PDO = null,$configPath="include/config.ini")
    {
        $this->ini = easyFrameWork::getParams("BDD",$configPath);
        $this->PDO = $PDO ?? new PDO('mysql:host=' . $this->ini["host"] . ';dbname=' . $this->ini["bdd"], $this->ini["user"], $this->ini["mdp"]);
        $this->tables = [];
        $t = $this->getTableSchema();
        $this->tables = array_reduce($t, function ($carry, $item) {
            // echo count($carry);
            $carry[$item["TABLE_NAME"]] = [];
            $carry[$item["TABLE_NAME"]]["PRI"] = $this->getID($item["TABLE_NAME"]);
            return $carry;
        }, []);
        $r = $this->getStorageFnc();
        //   var_dump($r[0]);
        $this->routine_fnc = array_reduce($r, function ($carry, $item) {
            $fncName = $item["ROUTINE_NAME"]??$item["routine_name"];
            $carry[$fncName]["type"] = $item["DATA_TYPE"]??$item["data_type"];

            $carry[$fncName]["exec"] = function ($args) use ($fncName) {
                $argsString = [];
                foreach ($args as $el) {
                    $argsString[] = $el["value"];
                }
                return $this->execQuery("SELECT `$fncName`(" . implode(",", $argsString) . ") AS `$fncName`;");
            };
            return $carry;
        }, []);
        //   var_dump($this->tables);
    }
    private function getID($table)
    {
        return array_reduce($this->execQuery("SELECT COLUMN_NAME
        FROM INFORMATION_SCHEMA.COLUMNS
        WHERE TABLE_SCHEMA = '" . $this->ini["bdd"] . "'
          AND TABLE_NAME = '$table'
          AND COLUMN_KEY = 'PRI'"), function ($carry, $item) {
            $carry[] = $item["COLUMN_NAME"];
            return $carry;
        }, []);
    }
    public function get($table,$filter){
        if (key_exists($table, $this->tables)) {
            $sth = $this->PDO->query("SELECT * FROM $table WHERE 1=1 AND $filter");
            $arr = $sth->fetchAll(PDO::FETCH_ASSOC);
            $sth->closeCursor();
            return $arr;
        } else {
            throw new Exception("$table doesn't exist in the current schema");
        }
    }
    public function getColumns($table){
        $i=0;
        return array_reduce($this->execQuery("SELECT COLUMN_NAME,EXTRA,COLUMN_KEY,DATA_TYPE,CHARACTER_MAXIMUM_LENGTH
        FROM INFORMATION_SCHEMA.COLUMNS
        WHERE TABLE_SCHEMA = '" . $this->ini["bdd"] . "'
          AND TABLE_NAME = '$table'"), function ($carry, $item) use(&$i,$table){
            $carry[$i]["NAME"] = $item["COLUMN_NAME"];
            $carry[$i]["PRIMARY"]=$item["COLUMN_KEY"];
            if($item["COLUMN_KEY"]=="MUL"){
                $t=$this->execQuery("SELECT TABLE_NAME
                FROM INFORMATION_SCHEMA.COLUMNS
                WHERE COLUMN_NAME LIKE '%".$carry[$i]["NAME"]."%'
                AND TABLE_NAME <>'$table';");
                $carry[$i]["TABLE_ASSOC"]=$t[0]["TABLE_NAME"];
            }
            $carry[$i]["TYPE"] = $item["DATA_TYPE"];
            if($item["COLUMN_KEY"]=="PRI")
            $carry[$i]["AUTO_INCR"] = ($item["EXTRA"]=="auto_increment")?"YES":"NO";
            $carry[$i]["LENGHT"]=($item["DATA_TYPE"]=="varchar")?$item["CHARACTER_MAXIMUM_LENGTH"]:"";
            $i++;
            return $carry;
        }, []);
    }
    public function getTable($table)
    {

        if (key_exists($table, $this->tables)) {
            $sth = $this->PDO->query("SELECT * FROM $table");
            $arr = $sth->fetchAll(PDO::FETCH_ASSOC);
            $sth->closeCursor();
            return $arr;
        } else {
            throw new Exception("$table doesn't exist in the current schema");
        }
    }
    public function addItem($item, $table)
    {
            $query = "INSERT INTO $table (#K#) VALUES (#VALUES#)";
            $k = [];
            $v = [];
            foreach ($item as $key => $values) {
                $k[] = $key;

                $v[] = $values != null ? "\"$values\"" : "null";
            }
            $query = str_replace("#K#", implode(",", $k), $query);
            $query = str_replace("#VALUES#", implode(",", $v), $query);
            $return =$this->execQuery($query);
            
            return $return;
        
    }
    public function deleteItem($id, $table)
    {
        $f = $this->tables[$table]["PRI"][0];
            return $this->execQuery("DELETE FROM $table WHERE $f=$id");
    
    }
    public function updateItem($item, $table)
    {
        $u = [];
        $f = $this->tables[$table]["PRI"][0];
        foreach ($item as $key => $value) {
            if ($key != $f)
                $u[] = "$key=\"".($value)."\"";
        }

        $id = $item[$f];
        $this->execQuery("UPDATE $table SET " . implode(",", $u) . " WHERE $f=$id");
    }
    public function execQuery($query)
    {
       
        $sth = $this->PDO->query($query);
        try{
        $arr = $sth->fetchAll(PDO::FETCH_ASSOC);
        $sth->closeCursor();
      // var_dump($arr);
        return $arr;
        }catch(PDOException $e){
            echo $e->getMessage();
        }
        
    }
    public function execFnc($fncName, $args)
    {
        //  echo "SELECT `fncName`(".$args[0]["value"].") AS `$fncName`;";
        return $this->execQuery("SELECT `$fncName`(" . $args[0]["value"] . ") AS `$fncName`;");
    }
    /**
     * @return array
     */
    public function getTableSchema()
    {
        return $this->execQuery("SELECT TABLE_NAME 
            FROM INFORMATION_SCHEMA.TABLES
            WHERE TABLE_TYPE = 'BASE TABLE' AND TABLE_SCHEMA='" . $this->ini["bdd"] . "' ");
    }
    public function getTableArray()
    {
        return $this->tables;
    }
    public function getRoutineArray()
    {
        return $this->routine_fnc;
    }
    public function getStorageFnc():mixed
    {
        return $this->execQuery("SELECT routine_schema as \"Database\", routine_name, data_type FROM information_schema.routines WHERE routine_type = 'FUNCTION' AND routine_schema = \"" . $this->ini["bdd"] . "\" ORDER BY routine_schema ASC, routine_name ASC");
    }
}
?>