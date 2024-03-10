<?php
include './before_header.php';
include './header.php';
?>



<title>Tabela</title>
</head>
<body onload="ucitajTabelu()">
    <h6 class="title-color">Добродошли, <?php echo $_SESSION['username']; ?>!</h6>
    <hr class="border border-primary form_separator">
    <div class="navigacija">  
        <h5 class="link-color"><a href="form.php">Унос гласача</a></h5>
        <h5 class="link-color"><a href="../controllers/logout.php">Одјавите се</a></h5>            
        <hr class="border border-primary form_separator">             
    </div>
    <div class="container-fluid container-login p-3">
        <h2 class="text-center title-color">Табела гласача</h2>
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-3 col-sm-5 mt-2" >
                        <!-- Повереништво -->                    
                        <select  id="opstine_tab" name="opstine_tab" class="form-control form_data">
                            <option value="0">Сва повереништва</option>
                        </select>     
                    </div>
                    <div class="col-lg-3 col-sm-5 mt-2">
                        <input type="text" id="datepicker" class="form-control" placeholder="Изаберите датум претраге...">                  
                    </div>
                    <div class="col-lg-3 col-sm-1 mt-3 text-end">
                        <label>Export:</label>
                    </div>
                    <div class="col-lg-3 col-sm-1 mt-3">
                        <div id="exportBtn" onclick="exportToExcell()"><i class="fas fa-file-excel"></i></div>                         
                    </div>
                </div>
                <div class="table-responsive mt-4">
                    <table class="table table-striped" id="tabela_glasaca">
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
                                <th>Датум уноса</th>
                                <th>Носилац гласова</th> 
                                <th>Име Носиоца</th> 
                            </tr>
                        </thead>
                        <tbody id="tabela_glasaca_body">

                        </tbody>
                    </table>
                    <!-- Модал за учитавање и измену података -->                   
                    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form id="editModalForm">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editModalLabel">Ажурирање података</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">                                   
                                        <!-- # -->
                                        <div class="row">                                           
                                            <div class="col-lg-8 col-sm-8">
                                                <input  class="form-control form_data" id="idModal" name="idModal" type="hidden" >     
                                            </div>                           
                                        </div>    
                                        <!-- Ime -->
                                        <div class="row">
                                            <div class="col-lg-4 col-sm-4  d-flex align-items-center justify-content-sm-end justify-content-center">
                                                <label class="title-color" for="imeModal" >Име:</label>
                                            </div>
                                            <div class="col-lg-8 col-sm-8">
                                                <input  class="form-control form_data" id="imeModal" name="imeModal" type="text" placeholder="Унесите ваше име..." pattern="^[A-Za-zА-Яа-яЁёЉљЊњЂђЋћЧчЏџЈјĐđŽžČčĆć\s]+$"   title="Можете користити само слова и празнине" required>     
                                            </div>                           
                                        </div>                                      
                                        <!-- Prezime -->                                        
                                        <div class="row">
                                            <div class="col-lg-4 col-sm-4  d-flex align-items-center justify-content-sm-end justify-content-center">
                                                <label class="title-color" for="prezimeModal">Презиме:</label>
                                            </div>
                                            <div class="col-lg-8 col-sm-8">
                                                <input  class="form-control form_data mt-2" id="prezimeModal" name="prezimeModal" type="text" placeholder="Унесите ваше презиме..." pattern="^[A-Za-zА-Яа-яЁёЉљЊњЂђЋћЧчЏџЈјĐđŽžČčĆć\s]+$"  required>
                                            </div>
                                        </div>
                                        <!-- Email-->                   
                                        <div class="row">
                                            <div class="col-lg-4 col-sm-4  d-flex align-items-center justify-content-sm-end justify-content-center">
                                                <label class="title-color" for="emailModal">Email:</label>
                                            </div>
                                            <div class="col-lg-8 col-sm-8">
                                                <input  class="form-control form_data mt-2" id="emailModal" name="emailModal" type="email" pattern="^[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,}$" placeholder="Унесите ваш email..." required>
                                                <div id="messageEmail" class="error"></div>
                                            </div>
                                        </div>                                                                                                              
                                        <!-- JMBG -->
                                        <div class="row">
                                            <div class="col-lg-4 col-sm-4  d-flex align-items-center justify-content-sm-end justify-content-center">
                                                <label class="title-color" for="jmbgModal">ЈМБГ:</label>
                                            </div>
                                            <div class="col-lg-8 col-sm-8">
                                                <input  class="form-control form_data mt-2" id="jmbgModal" name="jmbgModal" type="text" placeholder="Унесите ваш ЈМБГ (13 цифара)..."  pattern="\d{13}" title="Можете користити само brojeve - 13 цифара">
                                                <div id="messageJMBG" class="error"></div>
                                            </div>
                                        </div>                       
                                        <!-- Датум рођења -->                  
                                        <div class="row">
                                            <div class="col-lg-4 col-sm-4  d-flex align-items-center justify-content-sm-end justify-content-center">
                                                <label class="title-color" for="datum_rodjModal">Датум рођења:</label>
                                            </div>
                                            <div class="col-lg-8 col-sm-8">
                                                <input  class="form-control form_data mt-2" id="datum_rodjModal" name="datum_rodjModal"  type="text" placeholder="Унесите датум рођ. у формату 01/01/2001"  pattern="^(0[1-9]|1\d|2\d|3[01])/(0[1-9]|1[0-2])/\d{4}$" title="Можете користити само brojeve и /" >
                                            </div>
                                        </div>

                                        <!-- Telefon -->
                                        <div class="row">
                                            <div class="col-lg-4 col-sm-4  d-flex align-items-center justify-content-sm-end justify-content-center">
                                                <label class="title-color" for="telefonModal">Телефон:</label>
                                            </div>
                                            <div class="col-lg-8 col-sm-8">
                                                <input  class="form-control form_data mt-2" id="telefonModal" name="telefonModal"  type="text" placeholder="000/8888888"  pattern="^[0-9\s+\-\/]+$" title="Можете користити само бројеве, празнине, +, -, /" required>
                                                <div id="messageTel" class="error"></div>
                                            </div>
                                        </div>
                                        <!-- Adresa-->                   
                                        <div class="row">
                                            <div class="col-lg-4 col-sm-4  d-flex align-items-center justify-content-sm-end justify-content-center">
                                                <label class="title-color" for="adresaModal">Адреса:</label>
                                            </div>
                                            <div class="col-lg-8 col-sm-8">
                                                <input  class="form-control form_data mt-2" id="adresaModal" name="adresaModal" type="text" placeholder="Унесите вашу адресу..." required>
                                            </div>
                                        </div>
                                        <!-- Биралиште-->
                                        <div class="row">
                                            <div class="col-lg-4 col-sm-4  d-flex align-items-center justify-content-sm-end justify-content-center">
                                                <label class="title-color" for="biralisteModal">Бирачко место:</label>
                                            </div>
                                            <div class="col-lg-8 col-sm-8">
                                                <input  class="form-control form_data mt-2" id="biralisteModal" name="biralisteModal" type="text" placeholder="Унесите бирачко место..." required>
                                            </div>
                                        </div>                    
                                        <!-- Повереништво -->                       
                                        <div class="row">
                                            <div class="col-lg-4 col-sm-4  d-flex align-items-center justify-content-sm-end justify-content-center">
                                                <label class="title-color" for="opstinaModal">Повереништво:</label>
                                            </div>
                                            <div class="col-lg-8 col-sm-8">
                                                <select  id="mestoModal" name="opstinaModal" class="form-control form_data mt-2" onclick="ucitajOpstineEdit()">

                                                </select>   
                                            </div>
                                        </div>
                                        <!-- Носилац гласова -->
                                        <div class="row">
                                            <div class="col-lg-4 col-sm-4  d-flex align-items-center justify-content-sm-end justify-content-center">
                                                <label class="title-color" for="nosilac_glasovaModal">Носилац гласова:</label>
                                            </div>
                                            <div class="col-lg-8 col-sm-8">
                                                <select id="nosilac_glasovaModal" name="nosilac_glasovaModal" class="form-control form_data mt-2" onclick="removeDuplicateOptions(this)" >                                                                                                                     
                                                </select>
                                            </div>
                                        </div>      
                                        <!-- Име носиоца гласова -->
                                        <div class="row">
                                            <div class="col-lg-4 col-sm-4  d-flex align-items-center justify-content-sm-end justify-content-center">
                                                <label class="title-color" for="ime_nosioca_glasovaModal">Име носиоца гласова:</label>
                                            </div>
                                            <div class="col-lg-8 col-sm-8">
                                                <select id="ime_nosioca_glasovaModal" name="ime_nosioca_glasovaModal" class="form-control form_data mt-2">                                            
                                                </select>
                                                <span id="messageING" class="error"></span>
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


    <?php
    include 'footer.php';

    