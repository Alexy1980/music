<?php
include("../../config.php");

if(isset($_POST['playlistId'])) {

    $playlistId = $_POST['playlistId'];

    $stmt = $pdo->prepare("DELETE FROM `playlists` WHERE `id` = :playlistId");
    $stmt->bindParam(':playlistId', $playlistId, PDO::PARAM_INT);
    $stmt->execute();
    // удаление песен из плейлиста
    $stmt1 = $pdo->prepare("DELETE FROM `playlistSongs` WHERE `playlistId` = :playlistId");
    $stmt1->bindParam(':playlistId', $playlistId, PDO::PARAM_INT);
    $stmt1->execute();

}
else {
    echo "ID плейлиста не передано!";
}

?>