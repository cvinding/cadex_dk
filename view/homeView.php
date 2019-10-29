<?php
namespace VIEW;

class HomeView extends \VIEW\BASE\View {

  //  public $title = "CADEX - Hjem";

    /*public $css = [
        "/design/css/demons.css",
        "/design/css/demons2.css",
    ];*/

    public function __construct(\Request $request) {
        parent::__construct($request);
        
    }

    public function index() {
        //$this->render("ui-elements/navbar.php",["login" => \SESSION\Session::get("LOGIN/STATUS")])
        exit($this->render("standard/standard.php", [
            "content" => "<h3>Vi her hos CADEX elsker penge :)</h3>"
        ],true));
    }

}