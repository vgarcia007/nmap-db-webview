<?php 

require_once('classes/db.php');


class HOSTS extends DB{


    private function check_if_host_exists_by_mac($mac){

        $data = array();

        $bindings[":mac"] = $mac;

        $query = $this->pdo->prepare("SELECT mac FROM user WHERE mac = :mac");
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

    public function check_if_host_exists_by_ipv4($ipv4){

        $data = array();

        $bindings[":ipv4"] = $ipv4;

        $query = $this->pdo->prepare("SELECT ipv4 FROM hosts WHERE ipv4 = :ipv4");
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


    public function update_host_by_ipv4($data, $ipv4){

        $setPart = array();
        $bindings = array();

        foreach ($data as $key => $value)
        {
            $setPart[] = "{$key} = :{$key}";
            $bindings[":{$key}"] = $value;
        }

        $bindings[":ipv4"] = $ipv4;


        $sql = "UPDATE hosts SET ".implode(', ', $setPart)." WHERE ipv4 = :ipv4";

        try{
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($bindings);
        }
        catch(Exception $e) {
            
            return var_dump($e->getMessage());
        }
    }

    public function get_hosts_with_mac(){

        $data = array();
        
        $query = $this->pdo->prepare("SELECT * FROM hosts WHERE mac != '00:00:00:00:00:00'");
        $result = $query->execute(array());
        while($row = $query->fetch(PDO::FETCH_ASSOC)) {
            array_push($data, $row);
        }
    
        return $data;
    }

    public function delete_all_host_ports($hosts_id){
       
        $bindings = array();

        $bindings[":hosts_id"] = $hosts_id;

        
        $sql = "DELETE FROM ports WHERE hosts_id = :hosts_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($bindings);

        $error = $this->pdo->errorInfo();

        if ($error[0] != '0000') {
            return 'success';
        }else{
            return 'warning';
        }
    }


    public function view_known_hosts(){

        $data = array();
        
        $query = $this->pdo->prepare("SELECT * FROM hosts WHERE state != '' ORDER BY inet_aton(ipv4)");
        $result = $query->execute(array());
        while($row = $query->fetch(PDO::FETCH_ASSOC)) {

            $row['icon'] = $this->match_icon($row);
            $row['state_css'] = $this->match_state_css_class($row['state']);
            array_push($data, $row);
        }
    
        return $data;
    }

    public function view_host_ports($hosts_id){
        $data = array();
        $bindings = array();

        $bindings[":hosts_id"] = $hosts_id;

        
        $sql = "SELECT * FROM ports WHERE hosts_id = :hosts_id";
        $query = $this->pdo->prepare($sql);
        $query->execute($bindings);
        while($row = $query->fetch(PDO::FETCH_ASSOC)) {
            array_push($data, $row);
        }
    
        return $data;
    }

    public function match_icon($host){

        $icon_path = '/img/logos/';
        $icon = 'unknown.png';
    
        $icons = $this->fetch_all('icons');
    
        foreach ($icons as $key => $row){
    

            if(str_contains($host[$row['match_in']], $row['search_string'])){
                $icon = $row['icon_file'];
   
            }
        }
    
        return $icon_path.$icon;
    }
    
    public function match_state_css_class($host_state){
        $status_class = ' text-danger ';
        if($host_state == 'up'){
            $status_class = ' text-success ';
        }
        if($host_state == 'vpn'){
            $status_class = ' text-warning ';
        }
    
        return $status_class;
    }
}
?>