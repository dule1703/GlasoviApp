<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../../dbbroker/DBBroker.php';

// Verifikacija tokena kada korisnik klikne na link za verifikaciju
if (isset($_GET['token'])) {
    $token = $_GET['token'];
    $_SESSION["checkUser"] = str_replace('+', ' ', $_GET['username']);
    
    $newDBB = new DBBroker();
    // Check if the token exists and is valid in the database
    $isValidToken = $newDBB->validateVerificationToken($token);

    if ($isValidToken) {
        ?>
        <!DOCTYPE html>

        <html>
            <head>
                <meta charset="UTF-8">
                <meta http-equiv="X-UA-Compatible" content="IE=edge">
                <meta name="viewport" content="width=device-width, initial-scale=1">                
                <link href="../../libs/css/style.css" rel="stylesheet" type="text/css"/>
                <link rel="icon" href="../libs/images/sg_favicon.png" type="image/x-icon">          
                <!-- Datepicker css -->
                <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">        
                <!-- Bootstrap css and Font Awesome -->
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet">
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
                <!-- jQuery js -->
                <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script> 
                <!-- Export to Excell and PDF js -->
                <script src="https://unpkg.com/xlsx/dist/xlsx.full.min.js"></script>
                <script src="https://unpkg.com/file-saver/dist/FileSaver.min.js"></script>        
                <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
                <!-- Datepicker js -->
                <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>     
                <!-- Bootstrap 5 js -->
                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.min.js"></script> 
                <title>Ресет шифре</title>
            </head>
            <body>
                <div class="container mt-5">
                    <div class="row justify-content-center">
                        <div class="col-lg-6 col-md-8">
                            <h2 class="text-center mb-4">Ресетујте вашу шифру</h2>
                            <form>
                                <input id="token" type="hidden" name="token" value="<?php echo $token; ?>">
                                <div class="mb-3">
                                    <label for="newPassword" class="form-label">Нова шифра:</label>
                                    <input type="password" id="newPassword" name="newPassword" class="form-control" required>
                                </div>                                
                            </form>
                            <div class="d-grid">
                                <button type="button" class="btn btn-primary" id="resetPassBtn">Ресетујте шифру</button>
                            </div>
                            <div id="tokenErrMsg" class="message"></div>
                            <div id="tokenSuccMsg" class="message"></div>
                        </div>
                    </div>
                </div>
                <script src="../../libs/js/asyncCtrlJS.js" type="text/javascript"></script>            
            </body>
        </html>

        <?php
        exit;
    }
}
//Pogrešan ili istekao token, prikazuje se tekst greške
?>
<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">                
        <link href="../../libs/css/style.css" rel="stylesheet" type="text/css"/>
        <link rel="icon" href="../libs/images/sg_favicon.png" type="image/x-icon">          
        <!-- Datepicker css -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">        
        <!-- Bootstrap css and Font Awesome -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
        <!-- jQuery js -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script> 
        <!-- Export to Excell and PDF js -->
        <script src="https://unpkg.com/xlsx/dist/xlsx.full.min.js"></script>
        <script src="https://unpkg.com/file-saver/dist/FileSaver.min.js"></script>        
        <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
        <!-- Datepicker js -->
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>     
        <!-- Bootstrap 5 js -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.min.js"></script> 
        <title>Error</title>

    </head>
    <body>
        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-8">
                    <h2 class="text-center mb-4">Токен је неважећи или је истекао</h2>
                    <p class="text-center">Линк за верификацију је неважећи или је истекао. Пробајте поново да ресетујете шифру.</p>
                </div>
            </div>
        </div>
    </body>
</html>


