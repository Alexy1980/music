<?php

include("includes/includedFiles.php");

?>

<div class="userDetails">

    <div class="container borderBottom">
        <h2>EMAIL</h2>
        <input type="text" class="email" name="email" placeholder="Email адрес..." value="<?php echo $userLoggedIn->getEmail(); ?>">
        <span class="message"></span>
        <button class="button" onclick="updateEmail('email')">СОХРАНИТЬ</button>
    </div>

    <div class="container">
        <h2>ПАРОЛЬ</h2>
        <input type="password" class="oldPassword" name="oldPassword" placeholder="Текущий пароль">
        <input type="password" class="newPassword1" name="newPassword1" placeholder="Новый пароль">
        <input type="password" class="newPassword2" name="newPassword2" placeholder="Подтвердить новый пароль">
        <span class="message"></span>
        <button class="button" onclick="">СОХРАНИТЬ</button>
    </div>

</div>