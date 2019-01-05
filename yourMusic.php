<?php
    include "includes/includedFiles.php";
?>

<div class="playlistsContainer">

    <div class="gridViewContainer">
        <h2>ВАШИ ПЛЕЙЛИСТЫ</h2>

        <div class="buttonItems">
            <button class="button green" onclick="createPlaylist()">НОВЫЙ ПЛЕЙЛИСТ</button>
        </div>

        <?php
            $username = $userLoggedIn->getUsername();

            $playlistsQuery = $pdo->prepare("SELECT COUNT(*) AS `playlistsQueryCount` FROM `playlists` WHERE owner = :username");
            $playlistsQuery->bindParam(":username", $username, PDO::PARAM_STR);
            $playlistsQuery->execute();
            $res = $playlistsQuery->fetch(PDO::FETCH_OBJ);
            $count = $res->playlistsQueryCount;
            if($count == 0){
                echo "<span class='noResults'>Вы еще не создали свой плейлист!</span>";
            } else {

            }
            // вывод плейлиста
            $stmt = $pdo->prepare("SELECT * FROM `playlists` WHERE `owner` = :username");
            $stmt->bindParam(":username", $username, PDO::PARAM_STR);
            $stmt->execute();

            while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $playlist = new Playlist($pdo, $row);
                echo "<div class='gridViewItem' role='link' tabindex='0' onclick='openPage(\"playlist.php?id=".
                            $playlist->getId()."\")'>

                            <div class='playlistImage'>
                                <img src='assets/images/icons/playlist.png'>
                            </div>

                            <div class='gridViewInfo'>"
                    . $playlist->getName() .
                    "</div>

                    </div>";

            }
        ?>

    </div>

</div>
