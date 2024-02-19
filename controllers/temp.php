<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// Example usage:
header('Content-Type: text/html; charset=utf-8'); // Set character encoding to UTF-8


function latinToCyrillic($text) {
    $latinChars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZŠšĐđŽžČčĆć';
    $cyrillicChars = 'абцдефгхијклмнопрстуввссджџззччћћ';

    return strtr($text, $latinChars, $cyrillicChars);
}

function cyrillicToLatin($text) {
    $latinChars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZŠšĐđŽžČčĆć';
    $cyrillicChars = 'абцдефгхијклмнопрстуввссджџззччћћ';

    return strtr($text, $cyrillicChars, $latinChars);
}



$text = "Južnobački, Mačvanski"; // Serbian Latin text
$cyrillicText = latinToCyrillic($text);
echo $cyrillicText; // Output: Добар дан, свет!

//$cyrillicText = "Добар дан, свет!"; // Serbian Cyrillic text
//$latinText = cyrillicToLatin($cyrillicText);
//echo $latinText; // Output: Dobar dan, svet!





