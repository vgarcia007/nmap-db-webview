<?php 

require_once('classes/db.php');


class USER extends DB{


    public function user_info($UserName){

        $data = array();

        if(!$this->check_if_user_exists_by_UserName($UserName)){
            $data['error'] = 'not registered';
            return $data;
        }

        

        $sql="
        SELECT 
            id,
            first_name as Vorname,
            second_name as Nachname,
            permissions,
            email,
            UserName,
            reg_date 
        FROM 
            user 
        WHERE 
            UserName = :UserName
        LIMIT 1;
        ";

        $bindings[":UserName"] = $UserName;
        
        $query = $this->pdo->prepare($sql);
        $result = $query->execute($bindings);
        while($row = $query->fetch(PDO::FETCH_ASSOC)) {
            array_push($data, $row);
        }
    
        return $data[0];
    }


    private function check_if_user_exists_by_UserName($UserName){

        $data = array();

        $bindings[":UserName"] = $UserName;

        $query = $this->pdo->prepare("SELECT UserName FROM user WHERE UserName = :UserName");
        $result = $query->execute($bindings);
        while($row = $query->fetch(PDO::FETCH_ASSOC)) {
            array_push($data, $row);
        }
        
        if (isset($data[0])){
            return true;
        }else{
            return false;
        }
    }

}
?>