<?php

CLASS DB{

    public $pdo;  

    public function __construct(){  

        // Create a new PDO instanace  
        try{  
            $this->pdo = new PDO(DBC_STRING, DB_USER, DB_PASS,array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET sql_mode=""'));
        }  
        // Catch any errors  
        catch(PDOException $e){  
            return error_and_die("Datenbankverbindung Fehlgeschlagen \n",$e);
        }  
    }
    #db insert
    public function insert($table, $data)
    {
        $setPart = array();
        $bindings = array();
        foreach ($data as $key => $value)
        {
            $setPart[] = "{$key}";
            $setPart2[] = ":{$key}";
            $bindings[":{$key}"] = $value;
        }

        $sql = "INSERT INTO {$table} (".implode(', ', $setPart).") VALUES (".implode(', ', $setPart2).")";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($bindings);

        return $this->pdo->lastInsertId();

    }

    #db update
    public function update ($table, $data, $id)
    {
        $setPart = array();
        $bindings = array();

        foreach ($data as $key => $value)
        {
            $setPart[] = "{$key} = :{$key}";
            $bindings[":{$key}"] = $value;
        }

        $bindings[":id"] = $id;


        $sql = "UPDATE {$table} SET ".implode(', ', $setPart)." WHERE id = :id";

        try{
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($bindings);
        }
        catch(Exception $e) {
            
            return var_dump($e->getMessage());
        }


    }

    #db delete
    public function delete ($table, $id)
    {
        $bindings = array();

        $bindings[":id"] = $id;

        
        $sql = "DELETE FROM {$table} WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($bindings);

        $error = $this->pdo->errorInfo();

        if ($error[0] != '0000') {
            return 'success';
        }else{
            return 'warning';
        }
    }

    #fetch all
    function fetch_all($table){

        $data = array();
        
        $query = $this->pdo->prepare("SELECT * FROM $table");
        $result = $query->execute(array());
        while($row = $query->fetch(PDO::FETCH_ASSOC)) {
            array_push($data, $row);
        }
    
        return $data;
    }

    #fetch id
    function fetch_id($table,$id){

        $data = array();

        $bindings[":id"] = $id;

        $query = $this->pdo->prepare("SELECT * FROM $table WHERE id = :id");
        $result = $query->execute($bindings);
        while($row = $query->fetch(PDO::FETCH_ASSOC)) {
            array_push($data, $row);
        }
        
        if (isset($data[0])){
            return $data[0];
        }else{
            return '';
        }
        
    }

    #free query
    public function query($what, $table, $where, $append =''){

        $setPart = array();
        $bindings = array();
    
        foreach ($where as $key => $value)
        {
            $setPart[] = "{$key} = :{$key}";
            $bindings[":{$key}"] = $value;
        }
    
   
        $statement = $this->pdo->prepare('SELECT '.$what.' FROM '.$table.' WHERE '.implode(' AND ', $setPart).' '.$append);
        $result = $statement->execute($where);
        $data = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $data;
    }
}


?>