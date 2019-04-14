<?php

namespace DipsAgency\Site\Model;

require_once("model/Manager.php");

class AdminPostManager extends Manager
{
    public function getPosts()
    {
        $db = $this->dbConnect();
        $req = $db->query('SELECT id, title, place, address_event, DATE_FORMAT(date_event, \'%d %M %Y à %Hh%i\') AS date_event, content, DATE_FORMAT(creation_date, \'%d/%m/%Y à %Hh%i\') AS creation_date_fr FROM ag_posts ORDER BY creation_date DESC');

        return $req;
    }

    public function getPost($postId)
    {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT id, title, place, address_event, DATE_FORMAT(date_event, \'%d %M %Y à %Hh%i\') AS date_event, content, DATE_FORMAT(creation_date, \'%d/%m/%Y à %Hh%i\') AS creation_date_fr FROM ag_posts WHERE id = ?');
        $req->execute(array($postId));
        $post = $req->fetch();

        return $post;
    }

    public function getPostAndCategories($postId)
    {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT * FROM ag_posts LEFT JOIN ag_categories_relationship ON ag_posts.id = ag_categories_relationship.post_id WHERE ag_posts.id = ?');
        $req->execute(array($postId));
        $post = $req->fetch();

        return $post;
    }

    public function getLastPost()
    {
        $db = $this->dbConnect();
        $req = $db->query('SELECT id, title, place, address_event, DATE_FORMAT(date_event, \'%d %M %Y à %Hh%i\') AS date_event, content, DATE_FORMAT(creation_date, \'%d/%m/%Y à %Hh%i\') AS creation_date_fr FROM ag_posts ORDER BY creation_date DESC');
        $LastPost = $req->fetch();

        return $LastPost;
    }

    public function postToAdd()
    {
        $db = $this->dbConnect();
        $req = $db->prepare('INSERT INTO ag_posts(title, place, address_event, date_event, content, creation_date) VALUES(:title, :place, :address_event, :date_event, :content, NOW())');
        $affectedLines = $req->execute(array(
            'title' => $_POST['post_title'],
            'place' => $_POST['post_place'],
            'address_event' => $_POST['post_address'],
            'date_event' => $_POST['post_date'],
            'content' => $_POST['post_content']
        )); 

        return $affectedLines;
    }

    public function postToDelete($postId)
    {
        $db = $this->dbConnect();
        $req = $db->prepare('DELETE FROM ag_posts WHERE id = ?');
        $affectedLines = $req->execute(array($postId)); 

        return $affectedLines;
    }

    public function postAndCategoriesToDelete($postId)
    {
        $db = $this->dbConnect();
        $req = $db->prepare('DELETE ag_posts, ag_categories_relationship FROM ag_posts INNER JOIN ag_categories_relationship ON ag_posts.id = ag_categories_relationship.post_id WHERE ag_posts.id = ?');
        $affectedLines = $req->execute(array($postId)); 

        return $affectedLines;
    }

    public function postToEdit($postId)
    {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT id, title, place, address_event, date_event, content, DATE_FORMAT(creation_date, \'%d/%m/%Y à %Hh%i\') AS creation_date_fr FROM ag_posts WHERE id = ?');
        $req->execute(array($postId));
        $form = $req->fetch();

        return $form;
    }

    public function postToUpdate($postId, $title, $place, $address_event, $date_event, $content)
    {
        $db = $this->dbConnect();
        $req = $db->prepare('UPDATE ag_posts SET title = ?, place = ?, address_event = ?, date_event = ?, content = ? WHERE id = ?');
        $affectedLines = $req->execute(array($title, $place, $address_event, $date_event, $content, $postId)); 

        return $affectedLines;
    }

    public function getCategories()
    {
        $db = $this->dbConnect();
        $req = $db->query('SELECT * FROM ag_categories');

        return $req;
    }

    public function addCategoriesRelationship($post_id, $category_id)
    {
        $db = $this->dbConnect();
        $req = $db->prepare('INSERT INTO ag_categories_relationship SET post_id = ?, category_id = ?');
        $affectedLines = $req->execute(array($post_id, $category_id)); 

        return $affectedLines;
    }

    public function deleteCategoriesRelationship($post_id)
    {
        $db = $this->dbConnect();
        $req = $db->prepare('DELETE FROM ag_categories_relationship WHERE post_id = ?');
        $affectedLines = $req->execute(array($post_id)); 

        return $affectedLines;
    }

    public function getCategoriesChecked($post_id)
    {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT category_id FROM ag_categories_relationship WHERE post_id = ?');
        $return = $req->execute(array($post_id));

        return $return;
    }
}