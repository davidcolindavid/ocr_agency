<?php
session_start();
require('controller/backend.php');

try {
    if (isset($_SESSION['id'])) {
        if (isset($_GET['action'])) {
            if ($_GET['action'] == 'listPosts') {
                listPosts();
            }
            elseif ($_GET['action'] == 'addPost') {
                addPost($_POST['category_id']);
            }
            elseif ($_GET['action'] == 'deletePost') {
                if (isset($_GET['id']) && $_GET['id'] > 0) {
                    deletePost();
                }
                else {
                    throw new Exception('Aucun identifiant de billet envoyé');
                }
            }
            elseif ($_GET['action'] == 'editPost') {
                if (isset($_GET['id']) && $_GET['id'] > 0) {
                    editPost();
                }
                else {
                    throw new Exception('Aucun identifiant de billet envoyé');
                }
            }
            elseif ($_GET['action'] == 'updatePost') {
                if (isset($_GET['id']) && $_GET['id'] > 0) {
                    if (!empty($_POST['post_title']) && !empty($_POST['post_content'])) {
                        updatePost($_GET['id'], $_POST['post_title'], $_POST['post_place'], $_POST['post_address'], $_POST['post_date'], $_POST['post_content'], $_POST['category_id']);
                    }
                    else {
                        throw new Exception('Tous les champs ne sont pas remplis !');
                    }
                }
                else {
                    throw new Exception('Aucun identifiant de billet envoyé');
                }
            }
            elseif ($_GET['action'] == 'uploadImage') {
                uploadImage();
            }
            elseif ($_GET['action'] == 'logout') {
                logout();
            }
            elseif ($_GET['action'] == 'influencers') {
                if (isset($_GET['cat']) && $_GET['cat'] > 0) {
                    listInfluencersCategory($_GET['cat']);
                }
                else {
                    listInfluencers();
                }
            }
        }
        else {
            listPosts();
        }
    }
    else {
    // login page
        if (isset($_GET['action'])) {
            if ($_GET['action'] == 'loginAdmin') {
                if (!empty($_POST['username']) && !empty($_POST['password'])) {
                    loginAdmin($_POST['username'], $_POST['password']);
                }
                else {
                    throw new Exception('Tous les champs ne sont pas remplis !');
                }
            }
            else {
                require('view/backend/loginView.php');
            }
        }
        else {
            require('view/backend/loginView.php');
        }
    }
}
catch(Exception $e) {
    echo 'Erreur : ' . $e->getMessage();
}
