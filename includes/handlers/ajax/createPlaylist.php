<?php
include("../../config.php");

if(isset($_POST['name']) && isset($_POST['username'])) {

    $namePlaylist = $_POST['name'];
    $username = $_POST['username'];
    $dateCreated = date("Y-m-d");

    $stmt = $pdo->prepare("INSERT INTO `playlists` VALUES('', :namePlaylist, :username, :dateCreated)");
    $stmt->bindParam(':namePlaylist', $namePlaylist, PDO::PARAM_STR);
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->bindParam(':dateCreated', $dateCreated, PDO::PARAM_STR);
    $stmt->execute();

}
else {
    echo "Имя плейлиста или имя пользователя не передано!";
}

?>