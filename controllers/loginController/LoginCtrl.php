<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


require_once '../../dbbroker/DBBroker.php';
require_once '../../model/UserLogin.php';

   //$json = file_get_contents("php://input"); // json string
   // $data = json_decode($json); // php object
$data = json_decode(file_get_contents('php://input'));
class LoginCtrl{
    public function checkLogin($data){
        $username = $data->sendData->username;
        $password = $data->sendData->password;
        //Kreiranje objekta UserLog i setovanje podataka
        $userLog = new UserLogin();

        $userLog->setUsername($username);
        $userLog->setPassword($password);

        //Kreiranje DBBroker instance i provera
        $newDBB = new DBBroker();
        $newDBB->checkLogin($userLog->getUsername(), $userLog->getPassword());
    }
}

if(isset($data->sendData)){
    if($data->action === 'login'){
        $login = new LoginCtrl();
        $login->checkLogin($data);
    }  
}
    
    
    
   


