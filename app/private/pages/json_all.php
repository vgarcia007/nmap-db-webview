<?php
require_once 'classes/hosts.php';
$hosts = new HOSTS;
echo_json_array($hosts->view_known_hosts($order));
?>