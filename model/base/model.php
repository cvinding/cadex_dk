<?php
namespace MODEL\BASE;

/**
 * Class Model
 * @package MODEL\BASE
 * @author Christian Vinding Rasmussen
 * The base class of all models
 */
class Model {

    /**
     * An instance of the Database class
     * @var \DATABASE\MYSQLI\Database $database
     */
    protected $database;

    /**
     * The max database entries
     * @var int $maxEntries
     */
    private $maxEntries;

    /**
     * __construct() creates a new \DATABASE\MYSQLI\Database instance
     * @param int $maxEntries = 25
     */
    public function __construct(int $maxEntries = 25) {
        $this->maxEntries = $maxEntries;
        $this->database = new \DATABASE\MYSQLI\Database();
    }

    /**
     * getLimit() is used to return the limit based on which $page the user is on. 
     * Used for pagenavigation in the API.
     * @param int $page
     * @return int
     */
    protected function getLimit(int $page) : int {
        return $this->maxEntries;
    }

    /**
     * getOffset() is used to return the offset based on which $page the user is on.
     * Used for pagenavigation in the API.
     * @param int $page
     * @return void
     */
    protected function getOffset(int $page) : int {
        return ($this->maxEntries * abs($page)) - $this->maxEntries;
    }
    
}