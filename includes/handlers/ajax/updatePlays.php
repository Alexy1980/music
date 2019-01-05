<?php
include "../../config.php";

if(isset($_POST['songId'])){

    $songId = trim(strip_tags($_POST['songId']));
    $stmt = $pdo->prepare("UPDATE `Songs` SET `plays` = `plays` + 1 WHERE `id` = :songId");
    $stmt->bindParam(":songId", $songId, PDO::PARAM_INT);
    $stmt->execute();
}
