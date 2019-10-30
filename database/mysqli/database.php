<?php
namespace DATABASE\MYSQLI;

/**
 * Class Database
 * @package DATABASE\MYSQLI
 * @author Christian Vinding Rasmussen
 * Database is the MySQLi version of PDO Database class.
 * This class is should be used when a mysql/mariadb database is used.
 */
class Database {

    /**
     * @var \mysqli $connection
     */
    private $connection = null;

    /**
     * @var \mysqli_stmt $query
     */
    private $query = null;

    /**
     * __construct() loads database config and create connection.
     */
    public function __construct() {

        try {

            // Load the database config
            $config = \HELPER\ConfigLoader::load("config/database.php");
            
            $error = 0;

            // Loop through each config and check if there are any available servers
            foreach($config as $dbServer) {

                // Check if specified driver is 'mysql'
                if($dbServer["DATABASE_DRIVER"] !== "mysql") {
                    throw new \Exception("Specified DATABASE_DRIVER does not support mysql database");
                }

                // Create connection
                $this->connection = new \mysqli($dbServer["HOSTNAME"], $dbServer["USERNAME"], $dbServer["PASSWORD"], $dbServer["DATABASE"]);

                // If no error occurred set $config to this $dbServer and break the loop
                if($this->connection->connect_errno === 0) {
                    
                    $currentConfig = $dbServer;
                    break;
                
                // Increment $error counter 
                } else {

                    $error++;
                }
    
            }
           
            // If $error is the same as the amount of servers in the config file throw exception
            if($error === sizeof($config)) {
                throw new \Exception("Unable to connect to a database server.");
            }

        } catch (\Exception $exception) {
            exit($exception);
        }

        // Set charset
        $this->connection->set_charset($currentConfig["CHARSET"]);
    }

    /**
     * query() is used for querying SQL syntax
     * @param string $sql
     * @param array $bindable
     * @return Database
     */
    public function query(string $sql, array $bindable = []) : Database {

        if(!empty($bindable)) {

            $newFormat = $this->formatQuery($sql, $bindable);

            $sql = $newFormat["SQL"];
            $bindable = $newFormat["BINDABLE"];
        }

        // Prepare the SQL
        $this->query = $this->connection->prepare($sql);

        // Bind variables if there are any
        if(!empty($bindable)) {
            $typeString = "";
            $references = [];

            // Get variable type, and create references to values
            foreach($bindable as $key => &$value) {
                $typeString .= substr(gettype($value),0,1);

                $references[] = &$value;
            }

            // Merge $typeString and $references
            $variables = array_merge([$typeString], $references);

            // Call bind_param()
            call_user_func_array([$this->query, "bind_param"], $variables);
        }

        // Execute query
        $this->query->execute();

        // Return $this
        return $this;
    }

    /**
     * formatQuery() is a method for creating support for named placeholders.
     * e.g. "SELECT * FROM news WHERE id = :id" turns into => "SELECT * FROM news WHERE id = ?"
     * @param string $sql
     * @param array $bindable
     * @return array
     */
    private function formatQuery(string $sql, array $bindable) : array {

        $tempBindable = [];

        // Loop through all translations in the $bindable array
        foreach($bindable as $key => $value) {

            // If a $key is numeric then break;
            if(is_numeric($key)) {
                break;
            }

            // Set $tempSql to current $sql
            $tempSql = $sql;

            // Check if $key has ':' else set it
            $key = (substr($key, 0, 1) === ":") ? $key : ":".$key;

            $placeholderCount = 0;
            $lastPosition = 0;

            // Find all $key in $sql, and loop through them. We cannot use strpos() as it can get ':id' and ':idd'
            while(preg_match("/(?<!\w)" . $key . "(?!\w)/",$tempSql,$matches,PREG_OFFSET_CAPTURE) === 1) {

                // Set last position equal to $lastPosition + new $position
                $lastPosition += $matches[0][1];

                // If $value is an array then create a placeholder, (?,?,..)
                if(is_array($value)) {

                    $placeholder = "(";

                    // Create the placeholder '?' and set the values into $tempBindable
                    foreach($value as $index => $item) {
                        $placeholder .= "?,";
                        $tempBindable[$lastPosition + $index] = $item;
                    }

                    $placeholder = rtrim($placeholder, ",") . ")";

                // Set '?' for non arrays, also set value equal to current position
                } else {

                    $sql = preg_replace("/(?<!\w)".$key."(?!\w)/", "?", $sql);
                    $tempBindable[$lastPosition] = $value;
                }

                // Cut off $tempSql, so we dont run forever
                $tempSql = substr($tempSql, $matches[0][1] + strlen($key));

                // There can be a max of 35 placeholders of the same time, e.g. ':id' can be set 35 times as a max
                if($placeholderCount === 35) {
                    break;
                }
                $placeholderCount++;
            }

            // Replace the placeholder $key with regex
            if(is_array($value)) {
                $sql = preg_replace("/(?<!\w)".$key."(?!\w)/", $placeholder, $sql);
            }

        }
        // Sort $tempBindable by key
        ksort($tempBindable);

        // Return new SQL and new BINDABLE
        return ["SQL" => $sql, "BINDABLE" => (!empty($tempBindable)) ? $tempBindable : $bindable];
    }

    /**
     * fetchAssoc() is used for returning the data as an associate array
     * @return array
     */
    public function fetchAssoc() : array {

        // Get result
        $this->query = $this->query->get_result();

        // Fetch all data
        $data = $this->query->fetch_all(MYSQLI_ASSOC);

        // Reset query
        $this->query->close();
        $this->query = null;

        // Return data
        return $data;
    }

    /**
     * fetchArray() is used for returning the data as a numeric array
     * @return array
     */
    public function fetchArray() : array {

        // Get Result
        $this->query = $this->query->get_result();

        // Fetch all data
        $data = $this->query->fetch_all(MYSQLI_NUM);

        // Reset query
        $this->query->close();
        $this->query = null;

        // Return data
        return $data;
    }

    /**
     * affectedRows() is used for returning the number of rows affected
     * @return int
     */
    public function affectedRows() : int {
        return $this->query->affected_rows;
    }

    /**
     * getLastAutoID() is used for returning the last inserted auto generated ID
     * @return int 
     */
    public function getLastAutoID()  {
        // Reset query
        $this->query->close();
        $this->query = null;

        return $this->connection->insert_id;
    }

    /**
     * __destruct() is used for closing the connection when done with the class.
     */
    public function __destruct(){
        $this->connection->close();
        $this->connection = null;
    }

}