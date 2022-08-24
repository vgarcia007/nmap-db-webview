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
        $db->insert('hosts', $row);
        log_msg("Insert ".$row['ipv4']);
    }
}




$hosts_with_mac = $db->get_hosts_with_mac();


foreach($hosts_with_mac as $host_db){

    log_msg("nmap ".$host_db['ipv4']);
    shell_exec('nmap -sS -O -T5 -oX '.$host_db['ipv4'].'.xml '.$host_db['ipv4']);

    if (!file_exists($host_db['ipv4'].'.xml')) {
        log_msg("no valid xml file for host ".$host_db['ipv4']);
        continue;
    }

    $nmap_xml_data = simplexml_load_file($host_db['ipv4'].'.xml');

    $nmap_json_data = json_encode($nmap_xml_data);

    $nmap_array = json_decode($nmap_json_data, true);

    if(!isset($nmap_array['host'])){
        log_msg("no valid xml data for host ".$host_db['ipv4']);
        $db->update_host_by_ipv4(array('state' => 'down'), $host_db['ipv4']);
        
        if('AVM Audiovisuelles Marketing und Computersysteme GmbH' == $host_db['vendor']){
            log_msg("fritzbox vpn user ".$host_db['ipv4']);
            $db->update_host_by_ipv4(array('state' => 'vpn'), $host_db['ipv4']);
        }
        
        continue;
    }

    $host = $nmap_array['host'];

    $data =array();

    $data['state'] = 'down';
    if(isset($host['status']['@attributes']['state'])){
        $data['state'] = $host['status']['@attributes']['state'];
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

    $db->update_host_by_ipv4($data, $host_db['ipv4']);
    $db->delete_all_host_ports($host_db['id']);

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

            $db->insert('ports',$port);
            unset($port);

        }
    }
}

log_msg("--- DONE - WAITING FOR NEXT RUN ---");
?>