<?php

require_once '../../dbbroker/DBBroker.php';


$data = json_decode(file_get_contents('php://input'));

class FormCtrl {

    private $newDBB;

    public function __construct() {
        $this->newDBB = new DBBroker();
    }

    public function checkEmail($data) {
        $email = $data->sendData->email;
        $this->newDBB->checkEmail($email);
    }

    public function checkJMBG($data) {
        $jmbg = $data->sendData->jmbg;
        $this->newDBB->checkJMBG($jmbg);
    }

    public function checkTel($data) {
        $tel = $data->sendData->telefon;
        $this->newDBB->checkTelefon($tel);
    }

    public function ucitajOpstine() {
        $this->newDBB->loadTowns();
    }

    public function checkNosioce($data) {
        $opstina = $data->sendData->opstina;
        $this->newDBB->checkNosioce($opstina);
    }

    public function ubaciGlasaca($data) {
        $this->newDBB->insertGlasac($data->sendData->ime, $data->sendData->prezime, $data->sendData->jmbg, $data->sendData->adresa,
                $data->sendData->telefon, $data->sendData->biraliste, $data->sendData->email, $data->sendData->datum_rodj,
                $data->sendData->nosilac_glasova, $data->sendData->ime_nosioca_glasova, $data->sendData->opstina);
    }

}

if (!empty($data)) {
    $form = new FormCtrl();

    switch ($data->action) {
        case 'checkEmail':
            $form->checkEmail($data);
            break;
        case 'checkJMBG':
            $form->checkJMBG($data);
            break;
        case 'checkTel':
            $form->checkTel($data);
            break;
        case 'ucitajOpstine':
            $form->ucitajOpstine();
            break;
        case 'checkNosioce':
            $form->checkNosioce($data);
            break;
        case 'insertGlasac':
            $form->ubaciGlasaca($data);
        default:
            echo json_encode(["status" => "error", "message" => "Грешка!"]);
            break;
    }
}
    
 
    
   


