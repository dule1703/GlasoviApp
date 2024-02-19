<?php

/* ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL); */


require_once '../../dbbroker/DBBroker.php';

$data = json_decode(file_get_contents('php://input'));

class AdminCtrl {

    private $newDBB;

    public function __construct() {
        $this->newDBB = new DBBroker();
    }

    public function ucitajOkruge() {
        $this->newDBB->loadOkrug();
    }

    public function poverenistvaPoOkrugu($data) {
        $okrug = $data->sendData->okrug;
        $this->newDBB->poverenistvaByOkrug($okrug);
    }

    public function kreirajPoverenika($data) {
        $ime = $data->sendData->ime;
        $prezime = $data->sendData->prezime;
        $jmbg = $data->sendData->jmbg;
        $adresa = $data->sendData->adresa;
        $telefon = $data->sendData->telefon;
        $biraliste = $data->sendData->biraliste;
        $email = $data->sendData->email;
        $datum_rodj = $data->sendData->datum_rodj;
        
        $poverenik_nivo_id = $data->sendData->poverenik_nivo;
        $opstina = $data->sendData->poverenistvo;
        $okrug = $data->sendData->okrug;
        

        $this->newDBB->insertPoverenik($ime, $prezime, $jmbg, $adresa, $telefon, $biraliste, $email, $datum_rodj, $poverenik_nivo_id, $opstina, $okrug);
    }

    public function azurirajPoverenika($data) {
        $id = $data->sendData->id;
        $ime = $data->sendData->ime;
        $prezime = $data->sendData->prezime;
        $jmbg = $data->sendData->jmbg;
        $adresa = $data->sendData->adresa;
        $telefon = $data->sendData->telefon;
        $biraliste = $data->sendData->biraliste;
        $email = $data->sendData->email;
        $datum_rodj = $data->sendData->datum_rodj;
        
        $this->newDBB->updatePoverenik($id, $ime, $prezime, $jmbg, $adresa, $telefon, $biraliste, $email, $datum_rodj);
    }
    
    public function izbrisiPoverenika($data){
        $id = $data->sendData->id;
        
        $this->newDBB->deletePoverenik($id);
    }

    public function loadTablePov() {
        $this->newDBB->loadTablePov();
    }

    public function getPoverenikById($data) {
        $id = $data->sendData->id;
        $this->newDBB->getPoverenikById($id);
    }
    
    public function ucitajSlobodneOpstine() {
        $this->newDBB->slobodneOpstine();
    }
    
    public function ubaciOkrugIPoverenistava($data){
        $okrug = $data->sendData->okrug;
        $nizPov = $data->sendData->niz;
        $this->newDBB->ubaciOkrugIPoverenistva($okrug, $nizPov);
    }

}

if (!empty($data)) {

    $admin = new AdminCtrl();
    switch ($data->action) {
        case 'ucitajOkruge':
            $admin->ucitajOkruge();
            break;
        case 'poverenistvaPoOkrugu':
            $admin->poverenistvaPoOkrugu($data);
            break;
        case 'kreirajPoverenika':
            $admin->kreirajPoverenika($data);
            break;
        case 'loadTablePov':
            $admin->loadTablePov();
            break;
        case 'getPoverenikById':
            $admin->getPoverenikById($data);
            break;
        case 'saveEditPoverenik':
            $admin->azurirajPoverenika($data);
            break;
        case 'deletePoverenik':
            $admin->izbrisiPoverenika($data);
            break;
        case 'slobodneOpstine':
            $admin->ucitajSlobodneOpstine();
            break;
        case 'sacuvajOkrug':
            $admin->ubaciOkrugIPoverenistava($data);
            break;

        default:
            echo json_encode(["status" => "error", "message" => "Грешка!"]);
            break;
    }
}