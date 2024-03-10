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
<body>

    <h6 class="pl-4 title-color ">Добродошли, <?php echo $_SESSION['username']; ?>!</h6>
    <hr class="border border-primary form_separator">
    <div class="navigacija">
        <h5 class="link-color"><a href="form.php">Унос гласача</a></h5>
        <h5 class="link-color"><a href="../controllers/logout.php">Одјавите се</a></h5>
        <hr class="border border-primary form_separator"> 
    </div>    

    <div class="container container-login p-2 text-center">       
        <h2 class="text-center title-color">АДМИНИСТРАЦИЈА</h2>
        <div class="row justify-content-center">  
            <div class="col-lg-6">
                <button type="button" class="btn btn-success btn_tabela mt-4 py-2 px-4"><a href="okrug.php">Администрација округа</a></button>
                <button type="button" class="btn btn-success btn_tabela mt-4 py-2 px-4"><a href="poverenik.php">Администрација повереника</a></button>
            </div>
        </div>          
    </div>

    <?php
    include 'footer.php';

    