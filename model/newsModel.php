<?php
namespace MODEL;

class NewsModel extends \MODEL\BASE\Model {

    public function getNews(int $page = 1, bool $cache = true) {
        return $this->sendGET("/news/getAll/" . $page, $cache);
    }

    public function getAll() {

        $allNews = [];

        $loop = true;
        $i = 1;
        while($loop) {

            $result = $this->getNews($i, false);

            if($result["status"] === false || $this->lastHTTPCode === 404 || $i === 10) {
                break;
            } 

            //TODO: fix API endpoint

            $allNews = array_merge($allNews, $result["result"]["newsPosts"]);

            $i++;            
        }

        return $allNews;
    }

    public function getNewsById(int $id, bool $cache = true) : array {
        return $this->sendGET("/news/get/" . $id, $cache);
    }

    public function addNews(string $title, string $content) : bool {
        
        $response = $this->sendPOST("/news/create", [
            "title" => $title,
            "content" => $content,
            "author" => \SESSION\Session::get("USER/ID")
        ]);

        return $response["status"];
    }

    public function deleteNews(int $id) : bool {

        $response = $this->sendDELETE("/news/delete/" . $id);

        return $response["status"];
    }

    public function editNews(int $id, string $title, string $content) : bool {

        $response = $this->sendPUT("/news/update/" . $id, [
            "title" => $title,
            "content" => $content,
            "author" => \SESSION\Session::get("USER/ID")
        ]);

        return $response["status"];
    }
}