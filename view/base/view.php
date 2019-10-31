<?php
namespace VIEW\BASE;

class View {

    public $title = "CADEX";

    private $css = [
        "/design/vendor/bootstrap-4.3.1/css/bootstrap.min.css",
        "/design/css/stylesheet.css"
    ];

    protected $request;

    public function __construct(\Request $request){
        $this->request = $request;
    }

    protected function renderView(string $template = "standard/standard.php", array $variables = []) : string {

        $viewVariables = [
            "title"     => $this->title, 
            "css"       => $this->createCSSLinks($this->css),
            "navbar"    => (new \VIEW\PARTIAL\NavbarView($this->request))->build(),
            "footer"    => \HELPER\Renderer::render("ui-elements/footer.php")
        ];

        $variables = array_merge($viewVariables, $variables);

        return \HELPER\Renderer::render($template, $variables);
    }

    protected function addCSSLinks(array $links) {
        $this->css = array_merge($this->css, $links);
    }

    private function createCSSLinks(array $links) : string {

        $html = "";

        foreach($links as $link) {
            $html .= "<link href='" . $link . "' rel='stylesheet' type='text/css'>";
        }

        return $html;
    }

}