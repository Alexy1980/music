<?php

class Playlist
{
    private $pdo;
    private $id;
    private $name;
    private $owner;

    public function __construct($pdo, $data)
    {
        if(!is_array($data)){
            $stmt = $pdo->prepare("SELECT * FROM `playlists` WHERE `id` = :dat");
            $stmt->bindParam(":dat", $data, PDO::PARAM_INT);
            $stmt->execute();
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
        }
        $this->pdo = $pdo;
        $this->id = $data['id'];
        $this->name = $data['name'];
        $this->owner = $data['owner'];
    }

    public function getId(){
        return $this->id;
    }

    public function getName(){
        return $this->name;
    }

    public function getOwner(){
        return $this->owner;
    }

    public function getNumberOfSongs() {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) AS `countSongs` FROM `playlistSongs` WHERE `playlistId` = :thisId");
        $stmt->bindParam(":thisId", $this->id, PDO::PARAM_INT);
        $stmt->execute();
        $res = $stmt->fetch(PDO::FETCH_OBJ);
        $count = $res->countSongs;
        return $count;
    }

    public function getSongIds(){
        $stmt = $this->pdo->prepare("SELECT `songId` FROM `playlistSongs` WHERE `playlistId` = :thisId ORDER BY playlistOrder ASC");
        $stmt->bindParam(":thisId", $this->id, PDO::PARAM_INT);
        $stmt->execute();
        $array = array();
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            array_push($array, $row['songId']);
        }
        return $array;
    }

    public static function getPlaylistsDropdown($pdo, $username){
        $dropdown = '<select name="" id="" class="item playlist">
                        <option value="">Добавить в плейлист:</option>';
        $stmt = $pdo->prepare("SELECT `id`, `name` FROM `playlists` WHERE `owner` = :username");
        $stmt->bindParam(":username", $username, PDO::PARAM_STR);
        $stmt->execute();
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $id = $row['id'];
            $name = $row['name'];
            $dropdown = $dropdown."<option value='$id'>$name</option>";
        }
        return $dropdown."</select>";
    }
}