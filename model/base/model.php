<?php
namespace MODEL\BASE;

class Model {

    /**
     * An instance of the Database class
     * @var \DATABASE\MYSQLI\Database $database
     */
    protected $database;

    private $maxEntries = 25;

    public function __construct() {
        $this->database = new \DATABASE\MYSQLI\Database();
    }

    protected function getLimit(int $page) : int {
        return ($this->maxEntries * abs($page));
    }

    protected function getOffset(int $page) : int {
        return ($this->maxEntries * abs($page)) - $this->maxEntries;
    }
    
}