<?php

include "../../config.php";

if(!isset($_POST['username'])){
    echo "ОШИБКА: параметр username не передан!";
    exit();
}

if(isset($_POST['email']) && $_POST['email'] != ''){
    $username = $_POST['username'];
    $email = $_POST['email'];
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        echo "EMAIl не соответствует шаблону!";
    }

    $emailCheck = $pdo->prepare("SELECT `email`, count(*) as countEmail FROM `users` WHERE `email` = :email AND `username` != :username");
    $emailCheck->bindParam(':email', $email, PDO::PARAM_STR);
    $emailCheck->bindParam(':username', $username, PDO::PARAM_STR);
    $emailCheck->execute();
    $row = $emailCheck->fetch(PDO::FETCH_ASSOC);
    if($row['countEmail'] > 0){
        echo "Такой EMAIL уже существует!";
        exit();
    }

    $updateQuery = $pdo->prepare("UPDATE `users` SET `email` = :email WHERE `username` = :username");
    $updateQuery->bindParam(':email', $email, PDO::PARAM_STR);
    $updateQuery->bindParam(':username', $username, PDO::PARAM_STR);
    $updateQuery->execute();
    echo "EMAIL успешно обновлен!";
    // echo $_POST['email'];

} else {
    echo "Вы можете добавить пользователя";
}

?>