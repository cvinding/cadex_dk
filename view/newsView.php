<?php
namespace VIEW;

/**
 * Class NewsView
 * @package VIEW
 * @author Christian Vinding Rasmussen
 * TODO description
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
     * get() the endpoint for outputting all the news posts or a specific news post
     * @param int $id = -1
     * @return void
     */
    public function get(int $id = -1) : void {

        // If $id === -1 then select all news posts else only the specified
        if($id === -1) {

            $data = $this->newsModel->getNewsPosts();

        } else {

            $data = $this->newsModel->getNewsPost($id);
        }

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