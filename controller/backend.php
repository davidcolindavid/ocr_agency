<?php

// load the classes
require_once('model/loginManager.php');
require_once('model/AdminPostManager.php');
require_once('model/InfluencerManager.php');


// Login Page

function loginAdmin($username, $password)
{   
    $loginManager = new \DipsAgency\Site\Model\LoginManager(); // creation of an object
    $correctPassword = $loginManager->getPass($username, $password); // call a function of this object
    $user =  $loginManager->getUser($username);

    if($correctPassword){
        session_start();
        $_SESSION['id'] = $user['id'];
        $_SESSION['pseudo'] = $username;
        // redirect to the admin page width ajax (backend.js)
        echo "success";
	}else{
		// Report an error: wrong id or password width ajax (backend.js)
        echo "fail";
	}
}

function logout()
{   
    session_start();
    
    $_SESSION = array();
    session_destroy();

    header('Location: admin.php');
}

// Post Page

function listPosts()
{
    $postManager = new \DipsAgency\Site\Model\AdminPostManager();
    $posts = $postManager->getPosts();
    $categories = $postManager->getCategories();

    $influencerManager = new \DipsAgency\Site\Model\InfluencerManager();
    $topInfluencers = $influencerManager->getTopInfluencersProfile();
    

    // editor and fields init
    $formTitle = "";
    $formPlace = "";
    $formAddress = "";
    $formDate = "";
    $formContent = "Exprimez-vous";
    $formAction = "admin.php?action=addPost";

    require('view/backend/adminView.php');
}

function addPost($category_id)
{
    $postManager = new \DipsAgency\Site\Model\AdminPostManager();
    $affectedLines = $postManager->postToAdd();
    $lastpost = $postManager->getLastPost();

    $categories = $postManager->getCategories();
    // add data in table categories_relationship
    foreach ($category_id as $category) {
        $postManager->addCategoriesRelationship($lastpost['id'], $category);
    }

    // editor and fields init
    $lastPostId = $lastpost['id'];
    $lastPostTitle = $lastpost['title'];
    $lastPostPlace = $lastpost['place'];
    $lastPostAddress = $lastpost['address_event'];
    $lastPostDateEvent = $lastpost['date_event'];
    $lastContent = $lastpost['content'];
    $lastPostDate = $lastpost['creation_date_fr'];

    if (isAjax()) { 
        $array = [$lastPostId, $lastPostTitle, $lastPostPlace, $lastPostAddress, $lastPostDateEvent, $lastContent, $lastPostDate];
        header('Content-type: application/json');
        echo json_encode($array); // transform the array into JSON
    }
    else {
        if ($affectedLines === false) {
            throw new Exception('Impossible d\'ajouter le commentaire !');
        }
        else {
            header('Location: admin.php');
        }
    }
}

function editPost()
{   
    $postManager = new \DipsAgency\Site\Model\AdminPostManager();
    $posts = $postManager->getPosts();
    $form = $postManager->postToEdit($_GET['id']);
    $categories = $postManager->getCategories();
    // "checked" attribute for the checkbox elt in adminView.php
    $categoryChecked = $postManager->checkCategories($_GET['id']);

    $influencerManager = new \DipsAgency\Site\Model\InfluencerManager();
    $topInfluencers = $influencerManager->getTopInfluencersProfile();
    
    // editor and fields init
    $formTitle = $form['title'];
    $formPlace = $form['place'];
    $formAddress = $form['address_event'];
    $formDate = $form['date_event'];
    $formContent = $form['content'];
    $formAction = "admin.php?action=updatePost&id=" . $form['id'];

    if (isAjax()) { 
        $array = [$formTitle, $formPlace, $formAddress, $formDate, $formContent, $formAction, $categoryChecked['category_id']];
        header('Content-type: application/json');
        echo json_encode($array);
    }
    else {
        require('view/backend/adminView.php');
    }
}

