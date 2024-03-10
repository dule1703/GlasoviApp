<?php
session_start();
function logout() {
    
    $_SESSION = array();
   
    session_destroy();
   
    header("Location: ../index.php");
    exit; 
}

logout();