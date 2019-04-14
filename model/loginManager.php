<?php

namespace DipsAgency\Site\Model;

require_once("model/Manager.php");

class LoginManager extends Manager
{
    public function getPass($username, $password)
    {
        // get the user and the hash password
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT * FROM ag_user WHERE pseudo = :username');
        $req->execute(array(
            'username' => $username,
        ));
        $result = $req->fetch();

        // compare the 2 passwords
        $correctPassword = password_verify($password, $result['pass']);

        return $correctPassword;
    }

    public function getUser($username)
    {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT id, pseudo FROM ag_user WHERE pseudo = :username');
        $req->execute(array(
            'username' => $username,
        ));
        $result = $req->fetch();

        return $result;
    }
}