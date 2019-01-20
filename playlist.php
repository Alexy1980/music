<?php include("includes/includedFiles.php");

if(isset($_GET['id'])) {
    $playlistId = $_GET['id'];
    $playlist = new Playlist($pdo, $playlistId);
    $owner = new User($pdo, $playlist->getOwner());
    echo $owner->getUsername();
}
else {
    header("Location: index.php");
}

?>

<div class="entityInfo">

    <div class="leftSection">
        <div class="playlistImage">
            <img src="assets/images/icons/playlist.png">
        </div>
    </div>

    <div class="rightSection">
        <h2><?php echo $playlist->getName(); ?></h2>
        <p>By <?php echo $playlist->getOwner(); ?></p>
        <p><?php echo $playlist->getNumberOfSongs(); ?> songs</p>
        <button class="button" onclick="deletePlaylist('<?php echo $playlistId; ?>')">УДАЛИТЬ ПЛЕЙЛИСТ</button>

    </div>

</div>


<div class="tracklistContainer">
    <ul class="tracklist">

        <?php
        $songIdArray = $playlist->getSongIds();

        $i = 1;
        foreach($songIdArray as $songId) {

            $playlistSong = new Song($pdo, $songId);
            $songArtist = $playlistSong->getArtist();


            echo "<li class='tracklistRow'>
					<div class='trackCount'>
						<img class='play' src='assets/images/icons/play-white.png' onclick='setTrack(\"" . $playlistSong->getId() . "\", tempPlaylist, true)'>
						<span class='trackNumber'>$i</span>
					</div>


					<div class='trackInfo'>
						<span class='trackName'>" . $playlistSong->getTitle() . "</span>
						<span class='artistName'>" . $songArtist->getName() . "</span>
					</div>

					<div class='trackOptions'>
						<input type='hidden' class='songId' value='" . $playlistSong->getId() . "'>
						<img class='optionsButton' src='assets/images/icons/more.png' onclick='showOptionsMenu(this)'>
					</div>

					<div class='trackDuration'>
						<span class='duration'>" . $playlistSong->getDuration() . "</span>
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
    <div class="item" onclick="removeFromPlaylist(this, '<?php echo $playlistId; ?>')">Удалить из плейлиста</div>
</nav>