<?php
/**
 * данный функционал работает только с включенным vpn, т.к. исходный сайт заблокирован роспотребнадзором
 */
function getDownloadLink(){
    $music = [];

    if($curl = curl_init())
    {
        if(isset($_POST['artist']) && !empty($_POST['artist'])){
            $get = trim(strip_tags($_POST['artist']));
        }

        curl_setopt($curl,CURLOPT_URL, "https://usermusic.org/search/".$get);
        curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($curl,CURLOPT_TIMEOUT,60);


        $html = curl_exec($curl);
        curl_close($curl);
        /*Взять то, что находится между тегами <title> и </title>
            if (preg_match('|<title.*?>(.*)</title>|sei', $str, $arr)) $title
            = $arr[1];
            else $title='';*/
        //echo $html;
        // ищем от song- до <span class="title-name"> и потом до </span>
        preg_match_all('|song-.*?<span class="title-name">.*?</span>|sei',$html,$arr);
        // print_r($arr);
        // echo "<a href=\"index.php\">НАЗАД</a><br>";
        if(isset($arr[0]))
        {
            foreach($arr[0] as $i=>$val)
            {
                preg_match('|data-songid=".*?"|sei',$val,$song);
                $song=str_replace('data-songid="',"",$song[0]);
                $song=str_replace('"',"",$song);
                // echo $song;
                $music[$i]['id']=$song;

                preg_match('|class="artist-name searchit".*?<span class="title-name">.*?</span>|sei',$val,$artist);

                $artist=strip_tags("<a ".$artist[0]);
                // echo $artist;
                preg_match('|<span class="title-name">.*?</span></span>|sei',$val,$name);
                $name=strip_tags($name[0]);

                $music[$i]['name']=$artist." - ".$name;

                //http://poiskm.co/?do=getById&id=-118073451_456239306&n=3
                echo "<a href=\"http://poiskm.co/?do=getById&id=".$song."&n=3\">".$artist." - ".$name."</a><br>";
            }

            //print_r($music);
        }
        else echo "not found";
    }
    else echo "curl err";
}
getDownloadLink();