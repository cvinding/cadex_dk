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
        return $this->database->query("SELECT DISTINCT p.id, p.name, p.description, p.price, pi.image FROM products p LEFT JOIN product_images pi ON p.id = pi.products_id")->fetchAssoc();
    }

    /**
     * getProduct() selects a product entry from the database, can return an empty array if no entry is found
     * @param int $id
     * @return array
     */
    public function getProduct(int $id) : array {
        return $this->database->query("SELECT id, name, description, price FROM products WHERE id = :id",["id" => $id])->fetchAssoc();
    }

    /**
     * createProduct() inserts a new database entry for products and then returns true/false based on outcome
     * @param string $name
     * @param string $description
     * @param float $price
     * @return bool
     */
    public function createProduct(string $name, string $description, float $price) : bool {
        return ($this->database->query("INSERT INTO products (name, description, price) VALUES (:name, :description, :price)", ["name" => $name, "description" => $description, "price" => $price])->affectedRows() > 0);
    }

    /**
     * updateProduct() updates the specified product
     * @param int $id
     * @param string $name
     * @param string $description
     * @param float $price
     * @return bool
     */
    public function updateProduct(int $id, string $name, string $description, float $price, array $imagesToDelete = []) : bool {

        // Select entry to see if exists
        $entry = $this->database->query("SELECT id FROM products WHERE id = :id", ["id" => $id])->fetchAssoc();

        // If it does not exists return false
        if(empty($entry)) {
            return false;
        }

        // Update the entry
        $this->database->query("UPDATE products SET name = :name, description = :description, price = :price WHERE id = :id", ["name" => $name, "description" => $description, "price" => $price, "id" => $id])->affectedRows();

        // Return true
        return true;
    }

    public function uploadImage(int $id, string $image = "") {

        if($image === "") {
            $image = $_FILES["file"];
        }

        $image = "data:image/png;base64," . base64_encode(file_get_contents($image['tmp_name']));
        
        $this->database->query("INSERT INTO product_images (products_id, image) VALUES(:id, :image)", ["id" => $id, "image" => $image])->affectedRows();

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