<?php
date_default_timezone_set(getenv('TZ'));
setlocale (LC_TIME, "de_DE");
set_include_path('/var/www/private');

require_once 'config.php';
require_once 'core.functions.php';

require_once 'classes/hosts.php';
$db = new HOSTS;

$hosts = array();

log_msg("--- START ---");

$files = glob('/var/www/html/*.xml'); 
foreach($files as $file){
  if(is_file($file)) {
    unlink($file);
  }
}


log_msg("nmap scan for hosts");

shell_exec('nmap -sn -T5 -oX hosts.xml '.NMAP_NET);


$xmldata = simplexml_load_file('hosts.xml');
$jsondata = json_encode($xmldata);
$someArray = json_decode($jsondata, true);

foreach ($someArray['host'] as $host) {
    $row =array();

    $row['state'] = 'down';
    if(isset($host['status']['@attributes']['state'])){
        $row['state'] = $host['status']['@attributes']['state'];
    }

    if($row['state'] == 'up'){
        $row['last_seen'] = date('Y-m-d H:i:s');
    }

    $row['ipv4'] = '';
    if(isset($host['address'][0]['@attributes']['addr'])){
        $row['ipv4'] = $host['address'][0]['@attributes']['addr'];
    }
    if(isset($host['address']['@attributes']['addr'])){
        $row['ipv4'] = $host['address']['@attributes']['addr'];
    }

    $row['mac'] = '';
    if(isset($host['address'][1]['@attributes']['addr'])){
        $row['mac'] = $host['address'][1]['@attributes']['addr'];
    }

    if($row['mac'] == ''){
        unset($row['mac']);
    }

    $row['vendor'] = '';
    if(isset($host['address'][1]['@attributes']['vendor'])){
        $row['vendor'] = $host['address'][1]['@attributes']['vendor'];
    }
    if($row['vendor'] == ''){
        unset($row['vendor']);
    }

    $row['hostname'] = '';
    if(isset($host['hostnames']['hostname']['@attributes']['name'])){
        $row['hostname'] = $host['hostnames']['hostname']['@attributes']['name'];
    }
    if($row['hostname'] == ''){
        unset($row['hostname']);
    }


    if($db->check_if_host_exists_by_ipv4($row['ipv4'])){
        $data['ipv4'] = $row['ipv4'];
        
        $db->update_host_by_ipv4($row, $row['ipv4']);
        log_msg("Update ".$row['ipv4']);
    }else{
        $row['id'] = $db->insert('hosts', $row);
        log_msg("Insert ".$row['ipv4']);

        //new host found
        //send notification if set up

        //extended scan for new host
        $db->scan_host($row);
    }
    unset($row);
}




$hosts_with_mac = $db->get_hosts_with_mac();


foreach($hosts_with_mac as $host_db){

    log_msg("nmap ".$host_db['ipv4']);
    $db->scan_host($host_db);
}




log_msg("--- DONE - WAITING FOR NEXT RUN ---");
?>
