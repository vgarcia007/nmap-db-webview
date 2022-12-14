<?php
function log_msg($msg){

    echo date('Y-m-d H:i:s') . " | " . $msg . "\n";
}

function send_error($number,$redirect='none'){
    header('HTTP/1.0 '.$number.' Forbidden');
    if ($redirect !== 'none') {
        header("Location: /".$redirect);
    }
    die();

}

function error_and_die($message,$e){
    echo 'Datenbankverbindung Fehlgeschlagen '."\n";
    die();
}


function echo_json_array($data){
    header('Content-Type: application/json');
    print_r(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

}

function api_key_required(){

    if(!isset($_POST)){
        header('HTTP/1.0 403 Forbidden');
        die();
    }
    if (array_key_exists('api-key', $_POST)) {
        
        if ($_POST['api-key'] == '04c8209e-8906-11ed-a1eb-0242ac120002') {
            #logger('permission granted');
        }else{
            header('HTTP/1.0 403 Forbidden');
        die();
        }
    }else {
        header('HTTP/1.0 403 Forbidden');
        die();
    }
}

function auth_required($role='user'){

    if (array_key_exists('id', $_SESSION)) {
        
        if (strpos($_SESSION['permissions'], $role) !== false) {
            #logger('permission granted');
        }else{
            send_error('403','auth/login');
        }
    }else {
        send_error('403','auth/login');
    }
}

function has_permisson($role='user'){
        
    if (strpos($_SESSION['permissions'], $role) !== false) {
        return true;
    }
    return false;
    
}

function up_since($uptime_seconds){
    if($uptime_seconds == 0){
        return '';
    }
    return '<small>up since '. date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s")) - $uptime_seconds).'</small>'; 
}



?>