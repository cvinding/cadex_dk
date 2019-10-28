<?php
namespace MODEL;

class NewsModel extends \MODEL\BASE\Model {

    public function __construct() {
        parent::__construct();
    }

    public function getNewsPosts() : array {
        return $this->database->query("SELECT n.id, n.title, n.content, u.username author, n.created_at FROM news n INNER JOIN users u ON n.author = u.id ORDER BY n.id DESC")->fetchAssoc();
    }

    public function getNewsPost(int $id) : array {
        return $this->database->query("SELECT n.id, n.title, n.content, u.username author, n.created_at FROM news n INNER JOIN users u ON n.author = u.id WHERE n.id = :id", ["id" => $id])->fetchAssoc();
    }

    public function createNewsPost(string $title, string $content, string $author) : bool {
        return ($this->database->query("INSERT INTO news (title, content, author) VALUES(:title, :content, (SELECT id FROM users WHERE username = :username))", ["title" => $title, "content" => $content, "username" => $author])->affectedRows() > 0);
    }

    public function updateNewsPost(int $id, string $title, string $content, string $author) : bool {
        
        // Select entry to see if exists
        $entry = $this->database->query("SELECT id FROM news WHERE id = :id", ["id" => $id])->fetchAssoc();

        // If it does not exists return false
        if(empty($entry)) {
            return false;
        }

        $this->database->query("UPDATE news SET title = :title, content = :content, author = (SELECT id FROM users WHERE username = :author) WHERE id = :id", ["title" => $title, "content" => $content, "author" => $author, "id" => $id])->affectedRows();

        // Return true
        return true;
    }

    public function deleteNewsPost(int $id) : bool {
        return ($this->database->query("DELETE FROM news WHERE id = :id", ["id" => $id])->affectedRows() > 0);
    }

}