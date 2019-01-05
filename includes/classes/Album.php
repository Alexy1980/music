<?php

class Album
{
    private $pdo;
    private $id;
    private $title;
    private $artistId;
    private $genre;
    private $artworkPath;

    public function __construct($pdo, $id)
    {
        $this->pdo = $pdo;
        $this->id = $id;
        $query = $this->pdo->prepare("SELECT * FROM `albums` WHERE `id` = :albumId");
        $query->bindParam(":albumId", $this->id, PDO::PARAM_INT);
        $query->execute();
        $album = $query->fetch(PDO::FETCH_ASSOC);
        $this->title = $album['title'];
        $this->artistId = $album['artist'];
        $this->genre = $album['genre'];
        $this->artworkPath = $album['artworkPath'];
    }

    public function getTitle(){
        return $this->title;
    }

    public function getArtworkPath(){
        return $this->artworkPath;
    }

    public function getArtist(){
        return new Artist($this->pdo, $this->artistId);
    }

    public function getGenre(){
        return $this->genre;
    }

    public function getNumberOfSongs(){
        $stmt = $this->pdo->prepare("SELECT COUNT(*) AS `countSongs` FROM `songs` WHERE `album` = :thisId");
        $stmt->bindParam(":thisId", $this->id, PDO::PARAM_INT);
        $stmt->execute();
        $res = $stmt->fetch(PDO::FETCH_OBJ);
        $count = $res->countSongs;
        return $count;
    }

    /**
     * Функция склонения числительных в русском языке
     *
     * @param int    $number Число которое нужно просклонять
     * @param array  $titles Массив слов для склонения
     * @return string
     **/
    public function declOfNum($number, $titles)
    {
        $cases = array (2, 0, 1, 1, 1, 2);
        return $number." ".$titles[ ($number%100 > 4 && $number %100 < 20) ? 2 : $cases[min($number%10, 5)] ];
    }

    public function getSongIds(){
        $stmt = $this->pdo->prepare("SELECT `id` FROM `Songs` WHERE `album` = :thisId ORDER BY albumOrder ASC");
        $stmt->bindParam(":thisId", $this->id, PDO::PARAM_INT);
        $stmt->execute();
        $array = array();
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            array_push($array, $row['id']);
        }
        return $array;
    }
}