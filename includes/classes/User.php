<?php

class User
{
    private $pdo;
    private $username;


    public function __construct($pdo, $username)
    {
        $this->pdo = $pdo;
        $this->username = $username;
    }

    public function getUsername(){
        return $this->username;
    }

    public function getFirstAndLastName(){
        $stmt = $this->pdo->prepare("SELECT concat(firstName, ' ', lastName) AS `name` FROM `users` WHERE `username` = :username");
        $stmt->bindParam(":username", $this->username, PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['name'];
    }

    public function getEmail(){
        $stmt = $this->pdo->prepare("SELECT * FROM `users` WHERE `username` = :username");
        $stmt->bindParam(":username", $this->username, PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['email'];
    }
}