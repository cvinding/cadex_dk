<?php
namespace VIEW\BASE;

class View {

    public $title = "CADEX";

    public $imageHeader = true;
    public $imageHeaderText = "Cadex A/S";
    public $imageHeaderSource = "/design/assets/cadex_auto_hq.svg";

    private $css = [
        "/design/vendor/bootstrap-4.3.1/css/bootstrap.min.css",
        "/design/vendor/font-awesome/css/all.css",
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

        if($this->imageHeader) {
            $imageHeader = \HELPER\Renderer::render("ui-elements/image-header.php",["text" => $this->imageHeaderText, "src" => $this->imageHeaderSource]);

            $viewVariables["imageHeader"] = $imageHeader;
        }

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

    protected function createAlert(string $type, string $message, bool $dismissible = false, string $strong = null) {

        if($strong === null) {
            $defaultStrong = ["danger" => "Error!", "warning" => "Warning!", "info" => "Info", "success" => "Success!"];
            $strong = $defaultStrong[$type];
        }

        if($dismissible) {
        
            $output = "<div class='alert alert-{$type} alert-dismissible fade show' role='alert'>";
                $output .= "<button type='button' class='close' data-dismiss='alert' aria-label='Close'>";
                    $output .= "<span aria-hidden='true'>&times;</span>";
                $output .= "</button>";
                $output .= "<strong>{$strong}</strong> {$message}";
            $output .= "</div>";
        
        } else {
        
            $output = "<div class='alert alert-{$type}' role='alert'>";
                $output .= "<strong>{$strong}</strong> {$message}";
            $output .= "</div>";
        }
        
        return $output;
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