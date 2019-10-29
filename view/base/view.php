<?php
namespace VIEW\BASE;

class View {

    public $title = "CADEX";

    public $css = [
        "/design/css/stylesheet.css"
    ];

    protected $request;

    public function __construct(\Request $request){
        $this->request = $request;
    }

    protected function render(string $template, array $variables = [], bool $fullTemplate = false) : string {

        if($fullTemplate) {
            $class = get_called_class();
            $instance = new $class($this->request);

            $standardVariables = [
                "title" => $instance->title, 
                "navbar" => $this->render("ui-elements/navbar.php", ["login" => \SESSION\Session::get("LOGIN/STATUS")])
            ];

            unset($instance);

            $variables = array_merge($standardVariables, $variables);
        }

        $templatePath = APP_ROOT . "/template/{$template}";

        // Check if template exists else throw exception
        if(!file_exists($templatePath)) {
            throw new \Exception("Unable to render template; '{$templatePath}' does not exists");
        }

        // Extract the variables
        extract($variables);
        
        // Start output buffering
        ob_start();
        
        // Require the template
        require $templatePath;
        
        // Get the output buffered template
        $renderedTemplate = ob_get_clean();
        
        // Return the rendered template
        return $renderedTemplate;
    }

    private function createCSSLinks(array $links) : string {

        $links = "";

        foreach($links as $link) {
            $links .= "<link href='" . $link . "' rel='stylesheet' type='text/css'>";
        }

        return $links;
    }

}