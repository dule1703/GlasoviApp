<?php
include './before_header.php';
$username = $_SESSION["username"];
if ($username !== "Superadmin") {
    header('Location: ../index.php');
    exit;
}
include './header.php';
?>

<!-- Include Bootstrap Multiselect CSS and JS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/css/bootstrap-multiselect.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.15/js/bootstrap-multiselect.min.js"></script>


<title>Admin okrug</title>
</head>
<body onload="ucitajSlobodneOpstineAdminOkrug(); ucitajOkruge()">

    <h6 class="pl-4 title-color ">Добродошли, <?php echo $_SESSION['username']; ?>!</h6>
    <hr class="border border-primary form_separator">
    <div class="navigacija">
        <h5 class="link-color"><a href="admin.php">Администрација</a></h5>
        <h5 class="link-color"><a href="../controllers/logout.php">Одјавите се</a></h5> 
        <hr class="border border-primary form_separator"> 
    </div>    

    <div class="container-fluid mt-5">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">КРЕИРАЊЕ ОКРУГА</a>
            </li>            
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="delete-tab"  data-bs-toggle="tab" href="#delete" role="tab" aria-controls="delete" aria-selected="false">БРИСАЊЕ ОКРУГА</a>
            </li> 
        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">               
                <div class="row justify-content-center">                
                    <div class="col-lg-6 col-xs-12">                      
                        <form  id="unosOkrugForm" >  

                            <!-- Унос назива округа -->
                            <div class="form-group mt-5">
                                <div class="row">
                                    <div class="col-lg-4 col-sm-4  d-flex align-items-center justify-content-sm-end justify-content-center">
                                        <label class="title-color" for="naziv_okruga">Назив<span class="error">*</span>:</label>
                                    </div>
                                    <div class="col-lg-8 col-sm-8">
                                        <input  class="form-control form_data" id="naziv_okruga" name="naziv_okruga" type="text" placeholder="Унесите назив округа..." pattern="^[A-Za-zА-Яа-яЁёЉљЊњЂђЋћЧчЏџЈјĐđŽžČčĆć\s]+$"   title="Можете користити само слова и празнине" required>     
                                    </div>                            
                                </div>
                            </div> 

                            <!-- Повереништво -->                        
                            <div class="form-group mt-2">
                                <div class="row">
                                    <div class="col-lg-4 col-sm-4  d-flex align-items-center justify-content-sm-end justify-content-center">
                                        <label class="title-color" for="poverenistvoAdminOkrug">Повереништва<span class="error">*</span>:</label>
                                    </div>
                                    <div class="col-lg-8 col-sm-8">
                                        <select  id="poverenistvoAdminOkrug" name="poverenistvoAdminOkrug" multiple class="form-control form_data mt-2">

                                        </select>                                          
                                    </div>

                                </div>
                                <div class="row mt-3">
                                    <div class="col-lg-4 col-sm-4  d-flex">

                                    </div>
                                    <div class="col-lg-8 col-sm-8">
                                        <span id="output" class="mt-2"></span>
                                    </div>
                                </div>
                            </div>                          
                            <div id="messagePoverenistvoUnos" style="text-align: center"></div>
                            <!-- Дугме за потврду уноса -->
                            <div class="btn_tabela_sep">
                                <button  type="submit" id="submitPoverenistvo" class="btn btn-primary mt-5 mb-2"  >Потврди унос</button> 
                                <hr class="border border-primary form_separator">
                            </div>
                        </form>
                        <div class="modal fade" id="confirmationModalPoverenistvo" tabindex="-1" aria-labelledby="confirmationModalPoverenistvo" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="confirmationModalPoverenistvo">Потврда</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        Да ли сте сигурни да желите ове податке унети?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" id="confirmButtonPov" class="btn btn-primary">Потврди</button>
                                        <button type="button" id="rejectButtonPov" class="btn btn-secondary" data-bs-dismiss="modal">Затвори</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>         


            </div>

 
            <!-- БРИСАЊЕ ПОДАТАКА -->
            <div class="tab-pane fade" id="delete" role="tabpanel" aria-labelledby="delete-tab">               
                <div class="row justify-content-center">                
                    <div class="col-lg-6 col-xs-12">                      
                        <form  id="deleteOkrugForm" >  

                            <!-- Унос назива округа -->
                            <div class="form-group mt-5">
                                <div class="row">
                                    <div class="col-lg-4 col-sm-4  d-flex align-items-center justify-content-sm-end justify-content-center">
                                        <label class="title-color" for="naziv_okruga_brisanje">Округ:</label>
                                    </div>                                   
                                    <div class="col-lg-8 col-sm-8">
                                        <select  id="okrugDelete" name="okrugDelete" class="form-control form_data mt-2">
                                            <option value="0">Изаберите округ за брисање...</option>
                                        </select>                                      
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4 col-sm-4"></div>
                                        <div class="col-lg-4 col-sm-4">
                                            <button id="deleteOkrugBtn" class="btn btn-danger mt-5 mb-2">Избришите округ</button>
                                        </div>
                                        <div class="col-lg-4 col-sm-4"></div>
                                    </div>
                                </div>  
                        </form>

                    </div>
                </div>   
            </div>
        </div>
    </div>

    <?php
    include 'footer.php';

    