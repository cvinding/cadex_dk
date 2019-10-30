<?php
namespace VIEW;

class HomeView extends \VIEW\BASE\View {

    public function __construct(\Request $request) {
        parent::__construct($request);
        $this->title = "CADEX - Hjem";
    }

    public function index() {

        exit($this->render("standard/standard.php", [

            "content" => "<h3>Vi her hos CADEX elsker penge :)</h3>"

        ], true));

    }

}