<?php

class Glasac {
    private $ime;
    private $prezime;
    private $jmbg;
    private $adresa;
    private $poverenistvo_id;
    private $telefon;
    private $biraliste;
    private $datum_unosa;
    private $email;
    private $datum_rodj;
    private $nosilac_glasova_id;
    private $nosilac_glasova_ime;
    private $poverenik_login_id;    
    
    
    public function getIme() {
        return $this->ime;
    }

    public function getPrezime() {
        return $this->prezime;
    }

    public function getJmbg() {
        return $this->jmbg;
    }

    public function getAdresa() {
        return $this->adresa;
    }

    public function getPoverenistvo_id() {
        return $this->poverenistvo_id;
    }

    public function getTelefon() {
        return $this->telefon;
    }

    public function getBiraliste() {
        return $this->biraliste;
    }

    public function getDatum_unosa() {
        return $this->datum_unosa;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getDatum_rodj() {
        return $this->datum_rodj;
    }

    public function getNosilac_glasova_id() {
        return $this->nosilac_glasova_id;
    }

    public function getNosilac_glasova_ime() {
        return $this->nosilac_glasova_ime;
    }

    public function getPoverenik_login_id() {
        return $this->poverenik_login_id;
    }

    public function setIme($ime): void {
        $this->ime = $ime;
    }

    public function setPrezime($prezime): void {
        $this->prezime = $prezime;
    }

    public function setJmbg($jmbg): void {
        $this->jmbg = $jmbg;
    }

    public function setAdresa($adresa): void {
        $this->adresa = $adresa;
    }

    public function setPoverenistvo_id($poverenistvo_id): void {
        $this->poverenistvo_id = $poverenistvo_id;
    }

    public function setTelefon($telefon): void {
        $this->telefon = $telefon;
    }

    public function setBiraliste($biraliste): void {
        $this->biraliste = $biraliste;
    }

    public function setDatum_unosa($datum_unosa): void {
        $this->datum_unosa = $datum_unosa;
    }

    public function setEmail($email): void {
        $this->email = $email;
    }

    public function setDatum_rodj($datum_rodj): void {
        $this->datum_rodj = $datum_rodj;
    }

    public function setNosilac_glasova_id($nosilac_glasova_id): void {
        $this->nosilac_glasova_id = $nosilac_glasova_id;
    }

    public function setNosilac_glasova_ime($nosilac_glasova_ime): void {
        $this->nosilac_glasova_ime = $nosilac_glasova_ime;
    }

    public function setPoverenik_login_id($poverenik_login_id): void {
        $this->poverenik_login_id = $poverenik_login_id;
    }


}

