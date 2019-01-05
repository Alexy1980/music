<?php
include "../../config.php";

if(isset($_POST['albumId'])){

    $albumId = trim(strip_tags($_POST['albumId']));
    $stmt = $pdo->prepare("SELECT * FROM `albums` WHERE `id` = :albumId");
    $stmt->bindParam(":albumId", $albumId, PDO::PARAM_INT);
    $stmt->execute();
    $resultArray = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode($resultArray);
}