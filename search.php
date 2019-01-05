<?php
include "includes/includedFiles.php";

if(isset($_GET['term'])){
    // декодируем полученные из url данные
    $term = urldecode($_GET['term']);
} else {
    $term = "";
}
?>

<div class="searchContainer">
    <h4>Поиск композиций, исполнителей, альбомов</h4>
    <!--чтобы по истечении двух секунд курсор автоматически становился в начало строки пишем onfocus="this.value = this.value"-->
    <input type="text" class="searchInput" value="<?php echo $term; ?>" placeholder="Начать поиск..." onfocus="this.value = this.value">
</div>
<script>
    $(".searchInput").focus();
    $(function(){

       // поиск сработает через 2 секунды
       $(".searchInput").keyup(function(){
           clearTimeout(timer);
           timer = setTimeout(function(){
               var val = $(".searchInput").val();
               openPage("search.php?term=" + val);
           }, 2000);
       });
    });
</script>

<?php if($term == '') exit(); ?>

<div class="trackListContainer borderBottom">
    <h2>КОМПОЗИЦИИ</h2>
    <ul class="tracklist">
        <?php
        // количество песен
        $songsQuery = $pdo->prepare("SELECT COUNT(*) AS `songsCount` FROM `songs` WHERE `title` LIKE :term LIMIT 10");
        $songsQuery->bindValue(":term", $term.'%');
        $songsQuery->execute();
        $res = $songsQuery->fetch(PDO::FETCH_OBJ);
        $count = $res->songsCount;
        if($count == 0){
            echo "<span class='noResults'>Композиций не найдено...".$term."</span>";
        } else {

        }
        // песни
        $songIdArray = array();
        $stmt = $pdo->prepare("SELECT `id` FROM `songs` WHERE `title` LIKE :term LIMIT 10");
        $stmt->bindValue(":term", $term.'%');
        $stmt->execute();
        $i = 1;
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            // т.к. всего песен 15. В перспективе это можно исправить.
            if($i > 15){
                break;
            }
            array_push($songIdArray, $row['id']);
            $albumSong = new Song($pdo, $row['id']);
            $albumArtist = $albumSong->getArtist();
            /*
             * playlist
             */
            echo "<li class='trackListRow'>
                        <div class='trackCount'>
                            <img src='assets/images/icons/play-white.png' alt='' class='play' onclick='setTrack(\"".
                $albumSong->getId()."\", tempPlaylist, true)'>
                            <span class='trackNumber'>$i</span>
                        </div>

                        <div class='trackInfo'>
                            <span class='trackName'>".$albumSong->getTitle()."</span>
                            <span class='artistName'>".$albumArtist->getName()."</span>
                        </div>

                        <div class='trackOptions'>
                            <input type='hidden' class='songId' value='" . $albumSong->getId() . "'>
                            <img class='optionsButton' src='assets/images/icons/more.png' onclick='showOptionsMenu(this)'>
					    </div>

                        <div class='trackDuration'>
                            <span class='duration'>".$albumSong->getDuration()."</span>
                        </div>
                      </li>";
            $i++;
        }
        ?>
        <script>
            var tempSongIds = '<?php echo json_encode($songIdArray); ?>';
            tempPlaylist = JSON.parse(tempSongIds);
        </script>
    </ul>
</div>
<!--поиск исполнителей-->
<div class="artistContainer borderBottom">
    <h2>ИСПОЛНИТЕЛИ</h2>
    <?php
        $artistsQuery = $pdo->prepare("SELECT COUNT(*) AS `artistsCount` FROM `artists` WHERE `name` LIKE :term LIMIT 10");
        $artistsQuery->bindValue(":term", $term.'%');
        $artistsQuery->execute();
        $res1 = $artistsQuery->fetch(PDO::FETCH_OBJ);
        $count = $res1->artistsCount;
        if($count == 0){
            echo "<span class='noResults'>Исполнителей не найдено...".$term."</span>";
        }
        // исполнители
        $stmt1 = $pdo->prepare("SELECT `id` FROM `artists` WHERE `name` LIKE :term LIMIT 10");
        $stmt1->bindValue(":term", $term.'%');
        $stmt1->execute();

        while($row1 = $stmt1->fetch(PDO::FETCH_ASSOC)) {
            $artistFound = new Artist($pdo, $row1['id']);
            echo "<div class='searchResultRow'>
                    <div class='artistName'>
                        <span class='artistName' role='link' tabindex='0' onclick='openPage(\"artist.php?id=".$artistFound->getId()."\")'>".$artistFound->getName()."</span>
                    </div>
                  </div>";
        }
    ?>
</div>
<!--поиск альбома-->
<div class="gridViewContainer">
    <h2>АЛЬБОМЫ</h2>
    <?php

    $albumsQuery = $pdo->prepare("SELECT COUNT(*) AS `albumsCount` FROM `albums` WHERE `title` LIKE :term LIMIT 10");
    $albumsQuery->bindValue(":term", $term.'%');
    $albumsQuery->execute();
    $res2 = $albumsQuery->fetch(PDO::FETCH_OBJ);
    $count = $res2->albumsCount;
    if($count == 0){
        echo "<span class='noResults'>Альбомов не найдено...".$term."</span>";
    }

    $stmt2 = $pdo->prepare("SELECT * FROM `albums` WHERE `title` LIKE :term LIMIT 10");
    $stmt2->bindValue(":term", $term.'%');
    $stmt2->execute();
    while($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)){
        echo "<div class='gridViewItem'>
               <span role='link' tabindex='0' onclick='openPage(\"album.php?id=".$row2['id']."\")'>
                   <img src='".$row2['artworkPath']."'>
                   <div class='gridViewInfo'>"
            .$row2['title'].
            "</div>
               </span>
             </div>";
    }
    ?>
</div>
<!--options menu-->
<nav class="optionsMenu">
    <input type="hidden" class="songId">
    <!--$userLoggedIn содержит экз. класса User-->
    <?php echo Playlist::getPlaylistsDropdown($pdo, $userLoggedIn->getUsername()); ?>
</nav>