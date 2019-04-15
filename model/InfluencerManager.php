<?php

namespace DipsAgency\Site\Model;

require_once("model/Manager.php");

class InfluencerManager extends Manager
{
    public function checkInfluencer($insta_id)
    {
        // Check if instagram ID exist
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT * FROM ag_influencers WHERE instagram_id = ?');
        $req->execute([$insta_id]); 
        $result = $req->fetch();

        return $result;
    }
    
    public function createInfluencer($insta_id, $username, $fullname, $bio, $profile_picture, $followers)
    {
        $db = $this->dbConnect();
        $req = $db->prepare('INSERT INTO ag_influencers SET instagram_id = ?, username = ?, fullname = ?, followers = ?, bio = ?, profile_picture = ?, creation_date = NOW()');
        $req->execute([$insta_id, $username, $fullname, $followers, $bio, $profile_picture]);
    }

    public function getInfluencerProfile($influencer_id)
    {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT *, TIMESTAMPDIFF(YEAR, birthdate, CURDATE()) AS age FROM ag_influencers LEFT JOIN ag_influencers_categories ON ag_influencers.id = ag_influencers_categories.influencer_id WHERE id = 9');
        $req->execute(array($influencer_id));
        $result = $req->fetch();

        return $result;
    }

    public function getInfluencersProfiles()
    {
        $db = $this->dbConnect();
        $req = $db->query('SELECT *, TIMESTAMPDIFF(YEAR, birthdate, CURDATE()) AS age FROM ag_influencers');

        return $req;
    }

    public function getInfluencersProfilesCategory($category_id)
    {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT * FROM ag_influencers LEFT JOIN ag_influencers_categories ON ag_influencers.id = ag_influencers_categories.influencer_id WHERE ag_influencers_categories.category_id = ?');
        $req->execute(array($category_id));

        return $req;
    }

    public function getTopInfluencersProfile($influencer_id)
    {
        $db = $this->dbConnect();
        $req = $db->query('SELECT *, TIMESTAMPDIFF(YEAR, birthdate, CURDATE()) AS age FROM ag_influencers ORDER BY followers DESC LIMIT 0, 5 ');

        return $req;
    }

    public function influencerProfileToUpdate($influencer_id, $fullname, $email, $birthdate, $town)
    {
        $db = $this->dbConnect();
        $req = $db->prepare('UPDATE ag_influencers SET fullname = ?, email = ?, birthdate = ?, town = ? WHERE id = ?');
        $affectedLines = $req->execute(array($fullname, $email, $birthdate, $town, $influencer_id)); 

        return $affectedLines;
    }

    public function getCategories()
    {
        $db = $this->dbConnect();
        $req = $db->query('SELECT * FROM ag_categories');

        return $req;
    }

    public function addCategoriesRelationship($influencer_id, $category_id)
    {
        $db = $this->dbConnect();
        $req = $db->prepare('INSERT INTO ag_influencers_categories SET influencer_id = ?, category_id = ?');
        $affectedLines = $req->execute(array($influencer_id, $category_id)); 

        return $affectedLines;
    }

    public function deleteCategoriesRelationship($influencer_id)
    {
        $db = $this->dbConnect();
        $req = $db->prepare('DELETE FROM ag_influencers_categories WHERE influencer_id = ?');
        $affectedLines = $req->execute(array($influencer_id)); 

        return $affectedLines;
    }

    public function checkCategories($influencer_id)
    {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT category_id FROM ag_influencers_categories WHERE influencer_id = ?');
        $req->execute(array($influencer_id));

        return $req;
    }

    public function getPosts($category_id)
    {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT * FROM ag_posts LEFT JOIN ag_posts_categories ON ag_posts.id = ag_posts_categories.post_id WHERE ag_posts_categories.category_id = ?');
        $req->execute(array($category_id));

        return $req;
    }
}