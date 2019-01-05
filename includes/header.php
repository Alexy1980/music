<?php
include "includes/config.php";
include 'includes/classes/Artist.php';
include 'includes/classes/Album.php';
include 'includes/classes/Song.php';
// session_destroy(); // LOGOUT
if(isset($_SESSION['userLoggedIn'])){
    $userLoggedIn = $_SESSION['userLoggedIn'];
    // $userLoggedIn - Vasya
    echo "<script>userLoggedIn = '$userLoggedIn';</script>";
} else {
    header("Location: register.php");
}
?>
<html>
<head>
    <title><?php echo $userLoggedIn; ?>, Приветствуем Вас в Slotify!</title>
    <link rel="stylesheet" href="assets/css/style.css" type="text/css">
    <script type="text/javascript" src="assets/js/JQuery_3.3.1.min.js"></script>
    <script type="text/javascript" src="assets/js/script.js"></script>
</head>

<body>

<div id="mainContainer">
    <div id="topContainer">
        <?php include "includes/navBarContainer.php"; ?>
        <div id="mainViewContainer">
            <div id="mainContent">