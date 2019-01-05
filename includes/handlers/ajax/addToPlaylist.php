<?php
    include("../../config.php");

if(isset($_POST['playlistId']) && isset($_POST['songId'])) {

    $playlistId = $_POST['playlistId'];
    $songId = $_POST['songId'];

    $stmt = $pdo->prepare("SELECT MAX(playlistOrder) + 1 as playlistOrder FROM `playlistSongs` WHERE `playlistId` = :playlistId");
    $stmt->bindParam(':playlistId', $playlistId, PDO::PARAM_INT);
    $stmt->execute();
    $orderIdQuery = $stmt->fetch(PDO::FETCH_OBJ);
    // $playlistOrder означает номер песни в плейлисте
    $playlistOrder = $orderIdQuery->playlistOrder;

    $stmt1 = $pdo->prepare("INSERT INTO `playlistSongs` VALUES('', :songId, :playlistId, :playlistOrder)");
    $stmt1->bindParam(':songId', $songId, PDO::PARAM_INT);
    $stmt1->bindParam(':playlistId', $playlistId, PDO::PARAM_INT);
    $stmt1->bindParam(':playlistOrder', $playlistOrder, PDO::PARAM_INT);
    $stmt1->execute();
}
else {
    echo "ID плейлиста или ID песни не передано!";
}

?>