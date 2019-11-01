<?php
namespace MODEL;

class NewsModel extends \MODEL\BASE\Model {

    public function getNews(int $page = 1) {
        return $this->sendGET("/news/getAll/" . $page);
    }


}