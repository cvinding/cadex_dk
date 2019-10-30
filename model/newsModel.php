<?php
namespace MODEL;

/**
 * Class NewsModel
 * @package MODEL
 * @author Christian Vinding Rasmussen
 * The NewsModel is used for selecting, inserting, updating and deleting news posts
 */
class NewsModel extends \MODEL\BASE\Model {

    /**
     * __construct() call parent::__construct() for setting the Database instance
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * getNewsPosts() is a method for returning all news posts
     * @param int $page
     * @return array
     */
    public function getNewsPosts(int $page) : array {

        $sql = "SELECT n.id, n.title, n.content, u.username author, n.created_at FROM news n INNER JOIN users u ON n.author = u.id ORDER BY n.id DESC LIMIT :limit OFFSET :offset";

        $bindable = [
            "limit" => $this->getLimit($page),
            "offset" => $this->getOffset($page)
        ];

        return $this->database->query($sql, $bindable)->fetchAssoc();
    }

    /**
     * getNewsPost() is a method for returning the specified news post
     * @param int $id
     * @return array
     */
    public function getNewsPost(int $id) : array {
        return $this->database->query("SELECT n.id, n.title, n.content, u.username author, n.created_at FROM news n INNER JOIN users u ON n.author = u.id WHERE n.id = :id", ["id" => $id])->fetchAssoc();
    }

    /**
     * createNewsPost() is a method for creating news posts
     * @param string $title
     * @param string $content
     * @param string $author
     * @return bool
     */
    public function createNewsPost(string $title, string $content, string $author) : bool {
        return ($this->database->query("INSERT INTO news (title, content, author) VALUES(:title, :content, (SELECT id FROM users WHERE username = :username))", ["title" => $title, "content" => $content, "username" => $author])->affectedRows() > 0);
    }

    /**
     * updateNewsPost() is a method for updating news posts
     * @param int $id
     * @param string $title
     * @param string $content
     * @param string $author
     * @return bool
     */
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

    /**
     * deleteNewsPost() is a method for deleting news posts
     * @param int $id
     * @return bool
     */
    public function deleteNewsPost(int $id) : bool {
        return ($this->database->query("DELETE FROM news WHERE id = :id", ["id" => $id])->affectedRows() > 0);
    }

}