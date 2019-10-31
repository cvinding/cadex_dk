<?php
namespace VIEW;

class LoginView extends \VIEW\BASE\View {

    public $title = "CADEX - Log ind";

    public function __construct(\Request $request) {
        parent::__construct($request);
    }

    public function index() {
        $variables = [
            "content" => $this->createLoginForm()
        ];

        exit($this->renderView("standard/standard.php", $variables));
    }

    private function createLoginForm() {

        $html = '<form method="POST" action="/login/authenticate">';
            $html .= $this->CSRF_FIELD();
            $html .= '<div class="form-group">';
                $html .= '<label for="username">Brugernavn</label>';
                $html .= '<input type="text" class="form-control" id="username" name="username" placeholder="Brugernavn" required="required">';
            $html .= '</div>';
            $html .= '<div class="form-group">';
                $html .= '<label for="password">Kodeord</label>';
                $html .= '<input type="password" class="form-control" id="password" name="password" placeholder="Kodeord" required="required">';
            $html .= '</div>';
            $html .= '<input type="submit" class="btn btn-success" name="login" value="Log ind">';
        $html .= "</form>";

        return $html;
    }

}