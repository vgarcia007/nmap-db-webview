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
    return '<small>up since '. date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s")) - $uptime_seconds).'</small><br>'; 
}


function match_icon($icons,$host){

    $icon_path = $icons['default']['path'];
    $icon = $icons['default']['file'];

    unset($icons['default']);

    foreach ($icons as $key => $icon_array){

        foreach ($icon_array as $search_string => $icon_file){
            if(str_contains($host[$key], $search_string)){
                $icon = $icon_file;
            }
        }
    }

    return $icon_path.$icon;
}

function match_state_css_class($host_state){
    $status_class = ' text-danger ';
    if($host_state == 'up'){
        $status_class = ' text-success ';
    }
    if($host_state == 'vpn'){
        $status_class = ' text-warning ';
    }

    return $status_class;
}
?>