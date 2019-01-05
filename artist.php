<?php
include "includes/includedFiles.php";

// если не выбран артист
if(isset($_GET['id'])){
    $artistId = $_GET['id'];
} else {
    header("Location:index.php");
}
$artist = new Artist($pdo, $artistId);
?>

<div class="entityInfo borderBottom">
    <div class="centerSection">
        <div class="artistInfo">
            <h1 class="artistName">
                <?php echo $artist->getName(); ?>
            </h1>
            <div class="headerButtons">
                <button class="button green" onclick="playFirstSong()">PLAY</button>
            </div>
        </div>
    </div>
</div>

<div class="trackListContainer borderBottom">
    <h2>КОМПОЗИЦИИ</h2>
    <ul class="tracklist">
        <?php
        $songIdArray = $artist->getSongIds();
        $i = 1;
        foreach($songIdArray as $songId){
            // т.к. всего исполнителей 5. В перспективе это можно исправить.
            if($i > 5){
                break;
            }
            $albumSong = new Song($pdo, $songId);
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

<div class="gridViewContainer">
    <h2>АЛЬБОМЫ</h2>
    <?php
    // чтобы картинки появлялись рандомно
    $stmt = $pdo->prepare("SELECT * FROM `albums` WHERE `artist` = :artistId");
    $stmt->bindParam(":artistId", $artistId, PDO::PARAM_INT);
    $stmt->execute();
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        echo "<div class='gridViewItem'>
               <span role='link' tabindex='0' onclick='openPage(\"album.php?id=".$row['id']."\")'>
                   <img src='".$row['artworkPath']."'>
                   <div class='gridViewInfo'>"
            .$row['title'].
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
