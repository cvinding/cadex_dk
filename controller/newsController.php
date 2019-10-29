<?php
namespace CONTROLLER;

/**
 * Class NewsController
 * @package CONTROLLER
 * @author Christian Vinding Rasmussen
 * The controller for user input on the News endpoint
 */
class NewsController extends \CONTROLLER\BASE\Controller {

    /**
     * An instance of the \MODEL\NewsModel class
     * @var \MODEL\NewsModel $newsModel
     */
    private $newsModel;

    /**
     * __construct is used for setting the Request class and initializing the NewsModel
     * @param \Request $request
     */
    public function __construct(\Request $request) {
        parent::__construct($request);
        $this->newsModel = new \MODEL\NewsModel();
    }

    /**
     * create() is the method for creating news posts
     * @param string $title
     * @param string $content
     * @return void
     */
    public function create(string $title, string $content) : void {

        // try and create the news post
        $result = $this->newsModel->createNewsPost($title, $content, $this->username);

        // Attach message based on outcome
        if($result) {
            
            \HELPER\Logger::log($this->username, $this->request->remoteAddr, 1, 16);    

            \HELPER\MessageHandler::attachMessage("You have successfully created a new news post", 201);

        } else {

            \HELPER\Logger::log($this->username, $this->request->remoteAddr, 1, 17);    

            \HELPER\MessageHandler::attachMessage("An error occurred while trying to create a new news post.", 500);
        }
    }

    /**
     * update() is the method for updating news posts
     * @param int $id
     * @param string $title
     * @param string $content
     * @return void
     */
    public function update(int $id, string $title, string $content) : void {

        // try and update the news post
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

    /**
     * delete() is the method for deleting news posts
     * @param int $id
     * @return void
     */
    public function delete(int $id) : void {

        // try and delete the news post
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