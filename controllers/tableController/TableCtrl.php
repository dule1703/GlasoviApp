<?php

  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL); 


require_once '../../dbbroker/DBBroker.php';

$data = json_decode(file_get_contents('php://input'));

class TableCtrl {

    private $newDBB;

    public function __construct() {
        $this->newDBB = new DBBroker();
    }

    public function loadTable() {
        $this->newDBB->loadTable();
    }

    public function getGlasacById($data) {
        $id = $data->sendData->id;
        $this->newDBB->getGlasacById($id);
    }

    public function getImeNosioca($data) {
        $poverenistvo = $data->sendData->poverenistvo;
        $this->newDBB->getImeNosioca($poverenistvo);
    }
    
    public function getOpstineEdit(){
        $this->newDBB->loadTowns();
    }
    
    public function updateGlasaca($data){
        $id = $data->sendData->id;
        $ime = $data->sendData->ime;
        $prezime = $data->sendData->prezime;
        $jmbg = $data->sendData->jmbg;
        $adresa = $data->sendData->adresa;
        $telefon = $data->sendData->telefon;
        $biraliste = $data->sendData->biraliste;
        $email = $data->sendData->email;
        $datum_rodj = $data->sendData->datum_rodj;
        $nosilac_glasova = $data->sendData->nosilac_glasova;
        switch ($nosilac_glasova){
            case 'Ја сам носилац':
                $nosilac_glasova_id = 1;
                break;
            case 'Да, имам носиоца':
                $nosilac_glasova_id = 2;
                break;
            case 'Не, немам носиоца':
                $nosilac_glasova_id = 3;
                break;
            default:
                break;
            
        }
        $nosilac_glasova_ime = $data->sendData->ime_nosioca_glasova;
        $opstina = $data->sendData->opstina;
        
        $this->newDBB->updateGlasac($id, $ime, $prezime, $jmbg, $adresa, $telefon, $biraliste, $email, $datum_rodj, $nosilac_glasova_id, $nosilac_glasova_ime, $opstina);
        
    }
    
    public function deleteGlasac($data){
        $id = $data->sendData->id;
        $this->newDBB->deleteGlasac($id);
    }

}

if (!empty($data)) {

    $form = new TableCtrl();
    switch ($data->action) {
        case 'loadTable':
            $form->loadTable();
            break;
        case 'getGlasacById':
            $form->getGlasacById($data);
            break;
        case 'getImeNosioca':
            $form->getImeNosioca($data);
            break;
        case 'ucitajOpstineEdit':
            $form->getOpstineEdit();
            break;
        case 'updateGlasac':
            $form->updateGlasaca($data);
            break;
        case 'deleteGlasac':
            $form->deleteGlasac($data);
            break;
        default:
            echo json_encode(["status" => "error", "message" => "Грешка!"]);
            break;
    }
}
    
    
    
   


