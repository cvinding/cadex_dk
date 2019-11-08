<?php
namespace VIEW\PARTIAL;

class CarouselView extends \VIEW\PARTIAL\BASE\PartialView {

    public $id = "carousel";
    public $images = [
        "/design/assets/placeholder.png"
    ];

    public function build() {
        $variables = [
            "id" => $this->id,
            "items" => $this->createCarouselItems(),
            "indicators" => $this->createCarouselIndicators()
        ];

        return \HELPER\Renderer::render("ui-elements/carousel.php", $variables);
    }

    private function createCarouselItems() : string {
        
        $html = '';
        
        foreach($this->images as $index => $image) {

            $html .= ($index === 0) ? '<div class="carousel-item active">' : '<div class="carousel-item">';
                $html .= '<img class="d-block w-100" src="'.$image.'" alt="Slide">';
            $html .= '</div>';
        }

        return $html;
    }

    private function createCarouselIndicators() : string {

        $html = '';

        for($i = 0; $i < sizeof($this->images); $i++) {

            $html .= ($i === 0) ? '<li data-target="#' . $this->id . '" data-slide-to="'.$i.'" class="active">' : '<li data-target="#' . $this->id . '" data-slide-to="'.$i.'">';
            $html .= '</li>';
        }

        return $html;
    }

}