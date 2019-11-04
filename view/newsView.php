<?php
namespace VIEW;

class NewsView extends \VIEW\BASE\View {

    public $title = "CADEX - Nyheder";

    public $imageHeader = false;

    public function __construct(\Request $request) {
        parent::__construct($request);
        $this->addCSSLinks([
            "/design/css/news-posts.css"
        ]);
    }

    public function index(int $page = 1) {

        $newsModel = new \MODEL\NewsModel();

        $response = $newsModel->getNews($page);

        $variables = [
            "content" => $this->createNewsPosts($response["result"], $page)
        ];

        exit($this->renderView("standard/standard.php", $variables));
    }
    
    private function createNewsPosts(array $newsPosts, int $page) {

        $totalPosts = $newsPosts["newsCount"];
        $newsPosts = $newsPosts["newsPosts"];

        $html = '';

            foreach($newsPosts as $newsPost) {

                $createdAt = \HELPER\DateHelper::formatDate($newsPost["created_at"], " H:i \d\\e\\n d-m-Y");

                $html .= '<ul class="news-posts">';
                    $html .= '<li class="news-posts-title">' . $newsPost["title"] . '</li>';
                    $html .= '<li class="news-posts-content">' . $newsPost["content"] . '</li>';
                    $html .= '<li class="news-posts-author">Oprettet af '. $newsPost["author"] .' kl. ' . $createdAt . '</li>';
                $html .= '</ul>';

            }

        $totalPages = (int) ceil($totalPosts / 6);

        $html .= $this->createPageNavigator("/news/p/", $page, $totalPages);

        return $html;
    }

}