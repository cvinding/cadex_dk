<?php
namespace DATABASE;

/**
 * Class Database
 * @package DATABASE
 * @author Christian Vinding Rasmussen
 * A PDO implementation of an Database class.
 */
class Database {

    /**
     * @var \PDO $connection
     */
    private $connection = null;

    /**
     * @var \PDOStatement $query
     */
    private $query = null;

    /**
     * __construct() is used for loading the Database config file
     * Also used for creating a connection to the database
     */
    public function __construct() {

        try {
            // Load the database config
            $config = \HELPER\ConfigLoader::load("config/database.php", ["DATABASE_DRIVER","HOSTNAME", "USERNAME", "PASSWORD", "DATABASE", "CHARSET"]);

        } catch (\Exception $e) {
            exit($e);
        }

        $this->connection = new \PDO("{$config["DATABASE_DRIVER"]}:dbname={$config["DATABASE"]};charset={$config["CHARSET"]};host={$config["HOSTNAME"]}", $config["USERNAME"], $config["PASSWORD"]);

        $this->connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->connection->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
    }

    /**
     * query() is a method for querying data and returning the current Object
     * @param string $query
     * @param array $bindable
     * @return $this
     */
    public function query(string $query, array $bindable = []) : Database {
        try {

            // Prepare the query
            $this->query = $this->connection->prepare($query);

            // Check if there is any bindable variables and use them if there are
            if(isset($bindable) && !empty($bindable)){

                // Execute with parameters
                $this->query->execute($bindable);

            } else {

                // Execute without parameters
                $this->query->execute();
            }

        } catch (\PDOException $PDOException) {
            exit($PDOException);
        }

        return $this;
    }

    /**
     * fetchAssoc() is a method for returning all selected rows as an associate array
     * @return array
     */
    public function fetchAssoc() : array {
        // Fetch the data
        $data = $this->query->fetchAll(\PDO::FETCH_ASSOC);

        // Set the query to NULL
        $this->query = NULL;

        // Return the data
        return $data;
    }

    /**
     * fetchArray() is a method for returning all selected rows as a numeric array
     * @return array
     */
    public function fetchArray() : array {
        // Fetch the data
        $data = $this->query->fetchAll(\PDO::FETCH_NUM);

        // Set the query to NULL
        $this->query = NULL;

        // Return the data
        return $data;
    }

    /**
     * affectedRows() is a method for returning the row count of all affected rows
     * @return int
     */
    public function affectedRows() : int {
        return $this->query->rowCount();
    }

    /**
     * getLastAutoID() is a method for returning the last inserted auto generated ID
     */
    public function getLastAutoID() : int {
        return (int) $this->connection->lastInsertId();
    }

    /**
     * __destruct() closes the connection
     */
    public function __destruct() {
        $this->connection = NULL;
    }

}