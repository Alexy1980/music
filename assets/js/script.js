/**
 * jsonArray
 * @type {Array}
 */
var currentPlaylist = [];
var shufflePlaylist = [];
var tempPlaylist = [];
var timer;
/**
 * audioElement = new Audio();
 */
var audioElement;
var mouseDown = false;
var currentIndex = 0;
var repeat = false;
var shuffle = false;
var userLoggedIn;
// songId и playlistId лучше не делать глобальными. Но пока и так сойдет.
var songId;
var playlistId;

$(document).click(function(click){
    var target = $(click.target);
    // если кликаем не на панели выпадающего меню
    if(!target.hasClass("item") && !target.hasClass("optionsButton")){
        hideOptionsMenu();
    }
});

$(window).scroll(function() {
    hideOptionsMenu();
});

$(document).on("change", "select.playlist", function() {
    var select = $(this);
    playlistId = select.val();
    $.post("includes/handlers/ajax/addToPlaylist.php", {playlistId: playlistId, songId: songId})
        .done(function(error){

            if(error != ""){
                alert(error);
                return;
            }
            hideOptionsMenu();
            select.val("");
        });
});

// songId определяем следующим образом
$(document).on("click", "img.optionsButton", function(){
    songId = $(this).prev(".songId").val();
});

// кликая на любоим месте документа, кроме .item и .optionsMenu
$(document).click(function(click){
    var target = $(click.target);
    if(!target.hasClass("item") && !target.hasClass("optionsButton")){
        hideOptionsMenu();
    }
});
// прокручивая страницу всплывающее меню будет скрыто
$(window).scroll(function(){
    hideOptionsMenu();
});

function openPage(url){
    if(timer != null){
        clearTimeout(timer);
    }
    if(url.indexOf("?") == -1){
        url = url + "?";
    }
    var encodedUrl = encodeURI(url + "&userLoggedIn=" + userLoggedIn);
    $("#mainContent").load(encodedUrl);
    $("body").scrollTop(0);
    // В HTML документе метод history.pushState() добавляет новое состояние в историю браузера
    history.pushState(null, null, url);
}

function removeFromPlaylist(button, playlistId){
    // var songId = $(button).prevAll(".songId").val();
    $.post("includes/handlers/ajax/removeFromPlaylist.php", {playlistId: playlistId, songId: songId})
    .done(function(error){
        if(error != ""){
            alert(error);
            return;
        }
        // делаем что-то когда ajax отработал
        openPage("playlist.php?id=" + playlistId);
    });
}

function createPlaylist(){
    var popup = prompt("Пожалуйста, введите название Вашего плейлиста");
    if(popup != null){
        $.post("includes/handlers/ajax/createPlaylist.php", {name: popup, username: userLoggedIn})
            .done(function(error){
            if(error != ""){
                alert(error);
                return;
            }
            // делаем что-то когда ajax отработал
            openPage("yourMusic.php");
        });
    }
}

function deletePlaylist(playlistId){
    var prompt = confirm('вы уверены, что хотите удалить плейлист?');
    if(prompt == true){
        $.post("includes/handlers/ajax/deletePlaylist.php", {playlistId: playlistId})
            .done(function(error){
            if(error != ""){
                alert(error);
                return;
            }
            // делаем что-то когда ajax отработал
            openPage("yourMusic.php");
        });
    }
}

function hideOptionsMenu(){
    var menu = $(".optionsMenu");
    if(menu.css("display") != "none"){
        menu.css("display", "none");
    }
}

function showOptionsMenu(button){
    // var songId = $(button).prevAll(".songId").val();
    var menu = $(".optionsMenu");
    var menuWidth = menu.width();
    menu.find(".songId").val(songId);
    // координаты положения всплывающего меню
    var scrollTop = $(window).scrollTop();
    var elementOffset = $(button).offset().top;
    var top = elementOffset - scrollTop;
    var left = $(button).position().left;
    menu.css({"top": top+"px", left: (left - menuWidth) +"px", "display": "inline"});
}

function hideOptionsMenu(){
    var menu = $(".optionsMenu");
    if(menu.css("display") != "none"){
        menu.css("display", "none");
    }
}

function showOptionsMenu(button){
    var songId = $(button).prevAll(".songId").val();
    var menu = $(".optionsMenu");
    var menuWidth = menu.width();
    // distance from top of window to top of document
    var scrolTop = $(window).scrollTop();
    // distance from top of document
    var elementOffset = $(button).offset().top;
    var top = elementOffset - scrolTop;
    var left = $(button).position().left;
    menu.css({"top":top+"px", "left":left - menuWidth + "px", "display":"inline"});
}

/**
 *
 * @param seconds
 */
function formatTime(seconds){
    var time = Math.round(seconds);
    var minutes = Math.floor(time/60);
    var sec = time - (minutes*60);
    var extraZero;
    if(sec < 10){
        extraZero = "0";
    } else {
        extraZero = "";
    }
    // 5.04 вместо 5.4
    return minutes + ":" + extraZero + sec;
}

/**
 *
 * @param audio
 */
function updateTimeProgressBar(audio){
    // время песни справа
    $(".progressTime.current").text(formatTime(audio.currentTime));
    // время песни слева
    $(".progressTime.remaining").text(formatTime(audio.duration - audio.currentTime));
    // progressBar
    var progress = audio.currentTime/audio.duration*100;
    $(".playbackBar .progress").css("width", progress + "%");
}

function updateVolumeProgressBar(audio){
    var volume = audio.volume*100;
    $(".volumeBar .progress").css("width", volume + "%");
}

function playFirstSong(){
    setTrack(tempPlaylist[0], tempPlaylist, true);
}

/**
 * класс Audio()
 * @constructor
 * события ended, canplay, timeupdate, volumechange и т.д. см. в документации html5 audio
 */
function Audio() {
    this.currentlyPlaying;

    this.audio = document.createElement('audio');
    // данная функция отвечает за воспроизведение последующей песни после окончния текущей
    this.audio.addEventListener("ended", function(){
        nextSong();
    });

    this.audio.addEventListener("canplay", function(){
        // progress bar. this относится к audio
        var duration = formatTime(this.duration);
        $(".progressTime.remaining").text(duration);
    });

    this.audio.addEventListener("timeupdate", function(){
        if(this.duration){
            // каждый раз, когда происходит событие timeupdate, вызывается функция updateTimeProgressBar(this)
            // this здесь - audio
            updateTimeProgressBar(this);
        }
    });

    // на событие volumechange вешаем обработчик
    this.audio.addEventListener("volumechange", function(){
        if(this.volume){
            updateVolumeProgressBar(this);
        }
    });

    // src - путь к аудиофайлу = track.path
    this.setTrack = function(track){
        this.currentlyPlaying = track;
        this.audio.src = track.path;
    };

    this.play = function(){
        this.audio.play();
    };

    this.pause = function(){
        this.audio.pause();
    };

    this.setTime = function(seconds){
        this.audio.currentTime = seconds;
    }
}
