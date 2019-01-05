<?php
// рандомно выбираем 10 песен по id
$songQuery = $pdo->prepare("SELECT `id` FROM `Songs` ORDER BY RAND() LIMIT 10");
$songQuery->execute();
$resultArray = array();
while($row = $songQuery->fetch(PDO::FETCH_ASSOC)){
    array_push($resultArray, $row['id']);
}
// этот массив нужно передать JavaScript. Для этого переводим его в json формат. Таким образом данные можно передать из любого языка программирования
$jsonArray = json_encode($resultArray);
?>
<script>
    $(document).ready(function(){
        var newPlaylist = <?php echo $jsonArray; ?>;
        audioElement = new Audio();
        // вызов функции setTrack()
        setTrack(newPlaylist[0], newPlaylist, false);
        // чтобы громкость по-умолчанию была 100%
        updateVolumeProgressBar(audioElement.audio);

        $("#nowPlayingBarContainer").on("mousedown touchstart mousedown touchmove", function(e){
            e.preventDefault();
        });

        // манипулиции с progressBar
        $(".playBackBar .progressBar").mousedown(function(){
            mouseDown = true;
        });

        $(".playBackBar .progressBar").mousemove(function(e){
            if(mouseDown){
                // ставим время исполнения в зависимости от положения progressBar
                timeFromOffset(e, this);
            }
        });

        $(".playBackBar .progressBar").mouseup(function(e){
            // this = ".playBackBar .progressBar"
            timeFromOffset(e, this);
        });

        // громкость
        $(".volumeBar .progressBar").mousedown(function(){
            mouseDown = true;
        });

        $(".volumeBar .progressBar").mousemove(function(e){
            if(mouseDown){
                var percentage = e.offsetX/$(this).width();
                if(percentage >= 0 && percentage <= 1){
                    audioElement.audio.volume = percentage;
                }
            }
        });

        $(".volumeBar .progressBar").mouseup(function(e){
            var percentage = e.offsetX/$(this).width();
            if(percentage >= 0 && percentage <= 1){
                audioElement.audio.volume = percentage;
            }
        });

        $(document).mouseup(function(){
            mouseDown = false;
        });
    });

    function timeFromOffset(mouse, progressBar){
        var percentage = mouse.offsetX/$(progressBar).width()*100;
        var seconds = audioElement.audio.duration * (percentage/100);
        audioElement.setTime(seconds);
    }

    function prevSong(){
        // если время воспроизведения > 3 сек или песня первая в плей-листе
        if(audioElement.audio.currentTime >= 3 || currentIndex == 0){
            audioElement.setTime(0);
        } else {
            currentIndex = currentIndex - 1;
            setTrack(currentPlaylist[currentIndex], currentPlaylist, true);
        }
    }

    function nextSong(){
        // при repeat == true при нажатии на next будет повторяться одна и та же песня, при false - следующая
        if(repeat == true){
            audioElement.setTime(0);
            playSong();
            return;
        }
        // если currentIndex последний элемент массива
        if(currentIndex == currentPlaylist.length - 1){
            currentIndex = 0;
        } else {
            currentIndex++;
        }
        var trackToPlay = shuffle ? shufflePlaylist[currentIndex] : currentPlaylist[currentIndex];
        setTrack(trackToPlay, currentPlaylist, true);
    }
    // при нажатой кнопке repeat песня будет повторяться
    function setRepeat(){
        repeat = !repeat;
        var imageName = repeat ? "repeat-active.png" : "repeat.png";
        $(".controlButton.repeat img").attr("src", "assets/images/icons/" + imageName);
    }

    function setMute(){
        audioElement.audio.muted = !audioElement.audio.muted;
        var imageName = audioElement.audio.muted ? "volume-mute.png" : "volume.png";
        $(".controlButton.volume img").attr("src", "assets/images/icons/" + imageName);
    }

    // shuffle - тасовать, перетасовывать
    function setShuffle(){
        shuffle = !shuffle;
        var imageName = shuffle ? "shuffle-active.png" : "shuffle.png";
        $(".controlButton.shuffle img").attr("src", "assets/images/icons/" + imageName);
        // console.log(currentPlaylist);
        // console.log(shufflePlaylist);
        if(shuffle == true){
            // randomize playlist
            shuffleArray(shufflePlaylist);
            currentIndex = shufflePlaylist.indexOf(audioElement.currentlyPlaying.id);
        } else {
            // shuffle deactivated
            currentIndex = currentPlaylist.indexOf(audioElement.currentlyPlaying.id);
        }
    }

    function shuffleArray(a) {
        var j, x, i;
        for (i = a.length - 1; i > 0; i--) {
            j = Math.floor(Math.random() * (i + 1));
            x = a[i];
            a[i] = a[j];
            a[j] = x;
        }
        return a;
    }

    function setTrack(trackId, newPlaylist, play){

        if(newPlaylist != currentPlaylist){
            currentPlaylist = newPlaylist;
            shufflePlaylist = currentPlaylist.slice();
            shuffleArray(shufflePlaylist);
        }

        if(shuffle == true){
            currentIndex = shufflePlaylist.indexOf(trackId);
        } else {
            currentIndex = currentPlaylist.indexOf(trackId);
        }
        pauseSong();
        // в songId передается рандомное currentPlaylist[currentIndex] при вызове функции setTrack()
        $.post("includes/handlers/ajax/getSongJson.php", {songId: trackId}, function(data){
            // здесь обрабатываем ответ сервера
            // т.к. data пришла в виде json, ее необходимо обработать
            var track = JSON.parse(data);
            $(".trackName span").text(track.title);
            // artist page
            $.post("includes/handlers/ajax/getArtistJson.php", {artistId: track.artist}, function(data){
                var artist = JSON.parse(data);
                $(".trackInfo .artistName span").text(artist.name);
                $(".trackInfo .artistName span").attr("onclick", "openPage('artist.php?id=" + artist.id +"')");
            });
            // album page
            $.post("includes/handlers/ajax/getAlbumJson.php", {albumId: track.album}, function(data){
                var album = JSON.parse(data);
                $(".content .albumLink img").attr("src", album.artworkPath);
                // перепрыгиваем на страницу альбома при клике на .albumLink или .trackName
                $(".content .albumLink img").attr("onclick", "openPage('album.php?id=" + album.id +"')");
                $(".trackInfo .trackName span").attr("onclick", "openPage('album.php?id=" + album.id +"')");
            });
            audioElement.setTrack(track);
            // проигрывание начинается при загрузке страницы
            if(play == true){
                playSong();
            }
        });
    }

    function playSong(){
        if(audioElement.audio.currentTime == 0){
            $.post("includes/handlers/ajax/updatePlays.php", {songId: audioElement.currentlyPlaying.id});
        }
        $(".controlButton.play").hide();
        $(".controlButton.pause").show();
        audioElement.play();
    }

    function pauseSong(){
        $(".controlButton.play").show();
        $(".controlButton.pause").hide();
        audioElement.pause();
    }
