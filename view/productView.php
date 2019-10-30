<?php
namespace VIEW;

class ProductView extends \VIEW\BASE\View {

    public $title = "CADEX - Produkter";

    public function __construct(\Request $request) {
        parent::__construct($request);
    }

    public function index() {
        exit($this->render("standard/standard.php", [
            "content" => "Her kan du se vores produkter!"
        ],true));
    }

    private function createProductList() {

    }

}