<?php
include "../../config.php";

if(isset($_POST['songId'])){
    // в songId передается рандомное currentPlaylist[0] при вызове функции setTrack()
    $songId = trim(strip_tags($_POST['songId']));
    $stmt = $pdo->prepare("SELECT * FROM `Songs` WHERE `id` = :songId");
    $stmt->bindParam(":songId", $songId, PDO::PARAM_INT);
    $stmt->execute();
    $resultArray = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode($resultArray);
}