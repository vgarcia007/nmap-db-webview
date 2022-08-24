<?php
header('Content-Type: application/json');
ini_set ('display_errors', 1);  
ini_set ('display_startup_errors', 1);  
error_reporting (E_ALL); 
require_once('classes/db.php');

include('tables.php');

function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!$%&1234567890';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
  }

$retrun=array();

# Scheiben, Serien, Treffer, Version
$DB = new DB;

foreach ($TABLES as $key => $TABLE) {
  $sth = $DB->pdo->prepare($TABLE);
  $sth->execute();
  $retrun['errinfo'][$key] =$sth->errorInfo();
    
}

//check if setup runs the first time

$retrun['firstrun']=true;

$app_settings = $DB->fetch_id('app',1);

if ( isset($app_settings['firstrun']) ) {
  if($app_settings['firstrun'] == 'completed'){
    $retrun['firstrun']=false;
  }
}

if ($retrun['firstrun'] == true) {
  $retrun['YOUR_LOGIN_IS']='admin';
  $retrun['YOUR_PASSWORD_IS']=generateRandomString();
	$pwd=hash('sha256',$retrun['YOUR_PASSWORD_IS']);
	$data = [
        'first_name' => 'admin',
        'second_name' => '',
        'email' => '',
        'password' => $pwd,
        'permissions' => 'user, editor, admin, superadmin',
        'UserName' => $retrun['YOUR_LOGIN_IS']

        ];
  
  $DB->insert('user',$data);
  unset($data);

  $data = [
    'firstrun' => 'completed'
    ];

  $DB->insert('app',$data);
  unset($data);

}



print_r(json_encode($retrun, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
?>