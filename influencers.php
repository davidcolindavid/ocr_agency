<?php
session_start();
require('controller/frontend.php');

try {
    if (isset($_SESSION['instagram_id'])) {
        if (isset($_GET['action'])) {
            // profile page
            if ($_GET['action'] == 'profile' && $_GET['id'] == $_SESSION['id']) {
                if (isset($_GET['id']) && $_GET['id'] > 0) {
                    influencerProfile($_GET['id']);
                }
                else {
                    throw new Exception('Aucun identifiant de profile envoyé');
                }
            }
            // Update profile page
            elseif ($_GET['action'] == 'updateProfile' && $_GET['id'] == $_SESSION['id']) {
                if (isset($_GET['id']) && $_GET['id'] > 0) {
                    updateInfluencerProfile($_GET['id'], $_POST['fullname'], $_POST['email'], $_POST['birthdate'], $_POST['town'], $_POST['category_id']);
                }
                else {
                    throw new Exception('Aucun identifiant de profile envoyé');
                }
            }
            elseif ($_GET['action'] == 'logout') {
                logout();
            }
            else {
                throw new Exception('Page introuvable');
            }
        }
        else {
            header('Location: influencers.php?action=profile&id=' . $_SESSION['id']);
        }
    }
    else {
    // login page
        if (isset($_GET['action'])) {
            if ($_GET['action'] == 'getLoginURL') {
                getLoginURL();
            }
            else if ($_GET['action'] == 'callback') {
                getCallback($_GET['code']);
            }
        }
        else {
            require('view/frontend/influencersLoginView.php');
        }
    }
}
catch(Exception $e) {
    echo 'Erreur : ' . $e->getMessage();
}
