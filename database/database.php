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
            $config = \HELPER\ConfigLoader::load("config/database.php");

        } catch (\Exception $e) {
            exit($e);
        }

        $error = 0;

        // Loop through each database server config, and try and find a suitable database server
        foreach($config as $dbServer) {

            try {

                // Create connection
                $this->connection = new \PDO($dbServer["DATABASE_DRIVER"] . ":dbname=" . $dbServer["DATABASE"] . ";charset=" . $dbServer["CHARSET"] . ";host=" . $dbServer["HOSTNAME"], $dbServer["USERNAME"], $dbServer["PASSWORD"]);
                
                // Set ATTR_ERRMODE to ERRMODE_EXCEPTION
                $this->connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

                // if $this->connection connected successfully to a database break the loop
                break;

            } catch(\PDOException $PDOException) {
                // Increment $error counter
                $error++;
            }

        }

        try {

            // If $error is the same as the amount of servers in the config file throw exception
            if($error === sizeof($config)) {
                throw new \Exception("Unable to connect to a database server.");
            }

        } catch (\Exception $exception) {
            exit($exception);
        }

        // Set ATTR_EMULATE_PREPARES to false
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