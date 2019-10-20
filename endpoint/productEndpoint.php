<?php
namespace ENDPOINT;

class ProductEndpoint extends \ENDPOINT\BASE\Endpoint implements \ENDPOINT\IMPLEMENT\Endpoint {

    // GET request
    public function getAll() {
        
        $database = new \DATABASE\MYSQLI\Database();
        
        $data = $database->query("SELECT id, username FROM users")->fetchAssoc();

        exit(json_encode(["result" => $data, "status" => true]));
    }

    // GET request
    public function get(int $id) {

        $database = new \DATABASE\MYSQLI\Database();
        
        $data = $database->query("SELECT id, name, content, created, last_edit FROM news WHERE id = ?", [$id])->fetchAssoc();

        exit(json_encode(["result" => $data[0], "status" => true]));
    }

    // POST request
    public function create(string $name, int $price) {
        exit(json_encode(["result" => ["message" => "Created new product '{$name}' and it will be sold at {$price} DKK"],"status" => true]));
    }

    // PUT request
    public function update(int $id) {
        exit(json_encode(["status" => true]));
    }

    // DELETE request
    public function delete(int $id) {
        exit(json_encode(["status" => true]));
    }

}