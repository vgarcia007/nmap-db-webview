<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
date_default_timezone_set(getenv('TZ'));
setlocale (LC_TIME, "de_DE");
set_include_path('/var/www/private');
session_start();
require_once('config.php');
require_once 'core.functions.php';
require_once('classes/db.php');
require_once('classes/AltoRouter.php');
require_once('classes/user.php');
# ROUTER
#=========================================================
#

$router = new AltoRouter();
$router->addMatchTypes(array('char' => '(?:[^\/]*)'));
#$router->setBasePath('/');

/*
* setup
*/
$router->map( 'GET', '/setup', function() {
	require 'pages/setup.php';
});

/*
* home
*/
$router->map( 'GET', '/', function() {
    auth_required();
	require 'pages/home.php';
});


$router->map( 'GET', '/auth/login', function() {

	$nav_no_container = True;
	require 'pages/login.php';
});

$router->map( 'POST', '/auth/login', function() {
    
	require 'pages/auth.php';
});

$router->map( 'GET', '/auth/logout', function() {
    session_destroy();
	header("Location: /");

});

/*
=========================================================
* ROUTER RUN
=========================================================
*/

/*
* Match current request url
*/
$match = $router->match();

/*
* call closure or throw 404 status
*/
if( is_array($match) && is_callable( $match['target'] ) ) {
	call_user_func_array( $match['target'], $match['params'] ); 
} else {
    require 'pages/error.php';

}
?>