<?php
namespace VIEW;

class ProductView extends \VIEW\BASE\View {

    public $title = "CADEX - Produkter";

    public $imageHeaderText = "Produkter";
    public $imageHeaderSource = "/design/assets/lambogini_hp.svg";

    private $productModel;

    public function __construct(\Request $request) {
        parent::__construct($request);
        $this->productModel = new \MODEL\ProductModel();
        $this->addCSSLinks([
            "/design/css/product-stylesheet.css"
        ]);
    }

    public function index(int $page = 1) {
        $variables = [
            "content" => $this->createProductList($page)
        ];

        exit($this->renderView("standard/standard.php", $variables));
    }

    public function getProduct(int $id) {
        $this->imageHeader = false;

        $variables = [
            "content" => $this->createProductView($id)
        ];

        exit($this->renderView("standard/standard.php", $variables));
    }

    private function createProductList(int $page) {

        $products = $this->productModel->getProducts($page)["result"];

        $products = $products["products"];

        $perRow = 3;
        
        $rows = ceil(sizeof($products) / $perRow);

        for($i = 0; sizeof($products) < ($rows * $perRow); $i++) {
            $products[] = ["id" => false, "name" => "placeholder", "description" => "placeholder", "price" => "n/a"];
        }

        $html = '';

        foreach($products as $index => $product) {

            if($index % $perRow === 0) {
                $html .= '<div class="card-deck">';
            }

            $imageString = (isset($product["images"][0])) ? 'data:' . $product["images"][0]["type"] . ';base64,' . $product["images"][0]["image"] : '/design/assets/placeholder.png';

            $formatedDescription = \HELPER\StringHelper::previewString($product["description"], 10);

            $html .= ($product["id"] !== false) ? '<a class="card" href="/products/product/' . $product["id"] . '">' : '<div class="card">';
                $html .= '<img class="card-img-top" src="' . $imageString . '" alt="Card image cap" height="225px">';
                $html .= '<div class="card-body">';
                    $html .= '<h4 class="card-title">' . $product["name"] . '</h4>';
                    $html .= '<p class="card-text">' . $formatedDescription . '</p>';
                $html .= '</div>';                 
                $html .= '<div class="card-footer"><p class="card-text">Start pris: ' . number_format((float)$product["price"],2,",",".") . ' kr.</p></div>';
            $html .= ($product["id"] !== false) ? '</a>' : '</div>';

            if(($index % $perRow) === ($perRow - 1)) {
                $html .= '</div>';
            }
        }

        return $html;
    }

    private function createProductView(int $id) {

        $product = $this->productModel->getProductById($id);

        if(empty($product)) {
            header("location: /products");
        }

        $product = $product["result"][0];

        $carousel = new \VIEW\PARTIAL\CarouselView();
        $carousel->id = "product-carousel";

        $tempImages = [];

        foreach($product["images"] as $images) {
            $tempImages[] = 'data:' . $images["type"] . ';base64,' . $images["image"];
        }
        
        $carousel->images = $tempImages;

        $html = '<div class="product-output">';
            $html .= '<div class="row">';
                $html .= '<div class="col-sm-6">';
                    $html .= $carousel->build();
                $html .= '</div>';
                $html .= '<div class="col-sm-1">';
                    $html .= '&nbsp;';
                $html .= '</div>';
                $html .= '<div class="col-sm-5">';
                    $html .= '<h3>' . $product["name"] . '</h3>';
                    $html .= '<p>' . $product["description"] . '</p>';
                    $html .= '<p>Start pris: '. number_format($product["price"], 2, ",", ".") .'</p>';
                $html .= '</div>';
           $html .= '</div>';
        $html .= '</div>';

        return $html;
    }

    

}