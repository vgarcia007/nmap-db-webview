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


    public function view_known_hosts($order){

        $order_choice = array(
            'ipv4' => 'inet_aton(ipv4)',
            'reg-date' => 'reg_date DESC',
            'last-seen' => 'last_seen DESC',
        );

        if(!isset($order_choice[$order])){
            $order='ipv4';
        }

        $data = array();

        $query = $this->pdo->prepare("SELECT * FROM hosts WHERE state != '' ORDER BY " . $order_choice[$order]);
        $result = $query->execute(array());
        while($row = $query->fetch(PDO::FETCH_ASSOC)) {

            $row['icon'] = $this->match_icon($row);
            $row['state_css'] = $this->match_state_css_class($row['state']);
            if($row['last_seen'] == '0000-00-00 00:00:00'){
                $row['last_seen'] = $row['reg_date'];
            }
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

    public function scan_host($host_db){

        shell_exec('nmap -sS -O -T3 -oX '.$host_db['ipv4'].'.xml '.$host_db['ipv4']);

        if (!file_exists($host_db['ipv4'].'.xml')) {
            log_msg("no valid xml file for host ".$host_db['ipv4']);
            return;
        }
    
        $nmap_xml_data = simplexml_load_file($host_db['ipv4'].'.xml');
    
        $nmap_json_data = json_encode($nmap_xml_data);
    
        $nmap_array = json_decode($nmap_json_data, true);
    
        if(!isset($nmap_array['host'])){
            $this->update_host_by_ipv4(array('state' => 'down'), $host_db['ipv4']);
            
            if('AVM Audiovisuelles Marketing und Computersysteme GmbH' == $host_db['vendor']){
                $this->update_host_by_ipv4(array('state' => 'vpn'), $host_db['ipv4']);
            }
            
            return;
        }
    
        $host = $nmap_array['host'];
    
        $data =array();
    
        $data['state'] = 'down';
        if(isset($host['status']['@attributes']['state'])){
            $data['state'] = $host['status']['@attributes']['state'];
        }
        if($data['state'] == 'up'){
            $data['last_seen'] = date('Y-m-d H:i:s');
        }
      
        $data['vendor'] = '';
        if(isset($host['address'][1]['@attributes']['vendor'])){
            $data['vendor'] = $host['address'][1]['@attributes']['vendor'];
        }
        if($data['vendor'] == ''){
            unset($data['vendor']);
        }
    
        $data['hostname'] = '';
        if(isset($host['hostnames']['hostname']['@attributes']['name'])){
            $data['hostname'] = $host['hostnames']['hostname']['@attributes']['name'];
        }
        if($data['hostname'] == ''){
            unset($data['hostname']);
        }
    
        $data['os'] = '';
        if(isset($host['os']['osmatch']['@attributes']['name'])){
            $data['os'] = $host['os']['osmatch']['@attributes']['name'];
        }
        if($data['os'] == ''){
            unset($data['os']);
        }
    
        $data['uptime_seconds'] = '';
        if(isset($host['uptime']['@attributes']['seconds'])){
            $data['uptime_seconds'] = $host['uptime']['@attributes']['seconds'];
        }
    
        $this->update_host_by_ipv4($data, $host_db['ipv4']);
        unset($data);
        $this->delete_all_host_ports($host_db['id']);
    
        if(isset($host['ports']['port'])){
            foreach($host['ports']['port'] as $host_port){
    
                
                $port = array();
                $port['hosts_id']=$host_db['id'];
                $port['portid']='';
                
                if(isset($host_port['@attributes']['portid'])){
                    $port['portid'] = $host_port['@attributes']['portid'];
                }
                $port['protocol']='';
                if(isset($host_port['@attributes']['protocol'])){
                    $port['protocol'] = $host_port['@attributes']['protocol'];
                }
                $port['state']='';
                if(isset($host_port['state']['@attributes']['state'])){
                    $port['state'] = $host_port['state']['@attributes']['state'];
                }
                $port['service']='';
                if(isset($host_port['service']['@attributes']['name'])){
                    $port['service'] = $host_port['service']['@attributes']['name'];
                }
    
                $this->insert('ports',$port);
                unset($port);
    
            }
        }
    }
}
?>