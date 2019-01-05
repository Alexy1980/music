<?php

class Account
{
    private $pdo;
    private $errorArray;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
        $this->errorArray = array();
    }

    public function register($un, $fn, $ln, $em, $em2, $pw, $pw2){
        $this->validateUsername($un);
        $this->validateFirstName($fn);
        $this->validateLastName($ln);
        $this->validateEmails($em, $em2);
        $this->validatePasswords($pw, $pw2);
        if(empty($this->errorArray) == true){
            // insert into db
            $this->insertUserDetails($un, $fn, $ln, $em, $pw);
            return true;
        } else {
            return false;
        }
    }

    public function getError($error){
        if(!in_array($error, $this->errorArray)){
            $error = "";
        }
        return "<span class='errorMessage'>$error</span>";
    }

    public function login($un, $pw){
        $pw = md5($pw);
        $stmt = $this->pdo->prepare("SELECT COUNT(*) AS `totalCount` FROM `users` WHERE `username` = :un AND `password` = :pw");
        $stmt->bindParam(":un", $un, PDO::PARAM_STR);
        $stmt->bindParam(":pw", $pw, PDO::PARAM_STR);
        $stmt->execute();
        $res = $stmt->fetch(PDO::FETCH_OBJ);
        $count = $res->totalCount;
        if($count == 1){
            return true;
        } else {
            array_push($this->errorArray, Constants::$loginFail);
            return false;
        }
    }

    private function insertUserDetails($un, $fn, $ln, $em, $pw){
        $encriptedPw = md5($pw);
        $profilePic = "assets/images/profile-pics/head_emerald.png";
        $dateT = date("Y-m-d H:i:s");
        $stmt = $this->pdo->prepare("INSERT INTO `users` (`username`, `firstName`, `lastName`, `email`, `password`, `signUpDate`, `profilePic`) VALUES (:un, :fn, :ln, :em, :encriptedPw, :dateT, :profilePic) ");
        $stmt->bindParam(":un", $un, PDO::PARAM_STR);
        $stmt->bindParam(":fn", $fn, PDO::PARAM_STR);
        $stmt->bindParam(":ln", $ln, PDO::PARAM_STR);
        $stmt->bindParam(":em", $em, PDO::PARAM_STR);
        $stmt->bindParam(":profilePic", $profilePic, PDO::PARAM_STR);
        $stmt->bindParam(":encriptedPw", $encriptedPw, PDO::PARAM_STR);
        $stmt->bindParam(":dateT", $dateT, PDO::PARAM_STR);
        $stmt->execute();
    }

    private function validateUsername($un){
        if(strlen($un) > 25 || strlen($un) < 5){
            array_push($this->errorArray, Constants::$usernameNotLength);
            return;
        }
        $stmt = $this->pdo->prepare("SELECT `username` FROM `users` WHERE `username` = :un");
        $stmt->bindParam(":un", $un, PDO::PARAM_STR);
        $stmt->execute();
        $count = $stmt->rowCount();
        if($count != 0){
            array_push($this->errorArray, Constants::$usernameTaken);
            return;
        }
    }

    private function validateFirstName($fn){
        if(strlen($fn) > 25 || strlen($fn) < 2){
            array_push($this->errorArray, Constants::$firstNameNotLength);
            return;
        }
        //TODO: check if FirstName exists
    }

    private function validateLastName($ln){
        if(strlen($ln) > 25 || strlen($ln) < 2){
            array_push($this->errorArray, Constants::$lastNameNotLength);
            return;
        }
        //TODO: check if LastName exists
    }

    private function validateEmails($em, $em2){
        if($em != $em2){
            array_push($this->errorArray, Constants::$emailsDoNotMatch);
            return;
        }
        if(!filter_var($em, FILTER_VALIDATE_EMAIL) || !filter_var($em2, FILTER_VALIDATE_EMAIL)){
            array_push($this->errorArray, Constants::$emailsDoNotValid);
            return;
        }
        $stmt = $this->pdo->prepare("SELECT `email` FROM `users` WHERE `email` = :em");
        $stmt->bindParam(":em", $em, PDO::PARAM_STR);
        $stmt->execute();
        $count = $stmt->rowCount();
        if($count != 0){
            array_push($this->errorArray, Constants::$emailTaken);
            return;
        }
    }

    private function validatePasswords($pw, $pw2){
        if($pw != $pw2){
            array_push($this->errorArray, Constants::$passwordsDoNotMatch);
            return;
        }
        if(preg_match('/[^A-Za-z0-9]/', $pw)){
            array_push($this->errorArray, Constants::$passwordsDoNotValid);
            return;
        }
        if(strlen($pw) > 25 || strlen($pw) < 6){
            array_push($this->errorArray, Constants::$passwordsNotLength);
            return;
        }
    }
}