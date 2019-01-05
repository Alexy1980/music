<?php
    ob_start();
    session_start();
    $timezone = date_default_timezone_set("Europe/Moscow");
    $dsn = 'mysql:host=localhost; dbname=spotify';
    $user = 'Paul';
    $pass = '170680';
    try{
        $pdo = new PDO($dsn, $user, $pass);
    } catch (PDOException $e){
        echo 'Проблемы с подключением к базе данных! '.$e->getMessage();
    }