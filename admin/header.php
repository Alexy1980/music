<?php
include "../includes/config.php";

// session_destroy(); // LOGOUT
if(isset($_SESSION['adminLoggedIn'])){
    $adminLoggedIn = $_SESSION['adminLoggedIn'];

    echo "<script>adminLoggedIn = '$adminLoggedIn';</script>";
} else {
    header("Location: loginAdmin.php");
}
?>
<html>
<head>
    <title><?php echo $adminLoggedIn; ?>, Приветствуем Вас!</title>
    <link rel="stylesheet" href="../assets/css/style.css" type="text/css">
    <script type="text/javascript" src="../assets/js/JQuery_3.3.1.min.js"></script>
    <script type="text/javascript" src="../assets/js/script.js"></script>
</head>

<body>

<div id="mainContainer">
    <div id="topContainer">
        <div id="mainViewContainer">
            <div id="mainContent">