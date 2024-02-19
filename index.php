<?php

include 'views/header.php';
?>    
<title>Login page</title>

</head>
<body>

    <div class="container container-login p-5 text-center">
        <div class="content"></div>
        <h2 class="text-center">Логовање</h2>
        <div class="row justify-content-center">
            <div class="col-lg-4 col-sm-6">                      
                <form  id="loginForm" >                       
                    <div class="form-group mt-5">
                        <label for="username" >Корисничко име:</label>
                        <input type="text" class="form-control form_data mt-2" id="username" placeholder="Enter username" name="username" required>
                    </div>
                    <div class="form-group mt-2">
                        <label for="password">Шифра:</label>
                        <input type="password" class="form-control form_data mt-2" id="password" placeholder="Enter password" name="password" required>

                    </div>
                    <div class="form-group mt-2 login-fg-pass">
                        <a href="#" id="forgotPasswordBtn" data-bs-toggle="modal" data-bs-target="#usernameVerificationModal">Заборавили сте шифру?</a>
                    </div>

                    <div id="message" class="error"></div>
                    <button  type="submit" id="submit"  class="btn btn-primary mt-5 mb-5">Улогуј се</button>   
                </form>
               
                <!-- Provera korisničkog imena Modal -->
                <div class="modal fade" id="usernameVerificationModal" tabindex="-1" aria-labelledby="usernameVerificationModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="usernameVerificationModalLabel">Provera korisničkog imena</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">                                
                                <form id="usernameVerificationForm">
                                    <div class="mb-3">
                                        <label for="usernameInput" class="form-label">Korisničko ime:</label>
                                        <input type="text" class="form-control" id="usernameInput" name="username" required placeholder="Унесите ваше корисничко име">
                                    </div>
                                    <span id="checkUsernameMessage" class="error"></span>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Поништи</button>
                                <button type="button" class="btn btn-primary" id="verifyUsernameBtn">Провери</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Ubacivanje Email ako ga nema u bazi Modal -->
                <div class="modal fade" id="emailInsertModal" tabindex="-1" aria-labelledby="emailInsertModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="emailInsertModalLabel">Ubacivanje Email-a</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">                                
                                <form id="emailInsertForm">
                                    <div class="form-group mt-2">
                                        <div class="row">
                                            <div class="col-lg-4 col-sm-4  d-flex align-items-center justify-content-sm-end justify-content-center">
                                                <label class="title-color" for="emailInsert">Email:</label>
                                            </div>
                                            <div class="col-lg-8 col-sm-8">
                                                <input  class="form-control form_data mt-2" id="emailInsert" name="emailInsert"  type="email" pattern="^[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,}$" placeholder="Унесите ваш email..." required>
                                                <div id="messageInsertEmail" class="error"></div>
                                            </div>
                                        </div>                                                                                                              
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Поништи</button>
                                <button type="button" class="btn btn-primary" id="emailInsertBtn">Потврди</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!--Provera Email-a Modal -->
                <div class="modal fade" id="forgotPasswordModal" tabindex="-1" aria-labelledby="forgotPasswordModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="forgotPasswordModalLabel">Заборављена шифра</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p>Унесите мејл на који ће вам бити послан линк за ресетовање шифре.</p>
                                <form id="forgotPasswordForm">
                                    <div class="mb-3">
                                        <label for="emailInput" class="form-label">Email адреса:</label>
                                        <input type="email" class="form-control" id="emailInput" name="email" required>
                                        <span id="emailMessageError" class="error"></span>
                                        <span id="emailMessageSuccess" class="success"></span>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Поништи</button>
                                <button type="button" class="btn btn-primary" id="sendVerificationBtn">Пошаљи верификациони линк</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>            
    </div>
</div>

<?php

include 'views/footer.php';
