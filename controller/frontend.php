<?php

// load the classes
require_once('model/SettingsInstagramAPI.php');
require_once('model/InstagramAPI.php');

require_once('model/Manager.php');
require_once('model/InfluencerManager.php');


function getLoginURL()
{   
    // Get the API instagram settings
    $Instagram = new \DipsAgency\Site\Model\SettingsInstagramAPI();
    $settings = $Instagram->instaConnect();

    // Redirect to the login instagram page
    header ("Location: https://api.instagram.com/oauth/authorize/?client_id=".$settings['clientID']."&redirect_uri=".$settings['redirectURI']."&response_type=code&hl=en");
}

function getCallback($code)
{           
    // Get user instagram details
    $Insta = new \DipsAgency\Site\Model\InstagramAPI();
    $data = $Insta->getAccessTokenAndUserDetails($code);

    // Check if user is already registered
    $influencerManager = new \DipsAgency\Site\Model\InfluencerManager();
    $influencerRegistered = $influencerManager->checkInfluencer($data['user']['id']);
    

    if ($influencerRegistered ) {
        session_start();
        $_SESSION['instagram_id'] = $data['user']['id'];
        $_SESSION['id'] = $influencerRegistered['id'];
        // redirect to the profile page
        header('Location: influencers.php?action=profile&id=' . $influencerRegistered['id']);
    }
    else { // Else register the user in db
        // Get nb followers (JSON link)
        $json_source = file_get_contents('https://www.instagram.com/web/search/topsearch/?query={'.$data['user']['username'].'}');
        $json_data = json_decode($json_source, true); 
        $followers = $json_data['users']['0']['user']['follower_count'];
        
        $influencer = $influencerManager->createInfluencer($data['user']['id'], $data['user']['username'], $data['user']['full_name'], $data['user']['bio'], $data['user']['profile_picture'], $followers);

        session_start();
        $_SESSION['instagram_id'] = $data['user']['id'];
        $_SESSION['id'] = $data['user']['id'];
        // redirect to the profile page
        header('Location: influencers.php?action=profile&id=' . $influencer['id']);
    }
}

function logout()
{   
    session_start();
    
    $_SESSION = array();
    session_destroy();

    header('Location: influencers.php');
}

function influencerProfile($influencer_id)
{           
    // Get Profile details
    $influencerManager = new \DipsAgency\Site\Model\InfluencerManager();
    $influencer = $influencerManager->getInfluencerProfile($influencer_id);

    $categories = $influencerManager->getCategories();
    $categoriesChecked = $influencerManager->checkCategories($influencer['id']);

    // "checked" attribute for the checkbox elt in influencerProfileView.php
    while ($data = $categoriesChecked->fetch()) 
    {
        $checked[]=$data['category_id'];
    }
    
    $posts = $influencerManager->getPosts($influencer['id']);
    while ($post = $posts->fetch()) 
    {
    ?>
        <div><?= $post['title']; ?></div>
    <?php
    }
    

    require('view/frontend/influencerProfileView.php');    
}

function updateInfluencerProfile($influencer_id, $fullname, $email, $birthdate, $town, $category_id)
{           
    // Get Profile details
    $influencerManager = new \DipsAgency\Site\Model\InfluencerManager();
    $influencer = $influencerManager->getInfluencerProfile($influencer_id);
    $influencerManager->influencerProfileToUpdate($influencer_id, $fullname, $email, $birthdate, $town);
    
    // delete data in table categories_relationship
    $influencerManager->deleteCategoriesRelationship($influencer['id']);
    // add data in table categories_relationship
    foreach ($category_id as $category) {
        $influencerManager->addCategoriesRelationship($influencer['id'], $category);
    }
    
    header('Location: influencers.php?action=profile&id=' . $influencer['id']);
}

function isAjax()
{
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest';
}