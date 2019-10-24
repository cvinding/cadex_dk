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
    public function getProducts(int $imageCount) : array {

        $products = $this->database->query("SELECT id, name, description, price FROM products")->fetchAssoc();

        foreach($products as $index => $product) {
            $images = $this->database->query("SELECT image, type, thumbnail FROM product_images WHERE products_id = :p_id ORDER BY thumbnail DESC LIMIT :limit", ["p_id" => $product["id"], "limit" => $imageCount])->fetchAssoc();

            $products[$index]["images"] = $images;
        }

        return $products; 
    }

    /**
     * getProduct() selects a product entry from the database, can return an empty array if no entry is found
     * @param int $id
     * @return array
     */
    public function getProduct(int $id) : array {

        // Select product
        $product = $this->database->query("SELECT id, name, description, price FROM products WHERE id = :id",["id" => $id])->fetchAssoc();

        // If product does not exists return empty array
        if(empty($product)) {
            return [];
        }

        // Select images for the product
        $images = $this->database->query("SELECT image, type, thumbnail FROM product_images WHERE products_id = :id ORDER BY thumbnail DESC",["id" => $id])->fetchAssoc();

        // Add images into product array
        $product[0]["images"] = $images;

        // return product
        return $product; 
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
    public function updateProduct(int $id, string $name, string $description, float $price) : bool {

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

    public function uploadImage(int $id, string $thumbnail, string $image = "") {

        // If $image is empty check $_FILES
        if($image === "") {

            reset($_FILES);
            $first_key = key($_FILES);

            $file = $_FILES[$first_key];

            // If no image was uploaded then return 0
            if($file["error"] === 4) {
                return 0;
            }

            // Get binary image data
            $image = file_get_contents($file['tmp_name']);
        }

        // If $image is larger than 20 mib, then return 1
        if(strlen($image) > 20971520) {
            return 1;
        }

        // Valid image types
        $validImageTypes = ["image/jpeg" => "\xFF\xD8\xFF", "image/gif" => "GIF", "image/png" => "\x89\x50\x4e\x47\x0d\x0a\x1a\x0a"];

        $currentType = false;
        // Loop through each valid type and check binary data if image is valid
        foreach($validImageTypes as $name => $query) {

            // Set $currentType to the $name of the valid type, if the image is the valid type
            if(substr($image, 0, strlen($query)) === $query) {
                $currentType = $name;
                break;
            }
        }

        // If image is not a valid type, return 2
        if($currentType === false) {
            return 2;
        }

        // base64 encode $image
        $image = base64_encode($image);

        // Set image as thumbnail?
        $thumbnail = ($thumbnail === "true") ? 1 : 0;

        // Return true/false if it was able to be inserted
        return ($this->database->query("INSERT INTO product_images (products_id, image, type, thumbnail) VALUES(:id, :image, :image_type, :thumbnail)", ["id" => $id, "image" => $image, "image_type" => $currentType, "thumbnail" => $thumbnail])->affectedRows() > 0);
    }

    /**
     * deleteImages() is used to delete images from the database. 
     * $imagesToDelete is an array of image IDs that will be deleted.
     * @param array $imagesToDelete
     * @return bool
     */
    public function deleteImages(array $imagesToDelete) : bool {

        // Get image ID count
        $imageCount = sizeof($imagesToDelete);

        // Initialize query count
        $queryCount = 0;

        // Delete each image from the database
        foreach($imagesToDelete as $imageID) {
            $this->database->query("DELETE FROM product_images WHERE id = :id", ["id" => $imageID])->affectedRows();
        }

        // Return the result
        return ($imageCount === $queryCount);
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