<?php
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
require_once('classes/icons.php');
$icons = new ICONS;


$icons->assign_icon($_POST);
header("Location: /icon-assignment");
?>