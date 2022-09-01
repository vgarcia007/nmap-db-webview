<?php
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
require_once('classes/icons.php');
$icons = new ICONS;


print_r( $icons->upload_icon_picture($_FILES["upload-icon"]["name"],$_FILES["upload-icon"]["tmp_name"]) );
header("Location: /icon-upload");
?>