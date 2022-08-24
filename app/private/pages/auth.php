<?php
sleep(3);
require_once('classes/user.php');


$DB = new USER;

$data = [
    'login' => 'error',
    'message' => 'Login fehlgeschlagen.',
    ];

if(!isset($_POST['password'])) {
    $data['message'] = 'Login fehlgeschlagen: Passwort war leer.';

    
    die(echo_json_array($data));
}

if(!isset($_POST['UserName'])) {
    $data['message'] = 'Login fehlgeschlagen: UserName war leer.';

    die(echo_json_array($data));
}

$UserName = $_POST['UserName'];
$passwort = $_POST['password'];

$statement = $DB->pdo->prepare("SELECT * FROM user WHERE UserName = :UserName Limit 1");
$result = $statement->execute(array('UserName' => $UserName));
$user = $statement->fetch();

if(!is_array($user)){
    $data['message'] = 'Benutzer nicht vorhanden';

    die(echo_json_array($data));
}
if (!array_key_exists("password",$user)){

    die(echo_json_array($data));
}

$upassword = trim($user['password']);
$password = trim(hash('sha256',$_POST['password']));

//Überprüfung des Passworts
if ($password==$upassword) {
    $_SESSION['id'] = $user['id'];
    $_SESSION['first_name'] = $user['first_name'];
    $_SESSION['second_name'] = $user['second_name'];
    $_SESSION['permissions'] = $user['permissions'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['csrf_token'] = uniqid('', true);

    $data = [
        'login' => 'success',
        'message' => 'Login erfolgreich.',
        ];
    
}else{
    $data['message'] = 'Login fehlgeschlagen: Passwort war falsch.';

}

echo_json_array($data);


?>