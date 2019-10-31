<?php
namespace VIEW;

class ProductView extends \VIEW\BASE\View {

    public $title = "CADEX - Produkter";

    public function __construct(\Request $request) {
        parent::__construct($request);
    }

    public function index() {
        $variables = [
            "content" => "Her kan du se vores produkter!"
        ];

        exit($this->renderView("standard/standard.php", $variables));
    }

    private function createProductList() {

    }

}