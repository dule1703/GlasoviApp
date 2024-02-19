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

    public function checkAndInsertEmail($data) {
        $userEmail = $data->sendData->email;
        $checkUser = $_SESSION["checkUser"];
        // Start output buffering to capture the echo from checkEmail() function      
        $this->newDBB->checkAndInsertEmail($userEmail, $checkUser);                  
    }
    
    public function existEmail(){
        $checkUser = $_SESSION["checkUser"];
        $this->newDBB->existEmail($checkUser);
    }

    public function emailVerification($data) {
        $userEmail = $data->sendData->email;
        $checkUser = $_SESSION["checkUser"];
        //check is mail exist
        // Start output buffering to capture the echo from checkEmail() function
        ob_start();
        $this->newDBB->checkUsernameEmail($checkUser, $userEmail);
        $jsonString = ob_get_clean(); // Capture the output and clean the buffer        

        $emailData = json_decode($jsonString, true);

        if ($emailData === null) {
            echo "Error decoding JSON: " . json_last_error_msg();
        } else {
            $status = $emailData['status'];
            if ($status === "existing") {
                $userId = $this->newDBB->getUserLoginId($checkUser);
                $verificatationCode = $this->newDBB->generateVerificationCode();
                if ($verificatationCode) {
                    ob_start();
                    $this->newDBB->storeVerificationToken($userId, $verificatationCode);
                    $jsonString2 = ob_get_clean();
                    $verificationMsg = json_decode($jsonString2, true);
                    $status = $verificationMsg["status"];
                    if ($status === "success") {
                        $this->newDBB->sendVerificationEmail($userEmail, $verificatationCode);
                        echo json_encode(["status" => "success", "message" => "Успешно креиран токен, проверите ваш мејл!"]);
                        exit();
                    }
                }
            }else {
                echo json_encode(["status" => "non exist", "message" => "Email је заузет или не постоји у бази!"]);
            }
            
        }
    }

    public function resetPassword($data) {

        $newPassword = $data->sendData->newPassword;
        // print_r($newPassword);
        $token = $data->sendData->token;
        // print_r($token);

        $isValidToken = $this->newDBB->validateVerificationToken($token);

        if ($isValidToken) {
            $username = $_SESSION["checkUser"];
            if ($this->newDBB->updatePassword($username, $newPassword)) {
                echo json_encode(["status" => "success", "message" => "Ваша шифра је успешно ресетована. Можете се улоговати са новом шифром."]);
            } else {
                echo json_encode(["status" => "fail", "message" => "Неуспешно ажурирано"]);
            }
        } else {
            echo json_encode(["status" => "error", "message" => "Невалидан токен"]);
        }
    }

}

if (!empty($data)) {
    $rpc = new ResetPassCtrl();

    switch ($data->action) {
        case 'existEmail':
            $rpc->existEmail();
            break;
        case 'insertEmail':
            $rpc->checkAndInsertEmail($data);
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
//$rpc->resetPassword($data);