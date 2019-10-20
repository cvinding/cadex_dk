<?php
namespace MODEL;

/**
 * Class ProductModel
 * @package MODEL
 * @author Christian Vinding Rasmussen
 * ProductModel is used for managing Product database entries
 */
class ProductModel extends \MODEL\BASE\Model {

    /**
     * An instance of the Database class
     * @var \DATABASE\MYSQLI\Database $database
     */
    private $database;

    /**
     * __construct() is used for creating a new instance of the Database class
     */
    public function __construct() {
        $this->database = new \DATABASE\MYSQLI\Database();
    }

    /**
     * getProducts() selects all products from the database, can return an empty array if no entries has been created
     * @return array
     */
    public function getProducts() : array {
        return $this->database->query("SELECT id, name, price FROM products")->fetchAssoc();
    }

    /**
     * getProduct() selects a product entry from the database, can return an empty array if no entry is found
     * @param int $id
     * @return array
     */
    public function getProduct(int $id) : array {
        return $this->database->query("SELECT id, name, price FROM products WHERE id = :id",["id" => $id])->fetchAssoc();
    }

    /**
     * createProduct() inserts a new database entry for products and then returns true/false based on outcome
     * @param string $name
     * @param int $price
     * @return bool
     */
    public function createProduct(string $name, int $price) : bool {
        return ($this->database->query("INSERT INTO products (name, price) VALUES (:name, :price)", ["name" => $name, "price" => $price])->affectedRows() > 0);
    }

    /**
     * updateProduct() updates the specified product
     * @param int $id
     * @param string $name
     * @param int $price
     * @return bool
     */
    public function updateProduct(int $id, string $name, int $price) : bool {

        // Select entry to see if exists
        $entry = $this->database->query("SELECT id FROM products WHERE id = :id", ["id" => $id])->fetchAssoc();

        // If it does not exists return false
        if(empty($entry)) {
            return false;
        }

        // Update the entry
        $this->database->query("UPDATE products SET name = :name, price = :price WHERE id = :id", ["name" => $name, "price" => $price, "id" => $id])->affectedRows();

        // Return true
        return true;
    }

    /**
     * deleteProduct() deletes the specified product
     * @param int $id
     * @return bool
     */
    public function deleteProduct(int $id) : bool {
        return ($this->database->query("DELETE FROM products WHERE id = :id", ["id" => $id])->affectedRows() > 0);
    }

}