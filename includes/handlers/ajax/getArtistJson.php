<?php
include "../../config.php";

if(isset($_POST['artistId'])){

    $artistId = trim(strip_tags($_POST['artistId']));
    $stmt = $pdo->prepare("SELECT * FROM `artists` WHERE `id` = :artistId");
    $stmt->bindParam(":artistId", $artistId, PDO::PARAM_INT);
    $stmt->execute();
    $resultArray = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode($resultArray);
}