function updatePost($postId, $title, $place, $address, $date_event, $content, $category_id)
{
    $postManager = new \DipsAgency\Site\Model\AdminPostManager();
    $affectedLines = $postManager->PostToUpdate($postId, $title, $place, $address, $date_event, $content);
    $post = $postManager->getPost($postId);

    $categories = $postManager->getCategories();
    // delete data in table categories_relationship
    $postManager->deleteCategoriesRelationship($postId);
    // add data in table categories_relationship
    foreach ($category_id as $category) {
        $postManager->addCategoriesRelationship($postId, $category);
    }

    if (isAjax()) { 
        $array = [$post['title'], $post['place'], $post['address_event'], $post['date_event'], $post['content']];
        header('Content-type: application/json');
        echo json_encode($array);
    }
    else {
        if ($affectedLines === false) {
            throw new Exception('Impossible de mettre à jour le billet !');
        }
        else {
            header('Location: admin.php');
        }
    }
}

function deletePost()
{
    $postManager = new \DipsAgency\Site\Model\AdminPostManager();    
    $categoryFound = $postManager->getPostAndCategories($_GET['id']);

    if ($categoryFound['category_id']) {
        $affectedLines = $postManager->postAndCategoriesToDelete($_GET['id']);
    }
    else {
        $affectedLines = $postManager->postToDelete($_GET['id']);
    }

    if ($affectedLines === false) {
        throw new Exception('Impossible de supprimer le billet !');
    }
    elseif ($affectedLinesComm === false) {
        throw new Exception('Impossible de supprimer les commentaires associés au billet !');
    }
    else {
        header('Location: admin.php');
    }
}

function uploadImage()
{
    // Allowed origins to upload images
    $accepted_origins = array("http://localhost:8888/", "http://david-colin.com");

    // Images upload path
    $imageFolder = "public/images/";

    reset($_FILES);
    $temp = current($_FILES);
    if(is_uploaded_file($temp['tmp_name'])){
        if(isset($_SERVER['HTTP_ORIGIN'])){
            // Same-origin requests won't set an origin. If the origin is set, it must be valid.
            if(in_array($_SERVER['HTTP_ORIGIN'], $accepted_origins)){
                header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
            }else{
                header("HTTP/1.1 403 Origin Denied");
                return;
            }
        }
    
        // Sanitize input
        if(preg_match("/([^\w\s\d\-_~,;:\[\]\(\).])|([\.]{2,})/", $temp['name'])){
            header("HTTP/1.1 400 Invalid file name.");
            return;
        }
    
        // Verify extension
        if(!in_array(strtolower(pathinfo($temp['name'], PATHINFO_EXTENSION)), array("gif", "jpg", "png"))){
            header("HTTP/1.1 400 Invalid extension.");
            return;
        }
    
        // Accept upload if there was no origin, or if it is an accepted origin
        $filetowrite = $imageFolder . $temp['name'];
        move_uploaded_file($temp['tmp_name'], $filetowrite);
    
        // Respond to the successful upload with JSON.
        echo json_encode(array('location' => $filetowrite));
    } else {
        // Notify editor that the upload failed
        header("HTTP/1.1 500 Server Error");
    }
}


// Profile page

function listInfluencers()
{
    $influencerManager = new \DipsAgency\Site\Model\InfluencerManager();
    $influencers = $influencerManager->getInfluencersProfiles();

    $postManager = new \DipsAgency\Site\Model\AdminPostManager();
    $categories = $postManager->getCategories();

    require('view/backend/profilesView.php');
}

function listInfluencersCategory($category_id)
{
    $influencerManager = new \DipsAgency\Site\Model\InfluencerManager();
    $influencers = $influencerManager->getInfluencersProfilesCategory($category_id);

    $postManager = new \DipsAgency\Site\Model\AdminPostManager();
    $categories = $postManager->getCategories();
    
    require('view/backend/profilesView.php');
}

function isAjax()
{
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest';
}