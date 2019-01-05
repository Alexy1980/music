<?php
    include("../../config.php");

    if(isset($_POST['playlistId']) && isset($_POST['songId'])) {

        $playlistId = $_POST['playlistId'];
        $songId = $_POST['songId'];

        $stmt = $pdo->prepare("DELETE FROM `playlistSongs` WHERE `playlistId` = :playlistId AND `songId` = :songId");
        $stmt->bindParam(':playlistId', $playlistId, PDO::PARAM_INT);
        $stmt->bindParam(':songId', $songId, PDO::PARAM_INT);
        $stmt->execute();

    }
    else {
        echo "ID плейлиста или ID композиции не передано!";
    }
?>