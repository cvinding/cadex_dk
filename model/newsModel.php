<?php
namespace MODEL;

class NewsModel extends \MODEL\BASE\Model {

    public function getNews() {
        return $this->sendGET("/news/getAll");
    }


}