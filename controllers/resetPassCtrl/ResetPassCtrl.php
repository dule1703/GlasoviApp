<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../../dbbroker/DBBroker.php';

$data = json_decode(file_get_contents('php://input'));

class ResetPassCtrl {

    private $newDBB;

    public function __construct() {
        $this->newDBB = new DBBroker();
    }

    public function checkUsername($data) {
        $username = $data->sendData->username;
        $this->newDBB->checkUser($username);
        $_SESSION["checkUser"] = $username;
    }

    public function checkEmailByUsername($data) {
        $userEmail = $data->sendData->email;
        $checkUser = $_SESSION["checkUser"];
//        $userEmail = 'contact@ddwebapps.com';
//        $checkUser = 'Superadmin';

        $this->newDBB->checkEmailByUsername($userEmail, $checkUser);
    }
    
    public function emailVerification($data) {
        $userEmail = $data->sendData->email;
        $checkUser = $_SESSION["checkUser"];

        //Proverava da li postoji email koji odgovara ulogovanom user-u
        // Start output buffering to capture the echo from checkEmail() function
        ob_start();
        $this->checkEmailByUsername($data);
        $jsonString = ob_get_clean(); // Capture the output and clean the buffer        

        $emailData = json_decode($jsonString, true);

        if ($emailData === null) {
            echo "Error decoding JSON: " . json_last_error_msg();
        } else {
            $status = $emailData['status'];
            
            if ($status === "exist") {
                $userLoginId = $this->newDBB->getUserLoginId($checkUser);             
                $verificationCode = $this->newDBB->generateVerificationCode();
               
                if ($verificationCode) {
                    ob_start();
                    $this->newDBB->storeVerificationToken($userLoginId, $verificationCode);

                    $jsonString2 = ob_get_clean();
                    $verificationMsg = json_decode($jsonString2, true);
                    if ($verificationMsg !== null) {
                        $status = $verificationMsg["status"];
                        if ($status === "success") {
                            $this->newDBB->sendVerificationEmail($userEmail, $checkUser, $verificationCode);
                            echo json_encode(["status" => "success", "message" => "Uspešno uskladišten token, proverite vaš email!"]);
                            exit();
                        } else {
                            echo json_encode(["status" => "fail", "message" => "Neuspešno uskladišten token!"]);
                        }
                    } else {
                        // Handle the case when $verificationMsg is null
                        echo json_encode(["status" => "fail", "message" => "Verifikaciona poruka je 'null'"]);
                    }                    
                }
            } else {
                echo json_encode(["status" => "non exist", "message" => "Pogrešan ili nepostojeći mejl!"]);
            }
        }
    }


    public function resetPassword($data) {

        $newPassword = $data->sendData->newPassword;
        // print_r($newPassword);
        $token = $data->sendData->token;
        // print_r($token);

        $isValidToken = $this->newDBB->validateVerificationToken($token);
        $username = $_SESSION["checkUser"];
        if ($isValidToken && $username != null) {                     
            if ($this->newDBB->updatePassword($username, $newPassword)) {
                echo json_encode(["status" => "success", "message" => "Vasa šifra je uspešno resetovana."]);
            } else {
                echo json_encode(["status" => "fail", "message" => "Neuspešno ažurirano"]);
            }
        } else {
            echo json_encode(["status" => "error", "message" => "Nevalidan token ili korisničko ime"]);
        }
    }

}

if (!empty($data)) {
    $rpc = new ResetPassCtrl();

    switch ($data->action) {
        case 'checkEmailByUsername':
            $rpc->checkEmailByUsername($data);
            break;
        case 'checkUsername':
            $rpc->checkUsername($data);
            break;
        case 'submitEmail':
            $rpc->emailVerification($data);
            break;
        case 'newPassword':
            $rpc->resetPassword($data);
            break;
        default:
            break;
    }
}
//$rpc = new ResetPassCtrl();
//$pomVC = $rpc->newDBB->generateVerificationCode();
//$rpc->newDBB->storeVerificationToken(1, $pomVC);
//$rpc->emailVerification();
//$rpc->resetPassword($data);