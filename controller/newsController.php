<?php
namespace CONTROLLER;

/**
 * Class NewsController
 * @package CONTROLLER
 * @author Christian Vinding Rasmussen
 * TODO description
 */
class NewsController extends \CONTROLLER\BASE\Controller {

    /**
     * An instance of the \MODEL\NewsModel class
     * @var \MODEL\NewsModel $newsModel
     */
    private $newsModel;

    /**
     * __construct is used for setting the Request class and initializing the NewsModel
     */
    public function __construct(\Request $request) {
        parent::__construct($request);
        $this->newsModel = new \MODEL\NewsModel();
    }

    public function create(string $title, string $content) : void {

        $result = $this->newsModel->createNewsPost($title, $content, $this->username);

        if($result) {
            
            \HELPER\Logger::log($this->username, $this->request->remoteAddr, 1, 16);    

            \HELPER\MessageHandler::attachMessage("You have successfully created a new news post", 201);

        } else {

            \HELPER\Logger::log($this->username, $this->request->remoteAddr, 1, 17);    

            \HELPER\MessageHandler::attachMessage("An error occurred while trying to create a new news post.", 500);
        }
    }

    public function update(int $id, string $title, string $content) : void {

        $result = $this->newsModel->updateNewsPost($id, $title, $content, $this->username);

        // Attach message based on outcome
        if($result) {
            \HELPER\Logger::log($this->username, $this->request->remoteAddr, 3, 18);

            \HELPER\MessageHandler::attachMessage("You have successfully updated a news post.", 200);
        } else {
            \HELPER\Logger::log($this->username, $this->request->remoteAddr, 3, 19);

            \HELPER\MessageHandler::attachMessage("Unable to find news post, news post could not be updated.", 404);
        }

    }

    public function delete(int $id) : void {

        $result = $this->newsModel->deleteNewsPost($id);

        // Attach message based on outcome
        if($result) {
            \HELPER\Logger::log($this->username, $this->request->remoteAddr, 4, 20);

            \HELPER\MessageHandler::attachMessage("You have successfully deleted a news post.", 200);
        } else {
            \HELPER\Logger::log($this->username, $this->request->remoteAddr, 4, 21);

            \HELPER\MessageHandler::attachMessage("Unable to find news post, news post could not be deleted.", 404);
        }
    }

}