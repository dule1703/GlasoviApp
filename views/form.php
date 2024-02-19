<?php
include './before_header.php';
include './header.php';
?>


<title>Forma za unos</title>
</head>
<body onload="ucitajOpstine()">

    <h6 class="pl-4 title-color ">Добродошли, <?php echo $_SESSION['username']; ?>!</h6>
    <hr class="border border-primary form_separator">
    <div class="navigacija">
        <h5 class="link-color"><a href="../controllers/logout.php">Одјавите се</a></h5>
        <hr class="border border-primary form_separator"> 
    </div>    

    <div class="container container-login p-2 text-center">       
            <h2 class="text-center title-color">Унос гласача</h2>
            <div class="row justify-content-center">                
                <div class="col-lg-6 col-xs-12">                      
                    <form  id="unosForm" >  

                        <!-- Ime -->
                        <div class="form-group mt-5">
                            <div class="row">
                                <div class="col-lg-4 col-sm-4  d-flex align-items-center justify-content-sm-end justify-content-center">
                                    <label class="title-color" for="ime" >Име<span class="error">*</span>:</label>
                                </div>
                                <div class="col-lg-8 col-sm-8">
                                    <input  class="form-control form_data" id="ime" name="ime" type="text" placeholder="Унесите ваше име..." pattern="^[A-Za-zА-Яа-яЁёЉљЊњЂђЋћЧчЏџЈјĐđŽžČčĆć\s]+$"   title="Можете користити само слова и празнине" required>     
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
                                    <input  class="form-control form_data mt-2" id="prezime" name="prezime" type="text" placeholder="Унесите ваше презиме..." pattern="^[A-Za-zА-Яа-яЁёЉљЊњЂђЋћЧчЏџЈјĐđŽžČčĆć\s]+$"  required>
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
                                    <input  class="form-control form_data mt-2" id="jmbg" name="jmbg" onkeyup="proveriJMBG()" type="text" placeholder="Унесите ваш ЈМБГ (13 цифара)..."  pattern="\d{13}" title="Можете користити само brojeve - 13 цифара">
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
                                    <input  class="form-control form_data mt-2" id="telefon" name="telefon" onkeyup="proveriTel()"  type="text" placeholder="000/8888888"  pattern="^[0-9\s+\-\/]+$" title="Можете користити само бројеве, празнине, +, -, /" required>
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
                        <!-- Повереништво -->                        
                        <div class="form-group mt-2">
                            <div class="row">
                                <div class="col-lg-4 col-sm-4  d-flex align-items-center justify-content-sm-end justify-content-center">
                                    <label class="title-color" for="opstina">Повереништво<span class="error">*</span>:</label>
                                </div>
                                <div class="col-lg-8 col-sm-8">
                                    <select  id="mesto" name="opstina" class="form-control form_data mt-2"  onchange="proveriNosioce()">
                                        <option value="0">Изаберите повереништво...</option>
                                    </select>   
                                </div>
                            </div>
                        </div>

                        <!-- Носилац гласова -->
                        <div class="form-group mt-2">
                            <div class="row">
                                <div class="col-lg-4 col-sm-4  d-flex align-items-center justify-content-sm-end justify-content-center">
                                    <label class="title-color" for="nosilac_glasova">Носилац гласова:</label>
                                </div>
                                <div class="col-lg-8 col-sm-8">
                                    <select id="nosilac_glasova" name="nosilac_glasova" class="form-control form_data mt-2" onchange="imeNosiocaGlasova()"  disabled >                                    
                                        <option value="1">Не, немам носиоца</option>
                                        <option value="2">Ја сам носилац</option>                                     
                                    </select>
                                </div>
                            </div>       
                        </div>                       
                        <!-- Име носиоца гласова -->
                        <div class="form-group mt-2">
                            <div class="row">
                                <div class="col-lg-4 col-sm-4  d-flex align-items-center justify-content-sm-end justify-content-center">
                                    <label class="title-color" for="ime_nosioca_glasova">Име носиоца гласова:</label>
                                </div>
                                <div class="col-lg-8 col-sm-8">
                                    <select id="ime_nosioca_glasova" name="ime_nosioca_glasova" class="form-control form_data mt-2" disabled>                                    
                                        <option value="0">Изаберите носиоца...</option>

                                    </select>
                                    <span id="messageING" class="error"></span>
                                </div>
                            </div>
                        </div> 
                        <!-- Модал за поруку -->
                        <div class="modal" id="customAlert" tabindex="-1" role="dialog">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-body">
                                        <h4 id="alertMessage" class="error"></h4>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-primary" id="closeAlert">У реду</button>
                                    </div>
                                </div>
                            </div>
                        </div>



                        <!-- Дугме за потврду уноса -->
                        <div id="messageUnos"></div>
                        <div class="btn_tabela_sep">
                            <button  type="submit" id="submitForm" class="btn btn-primary mt-5 mb-2"  >Потврди унос</button> 
                            <hr class="border border-primary form_separator">
                        </div>

                    </form>

                </div>
            </div>          

            <button type="button" class="btn btn-success btn_tabela mt-4 py-2 px-4"><a href="tabela.php">Табела гласача</a></button>
            <button type="button" <?php
               $username = $_SESSION["username"];
               if($username !== "Superadmin"){
                   echo 'style = display:none';
               }
            ?> class="btn btn-info btn_tabela mt-4 ms-2 py-2 px-4"><a href="admin.php">Администрација</a></button>             

        
    </div>

    <?php
    include 'footer.php';

    