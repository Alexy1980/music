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
}