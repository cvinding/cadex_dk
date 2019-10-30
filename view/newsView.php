<?php
namespace VIEW;

/**
 * Class NewsView
 * @package VIEW
 * @author Christian Vinding Rasmussen
 * The NewsView outputs responses directly or from the controller
 */
class NewsView extends \VIEW\BASE\View {

    /**
     * An instance of the \MODEL\NewsModel
     * @var \MODEL\NewsModel $newsModel
     */
    private $newsModel;

    /**
     * __construct() sets Request class and initializes NewsModel
     * @param \Request $request
     */
    public function __construct(\Request $request) {
        parent::__construct($request);
        $this->newsModel = new \MODEL\NewsModel();
    }

    /**
     * get() the endpoint for outputting a specific news post
     * @param int $id
     * @return void
     */
    public function get(int $id) : void {

        $data = $this->newsModel->getNewsPost($id);
        
        if(empty($data)) {
            
            $httpCode = 404;
            $status = false;

        } else {

            $httpCode = 200;
            $status = true;
        }

        http_response_code($httpCode);

        // Output the JSON data
        exit(json_encode(["result" => $data, "status" => $status]));
    }

    /**
     * getAll() is the method for showing all news posts method
     * @param int $page = 1
     * @return void
     */
    public function getAll(int $page = 1) : void {

        $data = $this->newsModel->getNewsPosts($page);

        if(empty($data)) {
            
            $httpCode = 404;
            $status = false;

        } else {

            $httpCode = 200;
            $status = true;
        }

        http_response_code($httpCode);

        // Output the JSON data
        exit(json_encode(["result" => $data, "status" => $status]));
    }

}