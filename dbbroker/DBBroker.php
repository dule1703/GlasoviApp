<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

class DBBroker {

    private $servername = "localhost";
    private $username = "ddweba_voicesDBpom";
    private $password = "voices888";
    private $dbname = "ddweba_voicesDBpom";
    private $conn;

    function __construct() {
        $this->conn = $this->connectToDB();
    }

    //Connect to Database
    public function connectToDB() {

        $conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
        $conn->query("SET NAMES 'utf8' COLLATE 'utf8_unicode_ci'");
        // $conn->autocommit(FALSE);
        if ($conn->connect_error) {
            die("Failed connection: " . $conn->connect_error);
        } /* else {
          echo 'Uspešno povezano';
          } */

        return $conn;
    }

    public function checkLogin($username, $password) {
        try {
            $conn = $this->conn;

            $sql = "SELECT username, password FROM poverenik_login_temp WHERE username = ?";
            $stmt = $conn->prepare($sql);
            if ($stmt === false) {
                throw new Exception("Failed to prepare the SQL query.");
            }

            $bind_result = $stmt->bind_param("s", $username);
            if ($bind_result === false) {
                throw new Exception("Failed to bind parameters.");
            }

            $execute_result = $stmt->execute();
            if ($execute_result === false) {
                throw new Exception("Failed to execute the SQL query.");
            }

            $stmt->bind_result($db_username, $db_password);

            while ($stmt->fetch()) {
                if (password_verify($password, $db_password)) {
                    $_SESSION['loggedin'] = true;
                    $_SESSION["username"] = $db_username;

                    echo json_encode(["status" => "success"]);
                } else {
                    echo json_encode(["status" => "error", "message" => "Неисправна шифра!"]);
                }
            }

            if ($stmt->num_rows === 0) {
                echo json_encode(["status" => "error", "message" => "Неисправно корисничко име!"]);
            }
        } catch (Exception $e) {
            // Handle the exception
            echo json_encode(["status" => "error", "message" => $e->getMessage()]);
        }
    }

    public function checkEmail($email) {
        $conn = $this->conn;

        // Odsecanje svih znakova osim brojeva
        $emailCheck = $email;
        $pattern = "/^[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,}$/";

        if (!preg_match($pattern, $emailCheck)) {
            echo json_encode(["status" => "invalid email", "message" => "Унели сте невалидан мејл!"]);
        } else {
            try {
                $sqlUpit = "SELECT email FROM glasac WHERE email = ?";
                $stmt = $conn->prepare($sqlUpit);
                $stmt->bind_param("s", $emailCheck);
                $stmt->execute();
                $stmt->store_result();

                if ($stmt->num_rows > 0) {
                    echo json_encode(["status" => "existing", "message" => "Унели сте постојећи мејл!"]);
                } else {
                    echo json_encode(["status" => "free", "message" => ""]);
                }
            } catch (Exception $e) {
                echo json_encode(["status" => "error", "message" => "Грешка приликом приступа бази података: " . $e->getMessage()]);
            }
        }
    }

    public function checkJMBG($jmbg) {
        try {
            $conn = $this->conn;
            //odsecanje svih znakova osim brojeva
            $jmbgCheck = preg_replace("/[^0-9]/", "", $jmbg);
            $sqlUpit = "SELECT jmbg FROM glasac WHERE jmbg = ?";
            $stmt = $conn->prepare($sqlUpit);
            $stmt->bind_param("s", $jmbgCheck);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($jmbgResult);

            $nizJMBG = array();

            while ($stmt->fetch()) {
                $value = preg_replace("/[^0-9]/", "", $jmbgResult);
                array_push($nizJMBG, $value); //punjenje niza               
            }

            //provera da li u bazi vec postoji  JMBG broj koji se unosi
            if (in_array($jmbgCheck, $nizJMBG)) {
                echo json_encode(["bussyJMBG" => "Postojeći JMBG!", "message" => "Изабрали сте постојећи JMBG!"]);
            } else if (strlen($jmbgCheck) != 13) {
                echo json_encode(["lenghtJMBG" => "Dužina JMBG!", "message" => "ЈМБГ мора имати 13 цифри!"]);
            } else {
                echo json_encode(["newJMBG" => "Slobodan JMBG!", "message" => ""]);
            }
        } catch (Exception $e) {
            echo json_encode(["status" => "error", "message" => "Грешка: " + $e->getMessage()]);
        }
        exit();
    }

    public function checkTelefon($tel) {

        try {
            $conn = $this->conn;
            //odsecanje svih znakova osim brojeva
            $telCheck = preg_replace("/[^0-9]/", "", $tel);

            $sqlUpit = "SELECT telefon FROM glasac WHERE telefon = ?";
            $stmt = $conn->prepare($sqlUpit);
            $stmt->bind_param("s", $telCheck);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($telefon); //bind result to variable

            $nizTel = array();

            while ($stmt->fetch()) {
                $value = preg_replace("/[^0-9]/", "", $telefon);
                array_push($nizTel, $value); //punjenje niza
            }

            if (in_array($telCheck, $nizTel)) {
                echo json_encode(["bussyTel" => "Postojeći telefon!", "message" => "Унели сте постојећи телефон!"]);
            } else {
                echo json_encode(["newTel" => "Slobodan telefon!", "message" => ""]);
            }
        } catch (Exception $e) {
            echo json_encode(["status" => "error", "message" => "Грешка: " + $e->getMessage()]);
        }

        exit();
    }

