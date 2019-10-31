<?php
namespace HELPER;

class Renderer {

    public static function render(string $template, array $variables = []) : string {
        
        // Create the full path to the requested template
        $fullTemplatePath = APP_ROOT . "/template/" . $template;

        // Check if template exists else throw exception
        if(!file_exists($fullTemplatePath)) {
            throw new \Exception("Unable to render template; '{$template}' does not exists");
        }

        // Extract the variables
        extract($variables);
                
        // Start output buffering
        ob_start();
        
        // Require the template
        require $fullTemplatePath;
        
        // Get the output buffered template
        $renderedTemplate = ob_get_clean();
        
        // Return the rendered template
        return $renderedTemplate;
    }


}