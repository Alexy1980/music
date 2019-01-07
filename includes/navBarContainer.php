<!--панель навигации-->
<div id="navBarContainer">
    <nav class="navBar">
        <span role="link" tabindex="0" onclick="openPage('index.php')" class="logo">
            <img src="assets/images/icons/note_white.png" alt="">
        </span>
        <!--поиск-->
        <div class="group">
            <div class="navItem">
                <span role='link' tabindex='0' onclick="openPage('search.php')" class="navItemLink">Поиск
                    <img src="assets/images/icons/search.png" class="icon" alt="Search">
                </span>
            </div>
        </div>
        <!--download-->
        <!--навигация-->
        <div class="group">
            <div class="navItem">
                <span role="link" tabindex="0" onclick="openPage('downloadMusic.php')" class="navItemLink">Скачать музыку</span>
            </div>
            <div class="navItem">
                <span role="link" tabindex="0" onclick="openPage('browse.php')" class="navItemLink">Выбор</span>
            </div>
            <div class="navItem">
                <span role="link" tabindex="0" onclick="openPage('yourMusic.php')" class="navItemLink">Ваша музыка</span>
            </div>
            <div class="navItem">
                <span role="link" tabindex="0" onclick="openPage('settings.php')" class="navItemLink"><?php echo $userLoggedIn; ?></span>
            </div>
        </div>
    </nav>
</div>