    public function loadTowns() {
        try {

            $conn = $this->conn;

            $username = $_SESSION["username"];
            //$username = "Loznica";
            $opstinaOkrug = $this->latinToCyrillic($username);
            $nizOpstina = array();

            if ($username === "Admin" || $username === "Superadmin") {
                $sqlUpit = "SELECT naziv_opstine FROM opstine";
            } else {
                $sqlUpit = "SELECT op.naziv_opstine FROM opstine op
                                                INNER JOIN poverenistvo pov ON pov.opstina_id = op.id
                                                INNER JOIN okruzi ok ON ok.id = pov.okrug_id
                                            WHERE pov.okrug_id IN (SELECT DISTINCT ok.id FROM okruzi ok                                            
                                                                   WHERE ok.naziv_regiona = ?)
                                            OR op.naziv_opstine IN (SELECT DISTINCT op.naziv_opstine FROM opstine op                                            
                                                                   WHERE op.naziv_opstine = ?)";
            }


            $stmt = $conn->prepare($sqlUpit);
            if (!$stmt) {
                throw new Exception("Error in preparing the SQL statement.");
            }

            if ($username !== "Admin" && $username !== "Superadmin") {
                $stmt->bind_param("ss", $opstinaOkrug, $opstinaOkrug);
            }

            if (!$stmt->execute()) {
                throw new Exception("Error in executing the SQL statement.");
            }

            $stmt->bind_result($naziv_opstine);

            while ($stmt->fetch()) {
                array_push($nizOpstina, $naziv_opstine);
            }

            echo json_encode($nizOpstina);
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
        exit();
    }

    public function checkNosioce($opstina) {
        $conn = $this->conn;
        $nosiociGlasovaNiz = array();
        $nosiociGlasova = '';

        $sqlUpit = "SELECT gl.ime, gl.prezime, gl.jmbg, op.naziv_opstine FROM glasac gl
                            INNER JOIN poverenistvo pov ON gl.poverenistvo_id = pov.id
                            INNER JOIN opstine op ON op.id = pov.opstina_id
                WHERE gl.nosilac_glasova_id = 1 AND op.naziv_opstine = ?";

        try {
            $stmt = $conn->prepare($sqlUpit);
            if (!$stmt) {
                throw new Exception("Error in preparing the SQL statement.");
            }

            $stmt->bind_param("s", $opstina);

            if (!$stmt->execute()) {
                throw new Exception("Error in executing the SQL statement.");
            }

            $stmt->bind_result($ime, $prezime, $jmbg, $naziv_opstine);

            while ($stmt->fetch()) {
                $nosiociGlasova = $ime . " " . $prezime . " (" . $naziv_opstine . ") - ЈМБГ: " . $jmbg;
                array_push($nosiociGlasovaNiz, $nosiociGlasova);
            }

            if (!empty($nosiociGlasovaNiz)) {
                echo json_encode(["status" => "Ima nosioca", "message" => "Има носиоца!", "niz" => $nosiociGlasovaNiz]);
            } else {
                echo json_encode(["status" => "Nema nosioca", "message" => "Нема носиоца!"]);
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
        exit();
    }

    public function insertGlasac($ime, $prezime, $jmbg, $adresa, $telefon, $biraliste, $email, $datum_rodj, $temp_nosilac_glasova_id, $nosilac_glasova_ime, $opstina) {
        $conn = $this->conn;

        $username = $_SESSION["username"];

        //vraćanje šifre povereništva za izabranu opštinu
        $sqlUpit = "SELECT DISTINCT pov.id, op.naziv_opstine FROM poverenistvo pov
                INNER JOIN opstine op ON op.id = pov.opstina_id
                WHERE op.naziv_opstine = ?";

        try {
            $stmt = $conn->prepare($sqlUpit);
            if (!$stmt) {
                throw new Exception("Error in preparing the SQL statement.");
            }

            $stmt->bind_param("s", $opstina);

            if (!$stmt->execute()) {
                throw new Exception("Error in executing the SQL statement.");
            }

            $stmt->bind_result($poverenistvo_id, $naziv_opstine);

            while ($stmt->fetch()) {
                $temp = $poverenistvo_id;
            }

            date_default_timezone_set("Europe/Belgrade");
            $datum_unosa = date("d/m/Y H:i:s");

            //vraćanje id poverenika na osnovu username-a
            $sqlUpit2 = "SELECT id FROM poverenik_login WHERE username = ? ";

            $stmt2 = $conn->prepare($sqlUpit2);
            if (!$stmt2) {
                throw new Exception("Error in preparing the SQL statement.");
            }

            $stmt2->bind_param("s", $username);

            if (!$stmt2->execute()) {
                throw new Exception("Error in executing the SQL statement.");
            }

            $stmt2->bind_result($poverenik_login_id);

            while ($stmt2->fetch()) {
                $temp2 = $poverenik_login_id;
            }

            switch ($temp_nosilac_glasova_id) {
                case 0:
                    $nosilac_glasova_id = 2;
                    break;
                case 1:
                    $nosilac_glasova_id = 3;
                    break;
                default:
                    $nosilac_glasova_id = 1;
                    break;
            }
            // $poverenik_login_id = $temp2;

            $stmt3 = $conn->prepare("INSERT INTO glasac(ime, prezime, jmbg, adresa, poverenistvo_id, telefon, biraliste,  datum_unosa, email, datum_rodj, nosilac_glasova_id, nosilac_glasova_ime)"
                    . "VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

            if (!$stmt3) {
                throw new Exception("Error in preparing the SQL statement.");
            }

            $stmt3->bind_param("ssssisssssis", $ime, $prezime, $jmbg, $adresa, $poverenistvo_id, $telefon, $biraliste, $datum_unosa, $email, $datum_rodj, $nosilac_glasova_id, $nosilac_glasova_ime);

            $res = $stmt3->execute();
            if ($res) {
                echo json_encode(["status" => "uspesno", "message" => "Успешно сачувани подаци!"]);
            } else {
                echo json_encode(["status" => "neuspesno", "message" => "Неуспешно сачувани подаци!", "error" => $stmt3->error]);
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
        exit();
    }

    public function ucitaјNosioceTip() {

        try {
            $conn = $this->conn;

            $sqlUpit = "SELECT nosilac_glasova_tip FROM nosilac_glasova";
            $stmt = $conn->prepare($sqlUpit);
            $stmt->execute();
            $stmt->bind_result($nosilac_glasova_tip);

            $nizTipNosioca = array();

            while ($stmt->fetch()) {
                array_push($nizTipNosioca, $nosilac_glasova_tip);
            }

            if (count($nizTipNosioca) > 0) {
                echo json_encode(["success" => "Успешно учитано", "niz" => $nizTipNosioca]);
            } else {
                echo json_encode(["fail" => "Неуспешно учитано"]);
            }
        } catch (Exception $e) {
            echo "Грешка на серверу: " . $e->getMessage();
        }
    }

    public function loadTable() {
        try {
            $conn = $this->conn;

            $username = $_SESSION["username"];
            $opstinaOkrug = $this->latinToCyrillic($username);

            $nizOpstina = array();

            if ($username === "Admin" || $username === "Superadmin") {
                $sqlUpit = "SELECT gl.id, gl.ime, gl.prezime, gl.email, gl.jmbg, gl.adresa, gl.telefon, gl.biraliste, gl.datum_rodj, op.naziv_opstine, ok.naziv_regiona, gl.datum_unosa, ng.nosilac_glasova_tip, gl.nosilac_glasova_ime 
                            FROM opstine op		
                            INNER JOIN poverenistvo pov ON pov.opstina_id = op.id
                            INNER JOIN okruzi ok ON ok.id = pov.okrug_id
                            INNER JOIN glasac gl ON gl.poverenistvo_id = pov.id
                            INNER JOIN nosilac_glasova ng ON ng.id = gl.nosilac_glasova_id";
            } else {
                $sqlUpit = "SELECT gl.id, gl.ime, gl.prezime, gl.email, gl.jmbg, gl.adresa, gl.telefon, gl.biraliste, gl.datum_rodj, op.naziv_opstine, ok.naziv_regiona, gl.datum_unosa, ng.nosilac_glasova_tip, gl.nosilac_glasova_ime 
                            FROM opstine op		
                                INNER JOIN poverenistvo pov ON pov.opstina_id = op.id
                                INNER JOIN okruzi ok ON ok.id = pov.okrug_id
                                INNER JOIN glasac gl ON gl.poverenistvo_id = pov.id
                                INNER JOIN nosilac_glasova ng ON ng.id = gl.nosilac_glasova_id
                            WHERE pov.okrug_id = (SELECT ok.id FROM okruzi ok WHERE ok.naziv_regiona = ?)
                                  OR op.naziv_opstine = (SELECT DISTINCT op.naziv_opstine FROM opstine op WHERE op.naziv_opstine = ?)";
            }

            if ($stmt = $conn->prepare($sqlUpit)) {
                if ($username !== "Admin" && $username !== "Superadmin") {
                    $stmt->bind_param("ss", $opstinaOkrug, $opstinaOkrug);
                }
                $stmt->execute();
                $stmt->bind_result(
                        $id,
                        $ime,
                        $prezime,
                        $email,
                        $jmbg,
                        $adresa,
                        $telefon,
                        $biraliste,
                        $datum_rodj,
                        $naziv_opstine,
                        $naziv_regiona,
                        $datum_unosa,
                        $nosilac_glasova_tip,
                        $nosilac_glasova_ime
                );

                while ($stmt->fetch()) {
                    $item = array(
                        'id' => $id,
                        'ime' => $ime,
                        'prezime' => $prezime,
                        'email' => $email,
                        'jmbg' => $jmbg,
                        'adresa' => $adresa,
                        'telefon' => $telefon,
                        'biraliste' => $biraliste,
                        'datum_rodj' => $datum_rodj,
                        'naziv_opstine' => $naziv_opstine,
                        'naziv_regiona' => $naziv_regiona,
                        'datum_unosa' => $datum_unosa,
                        'nosilac_glasova_tip' => $nosilac_glasova_tip,
                        'nosilac_glasova_ime' => $nosilac_glasova_ime
                    );
                    array_push($nizOpstina, $item);
                }

                echo json_encode($nizOpstina);
            } else {
                throw new Exception("Database query failed");
            }
        } catch (Exception $e) {
            echo "Грешка на серверу: " . $e->getMessage();
        }
        exit();
    }

    //EDITOVANJE TABELE

    public function getGlasacById($id) {
        try {
            $conn = $this->conn;
            $sqlUpit = "SELECT gl.ime, gl.prezime, gl.email, gl.jmbg, gl.adresa, gl.telefon, gl.biraliste, gl.datum_rodj, op.naziv_opstine, ok.naziv_regiona, gl.datum_unosa, ng.nosilac_glasova_tip, gl.nosilac_glasova_ime 
                            FROM opstine op		
                            INNER JOIN poverenistvo pov ON pov.opstina_id = op.id
                            INNER JOIN okruzi ok ON ok.id = pov.okrug_id
                            INNER JOIN glasac gl ON gl.poverenistvo_id = pov.id
                            INNER JOIN nosilac_glasova ng ON ng.id = gl.nosilac_glasova_id
                        WHERE gl.id = ?";
            $stmt = $conn->prepare($sqlUpit);

            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->bind_result(
                    $ime,
                    $prezime,
                    $email,
                    $jmbg,
                    $adresa,
                    $telefon,
                    $biraliste,
                    $datum_rodj,
                    $naziv_opstine,
                    $naziv_regiona,
                    $datum_unosa,
                    $nosilac_glasova_tip,
                    $nosilac_glasova_ime
            );
            if ($stmt->fetch()) {
                $item = array(
                    'ime' => $ime,
                    'prezime' => $prezime,
                    'email' => $email,
                    'jmbg' => $jmbg,
                    'adresa' => $adresa,
                    'telefon' => $telefon,
                    'biraliste' => $biraliste,
                    'datum_rodj' => $datum_rodj,
                    'naziv_opstine' => $naziv_opstine,
                    'naziv_regiona' => $naziv_regiona,
                    'datum_unosa' => $datum_unosa,
                    'nosilac_glasova_tip' => $nosilac_glasova_tip,
                    'nosilac_glasova_ime' => $nosilac_glasova_ime
                );

                echo json_encode(["status" => "success", "niz" => $item]);
            } else {
                echo json_encode(["status" => "fail"]);
            }
        } catch (Exception $e) {
            $message = "Грешка на серверу: " . $e->getMessage();
            echo json_encode(["status" => "error", "message" => $message]);
        }
        exit();
    }

    public function deleteGlasac($id) {
        try {
            $conn = $this->conn;

            $sqlUpit = "DELETE FROM glasac WHERE id = ?";
            $stmt = $conn->prepare($sqlUpit);
            $stmt->bind_param("i", $id);
            $res = $stmt->execute();
            if ($res) {
                echo json_encode(["status" => "success", "message" => "Успешно избрисан гласач!"]);
            } else {
                echo json_encode(["status" => "fail", "message" => "Неуспешно брисање!"]);
            }
        } catch (Exception $e) {
            echo json_encode(["status" => "error", "message" => "Грешка: " . $e->getMessage()]);
        }
    }

    public function getImeNosioca($tempOpstina) {
        $conn = $this->conn;

        $nosiociGlasovaNiz = array();
        $nosiociGlasova = '';

        $sqlUpit = "SELECT gl.ime, gl.prezime, gl.jmbg, op.naziv_opstine FROM glasac gl
						  INNER JOIN poverenistvo pov ON gl.poverenistvo_id = pov.id
                                                  INNER JOIN opstine op ON op.id = pov.opstina_id
                    WHERE gl.nosilac_glasova_id = 1 AND op.naziv_opstine = '$tempOpstina'";

        $result = $conn->query($sqlUpit);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $nosiociGlasova = $row['ime'] . " " . $row['prezime'] . " (" . $row['naziv_opstine'] . ") - ЈМБГ: " . $row['jmbg'];
                array_push($nosiociGlasovaNiz, $nosiociGlasova);
            }
            echo json_encode(["status" => "success", "niz" => $nosiociGlasovaNiz]);
        } else {
            echo json_encode(["status" => "fail", "message" => "За ово повереништво нема додељених носилаца!"]);
        }
    }

    public function getOpstineNosilacIme($tempOpstina) {
        try {
            $conn = $this->conn;
            $nosiociGlasovaNiz = array();
            $nosiociGlasova = '';
            $sqlUpit = "SELECT gl.ime, gl.prezime, gl.jmbg, op.naziv_opstine, gl.nosilac_glasova_ime, ng.nosilac_glasova_tip FROM glasac gl
                                INNER JOIN poverenistvo pov ON gl.poverenistvo_id = pov.id
                                INNER JOIN opstine op ON op.id = pov.opstina_id
                                INNER JOIN nosilac_glasova ng ON ng.id = gl.nosilac_glasova_id
                        WHERE op.naziv_opstine = ?";

            $stmt = $conn->prepare($sqlUpit);
            $stmt->bind_param("s", $tempOpstina);
            $stmt->execute();
            $stmt->bind_result($ime, $prezime, $jmbg, $naziv_opstine, $nosilac_glasova_ime, $nosilac_glasova_tip);

            while ($stmt->fetch()) {
                $nosiociGlasova = $ime . " " . $prezime . " (" . $naziv_opstine . ") - ЈМБГ: " . $jmbg;
                $item = array(
                    "naziv_opstine" => $naziv_opstine,
                    "nosilac_glasova_ime" => $nosiociGlasova,
                    "nosilac_glasova_tip" => $nosilac_glasova_tip
                );
                array_push($nosiociGlasovaNiz, $item);
            }

            if (count($nosiociGlasovaNiz) > 0) {
                echo json_encode(["status" => "success", "niz" => $nosiociGlasovaNiz]);
            } else {
                echo json_encode(["status" => "fail", "message" => "За ово повереништво нема додељених носилаца!"]);
            }
        } catch (Exception $e) {
            echo "Грешка на серверу: " . $e->getMessage();
        }
        exit();
    }

    public function updateGlasac($id, $ime, $prezime, $jmbg, $adresa, $telefon, $biraliste, $email, $datum_rodj,
            $nosilac_glasova_id, $nosilac_glasova_ime, $opstina) {
        try {
            $conn = $this->conn;

            $username = $_SESSION["username"];

            //vraćanje šifre povereništva za izabranu opštinu
            $sqlUpit = "SELECT DISTINCT pov.id, op.naziv_opstine FROM poverenistvo pov
                                INNER JOIN opstine op ON op.id = pov.opstina_id
                        WHERE op.naziv_opstine = ?";

            $stmt = $conn->prepare($sqlUpit);
            $stmt->bind_param("s", $opstina);
            $stmt->execute();
            $stmt->bind_result($tempID, $naziv_opstine);

            while ($stmt->fetch()) {
                $poverenistvo_id = $tempID;
            }

            //vraćanje id poverenika na osnovu username-a
            $sqlUpit2 = "SELECT DISTINCT id, username FROM poverenik_login
		                        WHERE username = ?";

            $stmt2 = $conn->prepare($sqlUpit2);
            $stmt2->bind_param("s", $username);
            $stmt2->execute();
            $stmt2->bind_result($temp2ID, $username);

            while ($stmt2->fetch()) {
                //$poverenik_login_id = $temp2ID;
            }

            $sqlUpit3 = "UPDATE glasac
                         SET ime = ?, prezime = ?, jmbg = ?, adresa = ?, poverenistvo_id = ?, telefon = ?, biraliste = ?,
                             email = ?, datum_rodj = ?, nosilac_glasova_id = ?, nosilac_glasova_ime = ?
                         WHERE id = ?;";

            $stmt3 = $conn->prepare($sqlUpit3);
            $stmt3->bind_param("ssssissssisi", $ime, $prezime, $jmbg, $adresa, $poverenistvo_id, $telefon, $biraliste,
                    $email, $datum_rodj, $nosilac_glasova_id, $nosilac_glasova_ime, $id);

            $res = $stmt3->execute();
            if ($res) {
                echo json_encode(["status" => "success", "message" => "Успешно измењени подаци!"]);
            } else {
                throw new Exception("Неуспешно измењени подаци! Error: " . $stmt3->error);
            }
        } catch (Exception $e) {
            echo json_encode(["status" => "fail", "message" => $e->getMessage()]);
        }
        exit();
    }

    /*     * ************************ */
    /*     * * Verification email ** */
    /*     * ************************ */

    function getUserLoginId($username) {
        try {
            $conn = $this->conn;

            $sqlUpit = "SELECT id FROM poverenik_login WHERE username = ?";
            $stmt = $conn->prepare($sqlUpit);
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $stmt->bind_result($resultId);

            while ($stmt->fetch()) {
                $id = $resultId;
            }
            return $id;
        } catch (Exception $e) {
            echo json_encode(["status" => "fail", "message" => $e->getMessage()]);
        }
    }

    function checkUser($username) {
        try {
            $conn = $this->conn;

            $sqlUpit = "SELECT username FROM poverenik_login WHERE username = ? ";
            $stmt = $conn->prepare($sqlUpit);
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $stmt->bind_result($username);
            if ($stmt->fetch()) {
                // Username exists in the database
                echo json_encode(["status" => "success", "message" => "Корисничко име постоји: " . $username]);
            } else {
                // Username does not exist in the database
                echo json_encode(["status" => "wrong", "message" => "Корисничко име не постоји!"]);
            }
        } catch (Exception $e) {
            echo json_encode(["status" => "fail", "message" => $e->getMessage()]);
        }
    }

    public function checkAndInsertEmail($email, $username) {
        $conn = $this->conn;

        // Odsecanje svih znakova osim brojeva
        $emailCheck = $email;
        $pattern = "/^[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,}$/";

        if (!preg_match($pattern, $emailCheck)) {
            echo json_encode(["status" => "invalid email", "message" => "Унели сте невалидан мејл!"]);
        } else {
            try {
                $conn->autocommit(false); // Start a transaction
                // Check if the pair of username and email exists in the database
                $sqlUpit = "SELECT email FROM poverenik_login WHERE username = ?";
                $stmt = $conn->prepare($sqlUpit);
                $stmt->bind_param("s", $username);
                $stmt->execute();
                $stmt->bind_result($existingEmail);
                $stmt->fetch();
                $stmt->close(); // Close the first statement before executing the second one

                if ($existingEmail !== null) {
                    // The username exists, now check if the email exists
                    $sqlUpit2 = "SELECT email FROM poverenik_login WHERE email = ?";
                    $stmt2 = $conn->prepare($sqlUpit2);
                    $stmt2->bind_param("s", $emailCheck);
                    $stmt2->execute();
                    $stmt2->bind_result($fetchedEmail);
                    $stmt2->fetch();
                    $stmt2->close(); // Close the second statement before proceeding

                    if (!empty($fetchedEmail)) {
                        // The pair of username and email already exists in the database
                        echo json_encode(["status" => "bussy", "message" => "Овај мејл је заузет!"]);
                    } else {
                        // Update the email in the database only if it's empty or null
                        if ($existingEmail === null || $existingEmail === "") {
                            $sqlUpit3 = "UPDATE poverenik_login SET email = ? WHERE username = ?";
                            $stmt3 = $conn->prepare($sqlUpit3);
                            $stmt3->bind_param("ss", $emailCheck, $username);
                            $stmt3->execute();
                            $stmt3->close(); // Close the third statement after the update
                            echo json_encode(["status" => "existing", "message" => "Успешно сачувано!"]);
                            $conn->commit(); // Commit the transaction
                            $conn->autocommit(true); // Turn autocommit back on
                        } else {
                            // Email is not empty, and the pair does not exist
                            echo json_encode(["status" => "not found", "message" => "Пар username/email не постоји."]);
                        }
                    }
                } else {
                    // The username does not exist
                    echo json_encode(["status" => "not found", "message" => "Пар username/email не постоји."]);
                }
            } catch (Exception $e) {
                $conn->rollback(); // Rollback the transaction if an error occurred
                $conn->autocommit(true); // Turn autocommit back on
                echo json_encode(["status" => "error", "message" => "Грешка приликом приступа бази података: " . $e->getMessage()]);
            }
        }
    }

    function existEmail($username) {
        try {
            $conn = $this->conn;
            $sqlUpit = "SELECT email FROM poverenik_login WHERE username = ?";
            $stmt = $conn->prepare($sqlUpit);
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $stmt->bind_result($email);

            $stmt->fetch();

            if ($email !== "") {
                echo json_encode(["status" => "existing", "message" => "Мејл постоји!"]);
            } else {
                echo json_encode(["status" => "empty", "message" => "Мејл није унешен!"]);
            }
        } catch (Exception $e) {
            
        }
    }

    function checkUsernameEmail($username, $email) {
        $conn = $this->conn;

        // Odsecanje svih znakova osim brojeva
        $emailCheck = $email;
        $pattern = "/^[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,}$/";

        if (!preg_match($pattern, $emailCheck)) {
            echo json_encode(["status" => "invalid email", "message" => "Унели сте невалидан мејл!"]);
        } else {

            try {
                $sqlUpit = "SELECT email FROM poverenik_login WHERE email = ? AND username = ?";
                $stmt = $conn->prepare($sqlUpit);
                $stmt->bind_param("ss", $emailCheck, $username);
                $stmt->execute();
                $stmt->store_result();

                if ($stmt->num_rows > 0) {
                    echo json_encode(["status" => "existing", "message" => "Овај мејл је заузет!"]);
                } else {
                    echo json_encode(["status" => "wrong", "message" => "Непостојећи мејл!"]);
                }
            } catch (Exception $e) {
                echo json_encode(["status" => "error", "message" => "Грешка приликом приступа бази података: " . $e->getMessage()]);
            }
        }
    }

    // Generate a Unique Verification Code/Token
    function generateVerificationCode() {
        $tokenLength = 32; // Length of the verification code/token
        $verificationCode = bin2hex(random_bytes($tokenLength));

        return $verificationCode;
    }

    function storeVerificationToken($userId, $verificationCode) {
        try {
            $expirationTime = date('Y-m-d H:i:s', strtotime('+5 minutes'));
            $conn = $this->conn;

            // Prepare and execute the SQL query to insert the data into the verification_tokens table
            $stmt = $conn->prepare("INSERT INTO verification_tokens (user_id, token, expiration_time) VALUES (?, ?, ?)");
            $stmt->bind_param("iss", $userId, $verificationCode, $expirationTime);
            $stmt->execute();
            $stmt->close();
            echo json_encode(["status" => "success", "message" => "Успешно сачуван токен у базу података."]);
        } catch (Exception $e) {
            echo json_encode(["status" => "fail", "message" => $e->getMessage()]);
        }
    }

    function sendVerificationEmail($userEmail, $verificationCode) {
        $verificationLink = "https://voicesapp-oop-php-vanillajs-bs5.ddwebapps.com/controllers/resetPassCtrl/sendToken.php?token=" . urlencode($verificationCode);
        $subject = "Zahtev za resetovanje sifre";
        $message = "Kliknite na link za resetovanje sifre:\n\n<a href='$verificationLink'>Reset Password</a>";
        $headers = "From: contact@ddwebapps.com\r\n";
        $headers .= "Content-type: text/html\r\n"; // Set the email content type to HTML
        // Use the mail() function to send the email (configure the mail server settings first)
        mail($userEmail, $subject, $message, $headers);
    }

    function validateVerificationToken($token) {
        try {
            $conn = $this->conn;
            // Prepare and execute the SQL query to check if the token exists and is valid
            $stmt = $conn->prepare("SELECT user_id, expiration_time FROM verification_tokens WHERE token = ? AND expiration_time > NOW()");
            $stmt->bind_param("s", $token);
            $stmt->execute();
            $stmt->store_result();

            // Check if a row was returned (token exists and is valid)
            $isValidToken = ($stmt->num_rows === 1);

            // Close the statement and database connection
            $stmt->close();

            // Echo the JSON response
            // echo json_encode(["status" => "success", "isValidToken" => $isValidToken]);
            return $isValidToken;
        } catch (Exception $e) {
            echo json_encode(["status" => "fail", "message" => $e->getMessage()]);
        }
    }

    function getUserIdByToken($token) {
        try {
            $conn = $this->conn;
            // Prepare and execute the SQL query
            $stmt = $conn->prepare("SELECT user_id FROM verification_tokens  WHERE token = ?");
            $stmt->bind_param("s", $token);
            $stmt->execute();
            $stmt->bind_result($userId);

            // Fetch the result
            $stmt->fetch();

            // Close the statement and database connection
            $stmt->close();

            // Return the userId
            return $userId;
        } catch (Exception $e) {
            echo json_encode(["status" => "success", "message" => $e->getMessage()]);
        }
    }

    public function updatePassword($username, $password) {
        $conn = $this->conn;
        $password_hash = password_hash($password, PASSWORD_BCRYPT);

        try {
            $sqlQuery = "UPDATE poverenik_login SET password = ? WHERE username = ?";
            $stmt = $conn->prepare($sqlQuery);
            $stmt->bind_param("ss", $password_hash, $username);
            $res = $stmt->execute();

            $stmt->close();
            return true;
            //return $res;
        } catch (Exception $e) {
            // Handle the exception if something goes wrong during the update
            return false;
        }
    }

    function latinToCyrillic($source) {
        $lat = array('lj', 'nj', 'dž', 'Lj', 'Nj', 'Dž', 'a', 'b', 'v', 'g', 'd', 'đ', 'e', 'ž', 'z', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'ć', 'u', 'f', 'h', 'c', 'č', 'š', 'A', 'B', 'V', 'G', 'D', 'Đ', 'E', 'Ž', 'Z', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'Ć', 'U', 'F', 'H', 'C', 'Č', 'Š');

        $cyr = array('љ', 'њ', 'џ', 'Љ', 'Њ', 'Џ', 'а', 'б', 'в', 'г', 'д', 'ђ', 'е', 'ж', 'з', 'и', 'ј', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'ћ', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'А', 'Б', 'В', 'Г', 'Д', 'Ђ', 'Е', 'Ж', 'З', 'И', 'Ј', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'Ћ', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш');

        $lowerSource = mb_strtolower($source, 'UTF-8');
        $converted = str_ireplace($lat, $cyr, $lowerSource);
        return mb_convert_case($converted, MB_CASE_TITLE, 'UTF-8');
    }

    function cyrillicToLatin($source) {
        $lat = array('lj', 'nj', 'dž', 'Lj', 'Nj', 'Dž', 'a', 'b', 'v', 'g', 'd', 'đ', 'e', 'ž', 'z', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'ć', 'u', 'f', 'h', 'c', 'č', 'š', 'A', 'B', 'V', 'G', 'D', 'Đ', 'E', 'Ž', 'Z', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'Ć', 'U', 'F', 'H', 'C', 'Č', 'Š');

        $cyr = array('љ', 'њ', 'џ', 'Љ', 'Њ', 'Џ', 'а', 'б', 'в', 'г', 'д', 'ђ', 'е', 'ж', 'з', 'и', 'ј', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'ћ', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'А', 'Б', 'В', 'Г', 'Д', 'Ђ', 'Е', 'Ж', 'З', 'И', 'Ј', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'Ћ', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш');

        $lowerSource = mb_strtolower($source, 'UTF-8');
        $converted = str_ireplace($cyr, $lat, $lowerSource);
        return mb_convert_case($converted, MB_CASE_TITLE, 'UTF-8');
    }

    /*     * ************************************** */
    /* АДМИНИСТРАЦИЈА ОКРУГА И ПОВЕРЕНИКА */

    /**     * ********************************** */
    /*     * *АДМИНИСТРАЦИЈА ПОВЕРЕНИКА** */

    public function insertPoverenik($ime, $prezime, $jmbg, $adresa, $telefon, $biraliste, $email, $datum_rodj, $poverenik_nivo_id, $opstina, $okrug) {
        $conn = $this->conn;

        try {
            $conn->autocommit(false); // Start a transaction
            // Vraćanje id povereništva    
            $sqlUpit = "SELECT DISTINCT pov.id FROM poverenistvo pov
                            INNER JOIN opstine op ON op.id = pov.opstina_id
                        WHERE op.naziv_opstine = ?";

            $stmt = $conn->prepare($sqlUpit);
            $stmt->bind_param("s", $opstina);
            $stmt->execute();
            $stmt->bind_result($id);
            while ($stmt->fetch()) {
                $poverenistvo_id = $id;
            }
            $stmt->close();

            date_default_timezone_set("Europe/Belgrade");
            $datum_unosa = date("d/m/Y H:i:s");

            $poverenik_login_id = $this->createPoverenikLogin($opstina, $okrug, $poverenik_nivo_id);

            $sqlUpit = "INSERT INTO poverenik(ime, prezime, jmbg, adresa, telefon, biraliste, email, datum_rodj, poverenik_nivo_id, poverenik_login_id, poverenistvo_id)"
                    . "VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt2 = $conn->prepare($sqlUpit);

            if (!$stmt2) {
                throw new Exception("Error in preparing the SQL statement.");
            }

            $stmt2->bind_param("ssssssssiii", $ime, $prezime, $jmbg, $adresa, $telefon, $biraliste, $email, $datum_rodj, $poverenik_nivo_id, $poverenik_login_id, $poverenistvo_id);

            $res = $stmt2->execute();
            $stmt2->close();
            //dobijanje korisničkog imena na osnovu id 
            $sqlUpit1 = "SELECT username FROM poverenik_login_temp WHERE id = ?";
            $stmt1 = $conn->prepare($sqlUpit1);
            $stmt1->bind_param("s", $poverenik_login_id);
            $stmt1->execute();
            $stmt1->bind_result($usr);
            while ($stmt1->fetch()) {
                //Slanje korisničkog imena na email novokreiranog poverenika
                $subject = "Korisničko ime";
                $message = "Vaše korisničko ime je: " . $usr . " <br> Šifru možete promeniti na <a href='https://voicesapp-oop-php-vanillajs-bs5.ddwebapps.com/'>linku</a>";
                $headers = "From: contact@ddwebapps.com\r\n";
                $headers .= "Content-type: text/html\r\n"; // Set the email content type to HTML               
                mail($email, $subject, $message, $headers);
            }

            if ($res) {
                $nosilac_glasova_id = 1;
                $nosilac_glasova_ime = "";
                $sqlUpit3 = "INSERT INTO glasac(ime, prezime, jmbg, adresa, poverenistvo_id, telefon, biraliste,  datum_unosa, email, datum_rodj, nosilac_glasova_id, nosilac_glasova_ime)"
                        . "VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt3 = $conn->prepare($sqlUpit3);
                $stmt3->bind_param("ssssisssssis", $ime, $prezime, $jmbg, $adresa, $poverenistvo_id, $telefon, $biraliste, $datum_unosa, $email, $datum_rodj, $nosilac_glasova_id, $nosilac_glasova_ime);
                $stmt3->execute();
                $stmt3->close();
                $conn->commit(); // Commit the transaction
                $conn->autocommit(true); // Turn autocommit back on
                echo json_encode(["status" => "uspesno", "message" => "Успешно сачувани подаци!"]);
            } else {
                echo json_encode(["status" => "neuspesno", "message" => "Неуспешно сачувани подаци!", "error" => $stmt->error]);
            }
        } catch (Exception $e) {
            $conn->rollback(); // Rollback the transaction if an error occurred
            $conn->autocommit(true); // Turn autocommit back on
            echo "Error: " . $e->getMessage();
            // Log the exception details
            $errorDetails = "Exception in insertPoverenik: " . $e->getMessage();
            error_log($errorDetails, 0); // Log the error to the PHP error log
        }
        exit();
    }

    public function createPoverenikLogin($opstina, $okrug, $poverenik_nivo) {
        $conn = $this->conn;
        try {
            $conn->autocommit(false); // Start a transaction   
            // Validate and sanitize za ulazne vrednosti
            $opstina = $this->sanitizeInput($opstina);
            $okrug = $this->sanitizeInput($okrug);
            //Korisničko ime je ime okruga ili opstine poverenika u zavisnoti da li je opštinski ili okružni poverenik             
            if ($poverenik_nivo !== 3 && $poverenik_nivo !== 4) {
                throw new Exception("Invalid poverenik_nivo");
            }
            if ($poverenik_nivo === 4) {
                $username = $this->cyrillicToLatin($okrug);
            } else {
                $username = $this->cyrillicToLatin($opstina);
            }

            // Šifra je ista kao korisničko ime zbog jednostavnosti. Poverenik može uvek da promeni šifru na stranici logovanja.
            $password = password_hash($username, PASSWORD_BCRYPT);

            //Ubacivanje novog para username/password
            $sqlUpit = "INSERT INTO poverenik_login_temp(username, password) VALUES(?,?)";
            $stmt = $conn->prepare($sqlUpit);
            $stmt->bind_param("ss", $username, $password);
            $stmt->execute();
            $stmt->close();
            //Vraćanje id koji se koristi prilikom kreiranja poverenika
            $sqlUpit2 = "SELECT id FROM poverenik_login_temp WHERE username = ?";
            $stmt2 = $conn->prepare($sqlUpit2);
            $stmt2->bind_param("s", $username);
            $stmt2->execute();
            $stmt2->bind_result($id);
            while ($stmt2->fetch()) {
                return $id;
            }
            $stmt2->close();
            $conn->commit(); // Commit the transaction
            $conn->autocommit(true); // Turn autocommit back on
        } catch (Exception $e) {
            $conn->rollback(); // Rollback the transaction if an error occurred
            $conn->autocommit(true); // Turn autocommit back on
            echo json_encode(["status" => "fail", "message" => "Грешка: " . $e->getMessage()]);
        }
    }

    // Helper function to sanitize input
    private function sanitizeInput($input) {
        $input = trim($input);
        $input = stripslashes($input);
        $input = htmlspecialchars($input);
        return $input;
    }

    public function loadOkrug() {
        $conn = $this->conn;
        try {
            $sqlUpit = "SELECT id, naziv_regiona FROM okruzi";
            $stmt = $conn->prepare($sqlUpit);
            $stmt->execute();
            $stmt->bind_result($id, $naziv_regiona);

            $results = [];
            while ($stmt->fetch()) {
                $results[] = [
                    "id" => $id,
                    "naziv_regiona" => $naziv_regiona,
                ];
            }

            $stmt->close();

            echo json_encode(["status" => "success", "message" => "Успешно учитани окрузи", "niz" => $results]);
        } catch (Exception $e) {
            echo json_encode(["status" => "error", "message" => "Грешка: " . $e->getMessage()]);
        }
    }

    public function poverenistvaByOkrug($okrug) {
        $conn = $this->conn;
        try {
            $sqlUpit = "SELECT o.naziv_opstine FROM opstine o 
                                                INNER JOIN poverenistvo pov ON pov.opstina_id = o.id
                                                INNER JOIN okruzi ok ON ok.id = pov.okrug_id
                        WHERE ok.naziv_regiona = ?";
            $stmt = $conn->prepare($sqlUpit);
            $stmt->bind_param("s", $okrug);
            $stmt->execute();
            $stmt->bind_result($poverenistva);
            $resultArray = array();

            while ($stmt->fetch()) {
                array_push($resultArray, $poverenistva);
            }
            echo json_encode(["status" => "success", "message" => "Успешно учитане општине", "niz" => $resultArray]);
        } catch (Exception $e) {
            echo json_encode(["status" => "error", "message" => "Грешка: " . $e->getMessage()]);
        }
    }

    public function loadTablePov() {
        try {
            $conn = $this->conn;

            $nizOpstina = array();

            $sqlUpit = "SELECT pvr.id, pvr.ime, pvr.prezime, pvr.jmbg, pvr.adresa, pvr.telefon, pvr.biraliste, pvr.email, pvr.datum_rodj, pn.admin_nivo, o.naziv_opstine, ok.naziv_regiona  
                        FROM poverenik pvr INNER JOIN poverenik_nivo pn ON pn.id = pvr.poverenik_nivo_id
                                           LEFT JOIN poverenistvo pov ON pov.id = pvr.poverenistvo_id
                                           LEFT JOIN opstine o ON o.id = pov.opstina_id
                                           LEFT JOIN okruzi ok ON ok.id = pov.okrug_id";

            if ($stmt = $conn->prepare($sqlUpit)) {
                $stmt->execute();
                $stmt->bind_result(
                        $id,
                        $ime,
                        $prezime,
                        $jmbg,
                        $adresa,
                        $telefon,
                        $biraliste,
                        $email,
                        $datum_rodj,
                        $admin_nivo,
                        $naziv_opstine,
                        $naziv_regiona
                );

                while ($stmt->fetch()) {
                    $item = array(
                        'id' => $id,
                        'ime' => $ime,
                        'prezime' => $prezime,
                        'email' => $email,
                        'jmbg' => $jmbg,
                        'adresa' => $adresa,
                        'telefon' => $telefon,
                        'biraliste' => $biraliste,
                        'datum_rodj' => $datum_rodj,
                        'naziv_opstine' => $naziv_opstine,
                        'naziv_regiona' => $naziv_regiona,
                        'admin_nivo' => $admin_nivo
                    );
                    array_push($nizOpstina, $item);
                }

                echo json_encode($nizOpstina);
            } else {
                throw new Exception("Database query failed");
            }
        } catch (Exception $e) {
            echo "Грешка на серверу: " . $e->getMessage();
        }
        exit();
    }

    public function getPoverenikById($id) {
        try {
            $conn = $this->conn;
            $sqlUpit = "SELECT pvr.ime, pvr.prezime, pvr.jmbg, pvr.adresa, pvr.telefon, pvr.biraliste, pvr.email, pvr.datum_rodj, pn.admin_nivo, o.naziv_opstine, ok.naziv_regiona  
                        FROM poverenik pvr INNER JOIN poverenik_nivo pn ON pn.id = pvr.poverenik_nivo_id
                                           LEFT JOIN poverenistvo pov ON pov.id = pvr.poverenistvo_id
                                           LEFT JOIN opstine o ON o.id = pov.opstina_id
                                           LEFT JOIN okruzi ok ON ok.id = pov.okrug_id
                        WHERE pvr.id = ?";
            $stmt = $conn->prepare($sqlUpit);

            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->bind_result(
                    $ime,
                    $prezime,
                    $jmbg,
                    $adresa,
                    $telefon,
                    $biraliste,
                    $email,
                    $datum_rodj,
                    $admin_nivo,
                    $naziv_opstine,
                    $naziv_regiona
            );
            if ($stmt->fetch()) {
                $item = array(
                    'ime' => $ime,
                    'prezime' => $prezime,
                    'email' => $email,
                    'jmbg' => $jmbg,
                    'adresa' => $adresa,
                    'telefon' => $telefon,
                    'biraliste' => $biraliste,
                    'datum_rodj' => $datum_rodj,
                    'naziv_opstine' => $naziv_opstine,
                    'naziv_regiona' => $naziv_regiona,
                    'admin_nivo' => $admin_nivo
                );
                // print_r($item);
                echo json_encode(["status" => "success", "niz" => $item]);
            } else {
                echo json_encode(["status" => "fail"]);
            }
        } catch (Exception $e) {
            $message = "Грешка на серверу: " . $e->getMessage();
            echo json_encode(["status" => "error", "message" => $message]);
        }
        exit();
    }

    public function updatePoverenik($id, $ime, $prezime, $jmbg, $adresa, $telefon, $biraliste, $email, $datum_rodj) {
        $conn = $this->conn;

        try {
            $conn->autocommit(false); // Početak transakcije
            //Vraćanje mejla poverenika za dati id pre ažuriranja
            $sqlUpit4 = "SELECT email FROM poverenik WHERE id = ?";
            $stmt4 = $conn->prepare($sqlUpit4);
            $stmt4->bind_param("i", $id);
            $stmt4->execute();
            $stmt4->bind_result($emailPov);
            while ($stmt4->fetch()) {
                $currentEmail = $emailPov;
            }
            $stmt4->close();
            //Ažuriranje poverenika
            $sqlUpit3 = "UPDATE poverenik SET ime = ?, prezime = ?, jmbg = ?, adresa = ?, telefon = ?, biraliste = ?,
                                             email = ?, datum_rodj = ?
                         WHERE id = ?";
            $stmt3 = $conn->prepare($sqlUpit3);

            if (!$stmt3) {
                throw new Exception("Error in preparing the SQL statement.");
            }

            $stmt3->bind_param("ssssssssi", $ime, $prezime, $jmbg, $adresa, $telefon, $biraliste, $email, $datum_rodj, $id);

            $res = $stmt3->execute();
            $stmt3->close();

            //Ako postoji id u tabeli glasac sa istim email-om kao i email poverenika onda ažurirati i glasača
            $sqlUpit5 = "SELECT id FROM glasac WHERE email = ?";
            $stmt5 = $conn->prepare($sqlUpit5);
            $stmt5->bind_param("s", $currentEmail);
            $stmt5->execute();
            $stmt5->bind_result($idGl);
            while ($stmt5->fetch()) {
                $idGlasac = $idGl;
            }
            $stmt5->close();
            if ($idGlasac !== null) {
                // Ako postoji glasač sa ID-jem onda se ažuriraju i podaci o glasaču
                $sqlUpit6 = "UPDATE glasac SET ime = ?, prezime = ?, jmbg = ?, adresa = ?,  telefon = ?,
                                             biraliste = ?, email = ?, datum_rodj = ?
                           WHERE id = ?";
                $stmt6 = $conn->prepare($sqlUpit6);
                $stmt6->bind_param("ssssssssi", $ime, $prezime, $jmbg, $adresa, $telefon, $biraliste, $email, $datum_rodj, $idGlasac);
                $stmt6->execute();
                $stmt6->close();
            }

            $conn->commit(); // Commit the transaction
            $conn->autocommit(true); // Turn autocommit back on
            echo json_encode(["status" => "success", "message" => "Успешно ажуриран повереник!"]);
        } catch (Exception $e) {
            $conn->rollback(); // Rollback the transaction if an error occurred
            $conn->autocommit(true); // Turn autocommit back on
            $erro = "Error: " . $e->getMessage();
            echo json_encode(["status" => "error", "message" => "Неуспешно ажурирање података!"]);
            // Log the exception details
            $errorDetails = "Exception in insertPoverenik: " . $e->getMessage();
            error_log($errorDetails, 0); // Log the error to the PHP error log
        }
        exit();
    }

    public function deletePoverenik($id) {
        try {
            $conn = $this->conn;
            $conn->autocommit(false); // Start a transaction
            // Fetch the email based on the given ID
            $stmt = $conn->prepare("SELECT email, poverenik_nivo_id FROM poverenik WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->bind_result($email, $poverenikNivoId);
            $stmt->fetch();
            $stmt->close();

            // Check if poverenik_nivo_id is 1 or 2
            if ($poverenikNivoId == 1 || $poverenikNivoId == 2) {
                $conn->rollback();
                echo json_encode(["status" => "fail", "message" => "Не можете брисати поверенике нивоа Superadmin и Admin"]);
                return;
            }

            // Delete the poverenik with the given ID
            $stmt0 = $conn->prepare("DELETE FROM poverenik WHERE id = ?");
            $stmt0->bind_param("i", $id);
            $res0 = $stmt0->execute();
            $stmt0->close();

            // Delete from glasac if email exists
            $stmt1 = $conn->prepare("DELETE FROM glasac WHERE email = ?");
            $stmt1->bind_param("s", $email);
            $res1 = $stmt1->execute();
            $stmt1->close();

            // Commit or rollback based on the result
            if ($res0 && $res1) {
                $conn->commit();
                $conn->autocommit(true); // Turn autocommit back on
                echo json_encode(["status" => "success", "message" => "Успешно избрисан повереник!"]);
            } else {
                $conn->rollback();
                echo json_encode(["status" => "fail", "message" => "Неуспешно брисање!"]);
            }
        } catch (Exception $e) {
            echo json_encode(["status" => "error", "message" => "Грешка: " . $e->getMessage()]);
        }
    }

    /*     * *АДМИНИСТРАЦИЈА ОКРУГА** */

    public function slobodneOpstine() {
        $conn = $this->conn;
        try {
            $sqlUpit = "SELECT id, naziv_opstine FROM opstine
                        WHERE id NOT IN (SELECT pov.opstina_id FROM poverenistvo pov
                                                         INNER JOIN opstine op ON pov.opstina_id = op.id)";
            $stmt = $conn->prepare($sqlUpit);
            $stmt->execute();
            $stmt->bind_result($id, $naziv_opstine);
            $resultArray = array();

            while ($stmt->fetch()) {
                $resultArray[] = array('id' => $id, 'naziv_opstine' => $naziv_opstine);
            }
            echo json_encode(["status" => "success", "message" => "Успешно учитане општине", "niz" => $resultArray]);
        } catch (Exception $e) {
            echo json_encode(["status" => "error", "message" => "Грешка: " . $e->getMessage()]);
        }
    }

    public function ubaciOkrugIPoverenistva($okrug, $nizPov) {
        $conn = $this->conn;
        try {
            $sqlUpit = "INSERT INTO okruzi(naziv_regiona) VALUES (?)";
            $stmt = $conn->prepare($sqlUpit);

            $stmt->bind_param("s", $okrug);
            $stmt->execute();
            $stmt->close();
            $lastInsertedID = $conn->insert_id;

            foreach ($nizPov as $poverenistvo) {

                $value = $poverenistvo->value;

                $sqlPoverenistvo = "INSERT INTO poverenistvo(opstina_id, okrug_id) VALUES (?, ?)";
                $stmtPoverenistvo = $conn->prepare($sqlPoverenistvo);
                $stmtPoverenistvo->bind_param("ii", $value, $lastInsertedID);
                $stmtPoverenistvo->execute();
                $stmtPoverenistvo->close();
            }

            echo json_encode(["status" => "success", "message" => "Успешно сачуван округ"]);
        } catch (Exception $e) {
            echo json_encode(["status" => "error", "message" => "Грешка: " . $e->getMessage()]);
        }
    }

    public function deleteOkrug($id) {
        try {
            $conn = $this->conn;

            $sqlUpit = "DELETE FROM okruzi WHERE id = ?";
            $stmt = $conn->prepare($sqlUpit);
            $stmt->bind_param("i", $id);
            $res = $stmt->execute();
            if ($res) {
                echo json_encode(["status" => "success", "message" => "Успешно избрисан округ!"]);
            } else {
                echo json_encode(["status" => "fail", "message" => "Неуспешно брисање!"]);
            }
        } catch (Exception $e) {
            echo json_encode(["status" => "error", "message" => "Грешка: " . $e->getMessage()]);
        }
    }

}

//$test = new DBBroker();
//$test->deleteOkrug(30);
//$test->ubaciOkrugIPoverenistva("Тест округ");
//$test->slobodneOpstine();
//$test->updatePoverenik(3, "Катарина", "Роквић", "2343234532388", "Јована Дучића 8", "3532888", "Дом омладине 8", "dusko8.drljaca@consultech.rs", "02/07/1998");
//$test->getPoverenikById(3);
//$test->loadTablePov();
//$test->poverenistvaByOkrug("Подрињски");
//$test->loadOkrug();
//echo $test->createPoverenikLogin("Kanjiža", "Južnobački", 4);
//$test->insertPoverenik("Каћа", "Дрљача", "2343234532399", "Јована Дучића 7", "3532999", "Дом омладине2", "dusko.drljaca@consultech.rs", "02/07/1994", 4, "Бор", "Borski");
//$test->deleteGlasac(17);
//$test->existEmail("superadmin");
//$test->checkAndInsertEmail("contact@ddwebapps.com", "superadmin");
//$test->checkUsernameEmail("superadmin", "contact1@ddwebapps.com");
//$test->updatePassword("superadmin", "superadmin");
//$test->getUserIdByToken("99e9908a93446bc4a0401d4c330264b8051386201da31d9e198d9e07e4a6f1df");
//$test->checkUser("admin");
//$test->getUserLoginId("oo_MaliZvornik");
//$test->updateGlasac('Božana', "Ilić", "2345234523452", "Izmenja adresa", "9944", "Centar 2", "bozana.ilic@yahoo.com",
//        '04/04/1995', 1, "", "Мали Зворник");
//$test->getOpstineNosilacIme('Лозница');
//$test->getGlasacById(5);
//$test->getImeNosioca('Крупањ');
//$test->ucitaјNosioceTip();
//$test->loadTable();
//$test->checkEmail("dusko.drljaca@consultech.rs");
//$test->insertGlasac();
//$test->checkNosioce('Ада');
//$test->loadTowns();
//$test->checkTelefon("7778885");
//$test->checkJMBG("1234567891011");
//$test->checkLogin("MaliZvornik", "MaliZvornik");

