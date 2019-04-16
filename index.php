<?php
session_start();
require('controller/frontend.php');

try {
    if (isset($_GET['action'])) {
        if ($_GET['action'] == 'loginAdmin') {
            if (!empty($_POST['username']) && !empty($_POST['password'])) {
                loginAdmin($_POST['username'], $_POST['password']);
            }
            else {
                throw new Exception('Tous les champs ne sont pas remplis !');
            }
        }
    }
    else {
        listInfluencers();
    }
}
catch(Exception $e) {
    echo 'Erreur : ' . $e->getMessage();
}

