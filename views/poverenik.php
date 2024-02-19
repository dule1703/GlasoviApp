<?php
include './before_header.php';
$username = $_SESSION["username"];
if ($username !== "Superadmin") {
    header('Location: ../index.php');
    exit;
}
include './header.php';
?>


<title>Admin</title>
</head>
<body onload="ucitajOkruge();">

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
                <a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">КРЕИРАЊЕ ПОВЕРЕНИКА</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="profile-tab" onclick="ucitajTabeluPov()" data-bs-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">АЖУРИРАЊЕ И БРИСАЊЕ ПОВЕРЕНИКА</a>
            </li>           
        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">               
                <div class="row justify-content-center">                
                    <div class="col-lg-6 col-xs-12">                      
                        <form  id="unosPoverenikForm" >  

                            <!-- Ime -->
                            <div class="form-group mt-5">
                                <div class="row">
                                    <div class="col-lg-4 col-sm-4  d-flex align-items-center justify-content-sm-end justify-content-center">
                                        <label class="title-color" for="ime" >Име<span class="error">*</span>:</label>
                                    </div>
                                    <div class="col-lg-8 col-sm-8">
                                        <input  class="form-control form_data" id="ime" name="ime" type="text" placeholder="Унесите име повереника..." pattern="^[A-Za-zА-Яа-яЁёЉљЊњЂђЋћЧчЏџЈјĐđŽžČčĆć\s]+$"   title="Можете користити само слова и празнине" required>     
                                    </div>                           
                                </div>
                            </div> 

                            <!-- Prezime -->
                            <div class="form-group mt-2">
                                <div class="row">
                                    <div class="col-lg-4 col-sm-4  d-flex align-items-center justify-content-sm-end justify-content-center">
                                        <label class="title-color" for="prezime">Презиме<span class="error">*</span>:</label>
                                    </div>
                                    <div class="col-lg-8 col-sm-8">
                                        <input  class="form-control form_data mt-2" id="prezime" name="prezime" type="text" placeholder="Унесите презиме повереника..." pattern="^[A-Za-zА-Яа-яЁёЉљЊњЂђЋћЧчЏџЈјĐđŽžČčĆć\s]+$"  required>
                                    </div>
                                </div>
                            </div>    
                            <!-- Email-->                    
                            <div class="form-group mt-2">
                                <div class="row">
                                    <div class="col-lg-4 col-sm-4  d-flex align-items-center justify-content-sm-end justify-content-center">
                                        <label class="title-color" for="email">Email<span class="error">*</span>:</label>
                                    </div>
                                    <div class="col-lg-8 col-sm-8">
                                        <input  class="form-control form_data mt-2" id="email" name="email" onkeyup="proveriEmail()" type="email" pattern="^[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,}$" placeholder="Унесите ваш email..." required>
                                        <div id="messageEmail" class="error"></div>
                                    </div>
                                </div>                                                                                                              
                            </div>
                            <!-- JMBG -->
                            <div class="form-group mt-2">
                                <div class="row">
                                    <div class="col-lg-4 col-sm-4  d-flex align-items-center justify-content-sm-end justify-content-center">
                                        <label class="title-color" for="jmbg">ЈМБГ:</label>
                                    </div>
                                    <div class="col-lg-8 col-sm-8">
                                        <input  class="form-control form_data mt-2" id="jmbg" name="jmbg"  type="text" placeholder="Унесите ваш ЈМБГ (13 цифара)..."  pattern="\d{13}" title="Можете користити само brojeve - 13 цифара">
                                        <div id="messageJMBG" class="error"></div>
                                    </div>
                                </div>                        
                            </div> 
                            <!-- Датум рођења -->                   

                            <div class="form-group mt-2">
                                <div class="row">
                                    <div class="col-lg-4 col-sm-4  d-flex align-items-center justify-content-sm-end justify-content-center">
                                        <label class="title-color" for="datum_rodj">Датум рођења:</label>
                                    </div>
                                    <div class="col-lg-8 col-sm-8">
                                        <input  class="form-control form_data mt-2" id="datum_rodj" name="datum_rodj"  type="text" placeholder="Унесите датум рођ. у формату 01/01/2001"  pattern="^(0[1-9]|1\d|2\d|3[01])/(0[1-9]|1[0-2])/\d{4}$" title="Можете користити само brojeve и /" >
                                    </div>
                                </div>
                            </div>                                                                                                                               
                            <!-- Telefon -->
                            <div class="form-group mt-2">
                                <div class="row">
                                    <div class="col-lg-4 col-sm-4  d-flex align-items-center justify-content-sm-end justify-content-center">
                                        <label class="title-color" for="telefon">Телефон<span class="error">*</span>:</label>
                                    </div>
                                    <div class="col-lg-8 col-sm-8">
                                        <input  class="form-control form_data mt-2" id="telefon" name="telefon"  type="text" placeholder="000/8888888"  pattern="^[0-9\s+\-\/]+$" title="Можете користити само бројеве, празнине, +, -, /" required>
                                        <div id="messageTel" class="error"></div>
                                    </div>
                                </div>
                            </div> 
                            <!-- Adresa-->                    
                            <div class="form-group mt-2">
                                <div class="row">
                                    <div class="col-lg-4 col-sm-4  d-flex align-items-center justify-content-sm-end justify-content-center">
                                        <label class="title-color" for="adresa">Адреса<span class="error">*</span>:</label>
                                    </div>
                                    <div class="col-lg-8 col-sm-8">
                                        <input  class="form-control form_data mt-2" id="adresa" name="adresa" type="text" placeholder="Унесите вашу адресу..." required>
                                    </div>
                                </div>
                            </div> 
                            <!-- Биралиште-->
                            <div class="form-group mt-2">
                                <div class="row">
                                    <div class="col-lg-4 col-sm-4  d-flex align-items-center justify-content-sm-end justify-content-center">
                                        <label class="title-color" for="biraliste">Бирачко место<span class="error">*</span>:</label>
                                    </div>
                                    <div class="col-lg-8 col-sm-8">
                                        <input  class="form-control form_data mt-2" id="biraliste" name="biraliste" type="text" placeholder="Унесите бирачко место..." required>
                                    </div>
                                </div>                      
                            </div>
                            <!-- Округ -->                        
                            <div class="form-group mt-2">
                                <div class="row">
                                    <div class="col-lg-4 col-sm-4  d-flex align-items-center justify-content-sm-end justify-content-center">
                                        <label class="title-color" for="okrug">Округ<span class="error">*</span>:</label>
                                    </div>
                                    <div class="col-lg-8 col-sm-8">
                                        <select  id="okrug" name="okrug" class="form-control form_data mt-2">
                                            <option value="0">Изаберите округ...</option>
                                        </select>   
                                    </div>
                                </div>
                            </div>
                            <!-- Повереништво -->                        
                            <div class="form-group mt-2">
                                <div class="row">
                                    <div class="col-lg-4 col-sm-4  d-flex align-items-center justify-content-sm-end justify-content-center">
                                        <label class="title-color" for="poverenistvo">Повереништво<span class="error">*</span>:</label>
                                    </div>
                                    <div class="col-lg-8 col-sm-8">
                                        <select  id="poverenistvo" name="poverenistvo" class="form-control form_data mt-2">
                                            <option value="0">Изаберите повереништво...</option>
                                        </select>   
                                    </div>
                                </div>
                            </div>

                            <!-- Ниво повереника -->
                            <div class="form-group mt-2">
                                <div class="row">
                                    <div class="col-lg-4 col-sm-4  d-flex align-items-center justify-content-sm-end justify-content-center">
                                        <label class="title-color" for="poverenik_nivo">Ниво повереника<span class="error">*</span>:</label>
                                    </div>
                                    <div class="col-lg-8 col-sm-8">
                                        <select id="poverenik_nivo" name="poverenik_nivo" class="form-control form_data mt-2">                                                                              
                                            <option value="3">Општински повереник</option> 
                                            <option value="4">Окружни повереник</option> 
                                        </select>
                                    </div>
                                </div>       
                            </div>                      

                            <div id="messagePoverenikUnos" style="text-align: center"></div>
                            <!-- Дугме за потврду уноса -->
                            <div class="btn_tabela_sep">
                                <button  type="submit" id="submitPoverenik" class="btn btn-primary mt-5 mb-2"  >Потврди унос</button> 
                                <hr class="border border-primary form_separator">
                            </div>


                        </form>
                        <div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="confirmationModalLabel">Потврда</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        Да ли сте сигурни да желите ове податке унети?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" id="confirmButton" class="btn btn-primary">Потврди</button>
                                        <button type="button" id="rejectButton" class="btn btn-secondary" data-bs-dismiss="modal">Затвори</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>         


            </div>


            <!-- АЖУРИРАЊЕ И БРИСАЊЕ ПОДАТАКА -->
            <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">               
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-3 col-sm-5 mt-2" >
                                <!-- Повереништво -->                    
                                <select  id="opstine_tab" name="opstine_tab" class="form-control">
                                    <option value="0">Сви повереници</option>
                                </select>     
                            </div>                            
                            <div class="col-lg-3 col-sm-1 mt-3 text-end">
                                <label>Export:</label>
                            </div>
                            <div class="col-lg-3 col-sm-1 mt-3">
                                <div id="exportBtn" onclick="exportToExcell()"><i class="fas fa-file-excel"></i></div>                         
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="table-responsive mt-4">
                                <table class="table table-striped" id="tabela_poverenika">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>РБ</th>
                                            <th>Име</th>
                                            <th>Презиме</th>
                                            <th>Email</th>
                                            <th>ЈМБГ</th>
                                            <th>Датум рођења</th>
                                            <th>Адреса</th>
                                            <th>Повереништво</th>
                                            <th>Бирачко место</th>
                                            <th>Округ</th>
                                            <th>Телефон</th>
                                            <th>Ниво повереника</th>                                        
                                        </tr>
                                    </thead>
                                    <tbody id="tabela_pov_body">

                                    </tbody>
                                </table>
                                <!-- Модал за учитавање и измену података -->                   
                                <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form id="editModalFormPoverenik">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editModalLabel">Ажурирање података</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">                                   
                                                    <!-- # -->
                                                    <div class="row">                                           
                                                        <div class="col-lg-8 col-sm-8">
                                                            <input  class="form-control" id="idModal" name="idModal" type="hidden" >     
                                                        </div>                           
                                                    </div>    
                                                    <!-- Ime -->
                                                    <div class="row">
                                                        <div class="col-lg-4 col-sm-4  d-flex align-items-center justify-content-sm-end justify-content-center">
                                                            <label class="title-color" for="imeModal" >Име:</label>
                                                        </div>
                                                        <div class="col-lg-8 col-sm-8">
                                                            <input  class="form-control" id="imeModal" name="imeModal" type="text" placeholder="Унесите ваше име..." pattern="^[A-Za-zА-Яа-яЁёЉљЊњЂђЋћЧчЏџЈјĐđŽžČčĆć\s]+$"   title="Можете користити само слова и празнине" required>     
                                                        </div>                           
                                                    </div>                                      
                                                    <!-- Prezime -->                                        
                                                    <div class="row">
                                                        <div class="col-lg-4 col-sm-4  d-flex align-items-center justify-content-sm-end justify-content-center">
                                                            <label class="title-color" for="prezimeModal">Презиме:</label>
                                                        </div>
                                                        <div class="col-lg-8 col-sm-8">
                                                            <input  class="form-control mt-2" id="prezimeModal" name="prezimeModal" type="text" placeholder="Унесите ваше презиме..." pattern="^[A-Za-zА-Яа-яЁёЉљЊњЂђЋћЧчЏџЈјĐđŽžČčĆć\s]+$"  required>
                                                        </div>
                                                    </div>
                                                    <!-- Email-->                   
                                                    <div class="row">
                                                        <div class="col-lg-4 col-sm-4  d-flex align-items-center justify-content-sm-end justify-content-center">
                                                            <label class="title-color" for="emailModal">Email:</label>
                                                        </div>
                                                        <div class="col-lg-8 col-sm-8">
                                                            <input  class="form-control mt-2" id="emailModal" name="emailModal" type="email" pattern="^[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,}$" placeholder="Унесите ваш email..." required>
                                                            <div id="messageEmail" class="error"></div>
                                                        </div>
                                                    </div>                                                                                                              
                                                    <!-- JMBG -->
                                                    <div class="row">
                                                        <div class="col-lg-4 col-sm-4  d-flex align-items-center justify-content-sm-end justify-content-center">
                                                            <label class="title-color" for="jmbgModal">ЈМБГ:</label>
                                                        </div>
                                                        <div class="col-lg-8 col-sm-8">
                                                            <input  class="form-control mt-2" id="jmbgModal" name="jmbgModal" type="text" placeholder="Унесите ваш ЈМБГ (13 цифара)..."  pattern="\d{13}" title="Можете користити само brojeve - 13 цифара">
                                                            <div id="messageJMBG" class="error"></div>
                                                        </div>
                                                    </div>                       
                                                    <!-- Датум рођења -->                  
                                                    <div class="row">
                                                        <div class="col-lg-4 col-sm-4  d-flex align-items-center justify-content-sm-end justify-content-center">
                                                            <label class="title-color" for="datum_rodjModal">Датум рођења:</label>
                                                        </div>
                                                        <div class="col-lg-8 col-sm-8">
                                                            <input  class="form-control mt-2" id="datum_rodjModal" name="datum_rodjModal"  type="text" placeholder="Унесите датум рођ. у формату 01/01/2001"  pattern="^(0[1-9]|1\d|2\d|3[01])/(0[1-9]|1[0-2])/\d{4}$" title="Можете користити само brojeve и /" >
                                                        </div>
                                                    </div>

                                                    <!-- Telefon -->
                                                    <div class="row">
                                                        <div class="col-lg-4 col-sm-4  d-flex align-items-center justify-content-sm-end justify-content-center">
                                                            <label class="title-color" for="telefonModal">Телефон:</label>
                                                        </div>
                                                        <div class="col-lg-8 col-sm-8">
                                                            <input  class="form-control mt-2" id="telefonModal" name="telefonModal"  type="text" placeholder="000/8888888"  pattern="^[0-9\s+\-\/]+$" title="Можете користити само бројеве, празнине, +, -, /" required>
                                                            <div id="messageTel" class="error"></div>
                                                        </div>
                                                    </div>
                                                    <!-- Adresa-->                   
                                                    <div class="row">
                                                        <div class="col-lg-4 col-sm-4  d-flex align-items-center justify-content-sm-end justify-content-center">
                                                            <label class="title-color" for="adresaModal">Адреса:</label>
                                                        </div>
                                                        <div class="col-lg-8 col-sm-8">
                                                            <input  class="form-control mt-2" id="adresaModal" name="adresaModal" type="text" placeholder="Унесите вашу адресу..." required>
                                                        </div>
                                                    </div>
                                                    <!-- Биралиште-->
                                                    <div class="row">
                                                        <div class="col-lg-4 col-sm-4  d-flex align-items-center justify-content-sm-end justify-content-center">
                                                            <label class="title-color" for="biralisteModal">Бирачко место:</label>
                                                        </div>
                                                        <div class="col-lg-8 col-sm-8">
                                                            <input  class="form-control mt-2" id="biralisteModal" name="biralisteModal" type="text" placeholder="Унесите бирачко место..." required>
                                                        </div>
                                                    </div>       
                                                    <!-- Округ -->                        
                                                    <div class="form-group mt-2">
                                                        <div class="row">
                                                            <div class="col-lg-4 col-sm-4  d-flex align-items-center justify-content-sm-end justify-content-center">
                                                                <label class="title-color" for="okrugModal">Округ:</label>
                                                            </div>
                                                            <div class="col-lg-8 col-sm-8">
                                                                <input  class="form-control mt-2" id="okrugModal" name="okrugModal" disabled>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- Повереништво -->                        
                                                    <div class="form-group mt-2">
                                                        <div class="row">
                                                            <div class="col-lg-4 col-sm-4  d-flex align-items-center justify-content-sm-end justify-content-center">
                                                                <label class="title-color" for="poverenistvoModal">Повереништво:</label>
                                                            </div>
                                                            <div class="col-lg-8 col-sm-8">
                                                                <input  class="form-control mt-2" id="poverenistvoModal" name="poverenistvoModal" disabled>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Ниво повереника -->
                                                    <div class="form-group mt-2">
                                                        <div class="row">
                                                            <div class="col-lg-4 col-sm-4  d-flex align-items-center justify-content-sm-end justify-content-center">
                                                                <label class="title-color" for="nivoPoverenikaModal">Ниво повереника:</label>
                                                            </div>
                                                            <div class="col-lg-8 col-sm-8">
                                                                <input  class="form-control mt-2" id="nivoPoverenikaModal" name="nivoPoverenikaModal" disabled>
                                                            </div>
                                                        </div>       
                                                    </div>   
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Поништи</button>
                                                    <button type="submit" class="btn btn-primary" id="saveChangesBtn">Сачувај</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php
    include 'footer.php';

    