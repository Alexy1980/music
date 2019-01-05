<?php

class Artist
{
    private $pdo;
    private $id;

    public function __construct($pdo, $id)
    {
        $this->pdo = $pdo;
        $this->id = $id;
    }

    public function getName(){
        $artistId = $this->id;
        $artistQuery = $this->pdo->prepare("SELECT `name` FROM `artists` WHERE `id` = :artistId");
        $artistQuery->bindParam(":artistId", $artistId, PDO::PARAM_INT);
        $artistQuery->execute();
        $artist = $artistQuery->fetch(PDO::FETCH_ASSOC);
        return $artist['name'];
    }

    public function getSongIds(){
        $stmt = $this->pdo->prepare("SELECT `id` FROM `Songs` WHERE `artist` = :thisId ORDER BY plays DESC ");
        $stmt->bindParam(":thisId", $this->id, PDO::PARAM_INT);
        $stmt->execute();
        $array = array();
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            array_push($array, $row['id']);
        }
        return $array;
    }

    public function getId(){
        return $this->id;
    }
}