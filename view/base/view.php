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

    /**
     * CSRF_FIELD() is used for making a standard CSRF_FIELD in a form
     * @return string
     */
    protected function CSRF_FIELD() {
        try {
            
            // Get the CSRF/TOKEN from session
            $CSRF = \SESSION\Session::get("CSRF/TOKEN");

        } catch (\Exception $e){
            exit($e);
        }

        // Return the input field
        return '<input type="hidden" name="CSRF-TOKEN" value="' . $CSRF . '">';
    }

    protected function createPageNavigator(string $url, int $page, int $total) {
        $previosPage = $page - 1;
        $nextPage = $page + 1;

        $pageNavigator = '<nav>';
            $pageNavigator .= '<ul class="pagination">';

                if($previosPage !== 0) {
                    $pageNavigator .= '<li class="page-item">';
                        $pageNavigator .= '<a class="page-link" href="' . $url . $previosPage . '" aria-label="Forrige">';
                        $pageNavigator .= '<span aria-hidden="true">&laquo;</span>';
                        $pageNavigator .= '<span class="sr-only">Forrige</span>';
                        $pageNavigator .= '</a>';
                    $pageNavigator .= '</li>';
                }

                for($i = 1; $i <= $total; $i++) {
                    $pageNavigator .= '<li class="page-item"><a class="page-link" href="'. $url . $i .'">' . $i . '</a></li>';
                }

                if($nextPage <= $total) {
                    $pageNavigator .= '<li class="page-item">';
                        $pageNavigator .= '<a class="page-link" href="' . $url . $nextPage . '" aria-label="Næste">';
                        $pageNavigator .= '<span aria-hidden="true">&raquo;</span>';
                        $pageNavigator .= '<span class="sr-only">Næste</span>';
                        $pageNavigator .= '</a>';
                    $pageNavigator .= '</li>';
                }

            $pageNavigator .= '</ul>';
        $pageNavigator .= '</nav>';

        return $pageNavigator;
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