</script>
<div id="nowPlayingBarContainer">
    <div id="nowPlayingBar">
        <div id="nowPlayingLeft">
            <div class="content">
                <span class="albumLink">
                   <img role="link" tabindex="0" src="" alt="image" class="albumArtwork">
                </span>
                <div class="trackInfo">
                    <span class="trackName">
                        <span role="link" tabindex="0"></span>
                    </span>
                    <span class="artistName">
                        <span role="link" tabindex="0"></span>
                    </span>
                </div>
            </div>
        </div>
        <div id="nowPlayingCenter">
            <div class="content playerControls">
                <!--панель управления-->
                <div class="buttons">

                    <button class="controlButton shuffle" title="Shuffle button" onclick="setShuffle()">
                        <img src="assets/images/icons/shuffle.png" alt="Shuffle">
                    </button>

                    <button class="controlButton previous" title="Previous button" onclick="prevSong()">
                        <img src="assets/images/icons/previous.png" alt="Previous">
                    </button>

                    <button class="controlButton play" title="Play button" onclick="playSong()">
                        <img src="assets/images/icons/play.png" alt="Play">
                    </button>

                    <button class="controlButton pause" title="Pause button" style="display: none;" onclick="pauseSong()">
                        <img src="assets/images/icons/pause.png" alt="Pause">
                    </button>

                    <button class="controlButton next" title="Next button" onclick="nextSong()">
                        <img src="assets/images/icons/next.png" alt="Next">
                    </button>

                    <button class="controlButton repeat" title="Repeat button" onclick="setRepeat()">
                        <img src="assets/images/icons/repeat.png" alt="Repeat">
                    </button>

                </div>
                <!--progressbar-->
                <div class="playbackBar">
                    <span class="progressTime current">0.00</span>
                    <div class="progressBar">
                        <div class="progressBarBg">
                            <div class="progress"></div>
                        </div>
                    </div>
                    <span class="progressTime remaining">0.00</span>
                </div>

            </div>

        </div>
        <div id="nowPlayingRight">
            <!--регулятор громкости-->
            <div class="volumeBar">
                <button class="controlButton volume" title="Громкость" onclick="setMute()">
                    <img src="assets/images/icons/volume.png" alt="Громкость">
                </button>
                <div class="progressBar">
                    <div class="progressBarBg">
                        <div class="progress"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>