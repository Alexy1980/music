<?php

function sanitizeFromPassword($inputText){
    $inputText = strip_tags($inputText);
    return $inputText;
}

function echoVars($vars = array()){
    if(isset($_POST['registerButton'])) {
        foreach ($vars as $var) {
            echo $var . '<br>';
        }
    }
}

function sanitizeFromString($inputText){
    $inputText = strip_tags($inputText);
    $inputText = str_replace(" ", "", $inputText);
    $inputText = ucfirst(strtolower($inputText));
    return $inputText;
}

function sanitizeFromUsername($inputText){
    $inputText = strip_tags($inputText);
    $inputText = str_replace(" ", "", $inputText);
    return $inputText;
}

if(isset($_POST['registerButton'])){

    $username =  sanitizeFromUsername($_POST['username']);
    $firstName = sanitizeFromString($_POST['firstName']);
    $lastName = sanitizeFromString($_POST['lastName']);
    $email = sanitizeFromString($_POST['email']);
    $email2 = sanitizeFromString($_POST['email2']);
    $password = sanitizeFromPassword($_POST['password']);
    $password2 = sanitizeFromPassword($_POST['password2']);
    // $account = new Account();
    $wasSuccessful = $account->register($username, $firstName, $lastName, $email, $email2, $password, $password2);
    // если регистрация прошла успешно. переходим на index.php
    if($wasSuccessful == true){
        $_SESSION['userLoggedIn'] = $username;
        header("Location: index.php");
    }
}
?>