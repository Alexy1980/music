<?php

include 'includes/includedFiles.php';

if(isset($_GET['id'])){
    $albumId = $_GET['id'];
} else {
    // echo 'Nothing, man...';
    header("Location: index.php");
}

$album = new Album($pdo, $albumId);
$artist = $album->getArtist();
$songs = array('песня', 'песни', 'песен');
// print_r($_SESSION);
// $userLoggedIn = new User($pdo, $_SESSION['userLoggedIn']);
?>
<div class="entityInfo">
    <div class="leftSection">
        <img src="<?php echo $album->getArtworkPath(); ?>" alt="<?php echo $artist->getName(); ?>">
    </div>
    <div class="rightSection">
        <h2><?php echo $album->getTitle(); ?></h2>
        <p>By <?php echo $artist->getName(); ?></p>
        <p><?php echo $album->declOfNum($album->getNumberOfSongs(), $songs); ?></p>
    </div>
</div>

<div class="trackListContainer">
    <ul class="tracklist">
        <?php
            $songIdArray = $album->getSongIds();
            $i = 1;
            foreach($songIdArray as $songId){
                $albumSong = new Song($pdo, $songId);
                //echo $albumSong->getId();
                $albumArtist = $albumSong->getArtist();
                /*
                 * playlist
                 */
                echo "<li class='tracklistRow'>
					<div class='trackCount'>
						<img class='play' src='assets/images/icons/play-white.png' onclick='setTrack(\"" . $albumSong->getId() . "\", tempPlaylist, true)'>
						<span class='trackNumber'>$i</span>
					</div>


					<div class='trackInfo'>
						<span class='trackName'>" . $albumSong->getTitle() . "</span>
						<span class='artistName'>" . $albumArtist->getName() . "</span>
					</div>

					<div class='trackOptions'>
						<input type='hidden' class='songId' value='" . $albumSong->getId() . "'>
						<img class='optionsButton' src='assets/images/icons/more.png' onclick='showOptionsMenu(this)'>
					</div>

					<div class='trackDuration'>
						<span class='duration'>" . $albumSong->getDuration() . "</span>
					</div>


				</li>";
                $i = $i + 1;
            }
        ?>
        <script>
            var tempSongIds = '<?php echo json_encode($songIdArray); ?>';
            tempPlaylist = JSON.parse(tempSongIds);
        </script>
    </ul>
</div>
<!--options menu-->
<nav class="optionsMenu">
    <input type="hidden" class="songId">
    <!--$userLoggedIn содержит экз. класса User-->
    <?php echo Playlist::getPlaylistsDropdown($pdo, $userLoggedIn->getUsername()); ?>
</nav>

