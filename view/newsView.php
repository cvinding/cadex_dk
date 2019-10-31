<?php
namespace VIEW;

class NewsView extends \VIEW\BASE\View {

    public $title = "CADEX - Nyheder";

    public function __construct(\Request $request) {
        parent::__construct($request);
    }

    public function index() {

        $newsModel = new \MODEL\NewsModel();

        $response = $newsModel->getNews();

        $variables = [
            "content" => $this->createNewsPosts($response["result"])
        ];

        exit($this->renderView("standard/standard.php", $variables));
    }
    
    private function createNewsPosts(array $newsPosts) {

        $html = '<ul>';

            foreach($newsPosts as $newsPost) {

                $html .= '<li>' . $newsPost["title"] . '</li>';

                    $html .= '<ul>';
                        $html .= '<li>' . $newsPost["content"] . '</li>';
                        $html .= '<li>Oprettet af '. $newsPost["author"] .' kl. ' . $newsPost["created_at"] . '</li>';
                    $html .= '</ul>';

            }

        $html .= '</ul>';

        return $html;
    }

}