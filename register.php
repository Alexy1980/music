<?php
    include 'includes/config.php';
    include 'includes/classes/Account.php';
    include 'includes/classes/Constants.php';
    $account = new Account($pdo);
    include 'includes/handlers/register-handler.php';
    include 'includes/handlers/login-handler.php';

    function getInputValue($name){
        if(isset($_POST[$name])){
            echo $_POST[$name];
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Добро пожаловать!</title>
    <link rel="stylesheet" href="assets/css/register.css" type="text/css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="assets/js/register.js"></script>
</head>
<body>
<?php
if(isset($_POST['registerButton'])){
    echo '<script>
                $(document).ready(function(){
                    $("#loginForm").hide();
                    $("#registerForm").show();
                });
          </script>';
} else {
    echo '<script>
                $(document).ready(function(){
                    $("#loginForm").show();
                    $("#registerForm").hide();
                });
          </script>';
}
?>
<div id="background">
    <div id="inputContainer">
        <div id="loginContainer">
            <form action="register.php" id="loginForm" method="post">
                <h2 class="login">Логин Вашего аккаунта</h2>
                <p>
                    <?php echo $account->getError(Constants::$loginFail); ?>
                    <label for="loginUserName">Логин</label>
                    <input type="text" id="loginUsername" name="loginUsername" placeholder="Ваш логин" value="<?php getInputValue('loginUsername'); ?>" required>
                </p>
                <p>
                    <label for="loginPassword">Пароль</label>
                    <input id="loginPassword" name="loginPassword" type="password" required>
                </p>
                <button type="submit" name="loginButton">ВОЙТИ</button>
                <div class="hasAccountText">
                    <span id="hideLogin">Не зарегистрированы? Зарегистрируйтесь!</span>
                </div>
            </form>
            <!--форма регистрации-->
            <form action="register.php" id="registerForm" method="post">
                <h2>Зарегистрируйтесь!</h2>
                <p>
                    <?php echo $account->getError(Constants::$usernameNotLength); ?>
                    <?php echo $account->getError(Constants::$usernameTaken); ?>
                    <label for="firstName">Username</label>
                    <input type="text" id="username" name="username" placeholder="Username" value="<?php getInputValue('username'); ?>" required>
                </p>
                <p>
                    <?php echo $account->getError(Constants::$firstNameNotLength); ?>
                    <label for="firstName">First name</label>
                    <input type="text" id="firstName" name="firstName" placeholder="First name" value="<?php getInputValue('firstName'); ?>" required>
                </p>
                <p>
                    <?php echo $account->getError(Constants::$lastNameNotLength); ?>
                    <label for="lastName">Last name</label>
                    <input type="text" id="lastName" name="lastName" placeholder="Last name" value="<?php getInputValue('lastName'); ?>" required>
                </p>
                <p>
                    <?php echo $account->getError(Constants::$emailsDoNotValid); ?>
                    <?php echo $account->getError(Constants::$emailTaken); ?>
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="email@mail.com" value="<?php getInputValue('email'); ?>" required>
                </p>
                <p>
                    <?php echo $account->getError(Constants::$emailsDoNotMatch); ?>
                    <label for="email2">Confirm email</label>
                    <input id="email2" name="email2" type="email" placeholder="email@mail.com" required>
                </p>
                <p>
                    <?php echo $account->getError(Constants::$passwordsDoNotValid); ?>
                    <?php echo $account->getError(Constants::$passwordsNotLength); ?>
                    <label for="password">Пароль</label>
                    <input id="password" name="password" type="password" placeholder="Ваш пароль" value="<?php getInputValue('password'); ?>" required>
                </p>
                <p>
                    <?php echo $account->getError(Constants::$passwordsDoNotMatch); ?>
                    <label for="password2">Подтверждение пароля</label>
                    <input id="password2" name="password2" type="password" placeholder="Подтвержение пароля" required>
                </p>
                <button type="submit" name="registerButton">Зарегистрироваться</button>
                <div class="hasAccountText">
                    <span id="hideRegister">Зарегистрированы? Войдите!</span>
                </div>
            </form>
        </div>
    </div>
    <div id="loginText">
        <h1>Слушайте хорошую музыку!</h1>
        <h2>Слушайте и скачивайте прямо сейчас бесплатно!</h2>
        <ul>
            <li>Выбирайте вашу любимую музыку</li>
            <li>Создайте свои плейлисты</li>
            <li>Сохраняйте историю прослушивания</li>
        </ul>
    </div>
</div>
</body>
</html>