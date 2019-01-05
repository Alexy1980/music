<?php

class Song
{
    private $pdo;
    private $id;
    private $mysqliData;
    private $artistId;
    private $albumId;
    private $genre;
    private $duration;
    private $path;

    public function __construct($pdo, $id)
    {
        $this->pdo = $pdo;
        $this->id = $id;
        $query = $this->pdo->prepare("SELECT * FROM `Songs` WHERE `id` = :id");
        $query->bindParam(":id", $this->id, PDO::PARAM_INT);
        $query->execute();
        $this->mysqliData = $query->fetch(PDO::FETCH_ASSOC);
        $this->title = $this->mysqliData['title'];
        $this->artistId = $this->mysqliData['artist'];
        $this->albumId = $this->mysqliData['album'];
        $this->duration = $this->mysqliData['duration'];
        $this->genre = $this->mysqliData['genre'];
        $this->path = $this->mysqliData['path'];
    }

    public function getTitle(){
        return $this->title;
    }

    public function getArtist(){
        return new Artist($this->pdo, $this->artistId);
    }

    public function getAlbum(){
        return new Album($this->pdo, $this->albumId);
    }

    public function getDuration(){
        return $this->duration;
    }

    public function getGenre(){
        return $this->genre;
    }

    public function getPath(){
        return $this->path;
    }

    public function getId(){
        return $this->id;
    }

    public function getMysqliData(){
        return $this->mysqliData;
    }
}