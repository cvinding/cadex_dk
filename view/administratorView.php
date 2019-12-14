<?php
namespace VIEW;

use Request;

class AdministratorView extends \VIEW\BASE\View {

    public $title = "CADEX - Admin";

    public $imageHeader = false;

    public function __construct(\Request $request) {
        parent::__construct($request);
        $this->addCSSLinks([
            "/design/css/administrate.css"
        ]);
    }

    public function index() {

        $html = '<nav aria-label="breadcrumb">';
            $html .= '<ol class="breadcrumb">';
                $html .= '<li class="breadcrumb-item active" aria-current="page">Dashboard</li>';
            $html .= '</ol>';
        $html .= '</nav>';

        $html .= '<ul>';
            $html .= '<li><a class="btn btn-link" href="/administrate/news">Administrate news</a></li>';
            $html .= '<li><a class="btn btn-link" href="/administrate/product">Administrate products</a></li>';
            $html .= '<li><a class="btn btn-link" href="/administrate/logs">See API logs</a></li>';
        $html .= '</ul>';

        exit($this->renderView("standard/standard.php", ["content" => $html]));
    }

    public function list() {

        $html = '<nav aria-label="breadcrumb">';
            $html .= '<ol class="breadcrumb">';
                $html .= '<li class="breadcrumb-item"><a href="/administrate">Dashboard</a></li>';
                $html .= '<li class="breadcrumb-item active" aria-current="page">Administrate ' . $this->request->action . '</li>';
            $html .= '</ol>';
        $html .= '</nav>';

        $html .= '<ul>';
            $html .= '<li><a class="btn btn-success" href="/administrate/' . $this->request->action .'/add">Add ' . $this->request->action . '</a></li>';
            $html .= '<li><a class="btn btn-warning" href="/administrate/' . $this->request->action .'/edit">Edit ' . $this->request->action . '</a></li>';
            $html .= '<li><a class="btn btn-danger" href="/administrate/' . $this->request->action .'/delete">Delete ' . $this->request->action . '</a></li>';
        $html .= '</ul>';

        if($this->request->action === "product") {
            
            $html .= '<form class="form" action="/administrate/product/reset" method="POST">';
                $html .= $this->CSRF_FIELD();
                $html .= '<input class="btn btn-danger" type="submit" name="reset" value="Reset Crawler prices">';
            $html .= '</form>';
        }
        
        exit($this->renderView("standard/standard.php", ["content" => $html]));
    }

    public function submit(string $action) {
        $this->getForm($action);
    }

    public function getForm(string $action, string $thirdOption = "") {
        $type = $this->request->action;

        $template =  [
            "CSRF_FIELD" => $this->CSRF_FIELD()
        ];

        $messages = \HELPER\MessageHandler::getMessages();

        if(!empty($messages)) {
            $template["msg"] = $this->createAlert($messages[0]["type"],$messages[0]["message"], true);
        }

        $html = '<nav aria-label="breadcrumb">';
            $html .= '<ol class="breadcrumb">';
                $html .= '<li class="breadcrumb-item"><a href="/administrate">Dashboard</a></li>';
                $html .= '<li class="breadcrumb-item"><a href="/administrate/'.$type.'">Administrate ' . $type .'</a></li>';
                $html .= '<li class="breadcrumb-item active" aria-current="page">'. ucfirst($action) . ' ' . $type . '</li>';
            $html .= '</ol>';
        $html .= '</nav>';

        $variables["content"] = $html;

        if(($action === "delete" || $action === "edit") && $thirdOption !== "form") {
        
            $variables["content"] .= $this->createOptionsHTML($type, $action, $template);
        
        } else if($thirdOption === "form") {

            $instance = \HELPER\Dynamic::instance($type,"MODEL");
            $result = \HELPER\Dynamic::call($instance, "get" . $type. "ById", [$_POST["id"], false]);

            $tempData = [];

            foreach($result["result"][0] as $key => $value) {

                $tempData[$key] = $value;
            }

            $template["data"] = $tempData;

            $variables["content"] .= \HELPER\Renderer::render("admin/" . $action . "-" . $type . ".php", $template);

        } else {

            $variables["content"] .= \HELPER\Renderer::render("admin/" . $action . "-" . $type . ".php", $template);
        }

        exit($this->renderView("standard/standard.php", $variables));
    }


    /**
     * confirm() is a method for creating confirmation dialog 
     * @param string $type 
     * @return void
     */
    public function confirm(string $type) : void {
        
        $html = '<form method="POST" action="/administrate/' . $this->request->action . '/'. $type .'/submit">';

            $html .= "<h3>Are you sure you want to proceed?</h3>";

            foreach($_POST as $name => $value) {
                $html .= '<input type="hidden" name="' . $name . '" value="' . $value . '">';
            }

            $html .= '<input type="submit" class="btn btn-primary" value="Confirm" name="confirm">';
            $html .= '<a class="btn btn-secondary" href="/administrate/' . $this->request->action . '/'. $type .'">Cancel</a>';

        $html .= "</form>";
        
        $variables = [
            "content" => $html
        ];

        exit($this->renderView("standard/standard.php", $variables));
    }

    public function logs(int $page = 1) {

        $companyModel = new \MODEL\CompanyModel();

        $logs = $companyModel->getLogs($page);

        $html = '<nav aria-label="breadcrumb">';
            $html .= '<ol class="breadcrumb">';
                $html .= '<li class="breadcrumb-item"><a href="/administrate">Dashboard</a></li>';
                $html .= '<li class="breadcrumb-item active" aria-current="page">Logs</li>';
            $html .= '</ol>';
        $html .= '</nav>';

        $html .= '<table class="table table-striped table-light">';
            $html .= '<thead>';
                $html .= '<tr>';
                    $html .= '<td>#</td>';
                    $html .= '<td>User</td>';
                    //$html .= '<td>IP</td>';
                    $html .= '<td>Action</td>';
                    $html .= '<td>Message</td>';
                    $html .= '<td>Timestamp</td>';
                $html .= '</tr>';
            $html .= '</thead>';
            $html .= '<tbody>';

            foreach($logs["result"] as $entry) {
                $html .= '<tr>';
                    $html .= '<td>' . $entry["id"] . '</td>';
                    $html .= '<td>' . $entry["user"] . '</td>';
                    //$html .= '<td>' . $entry["ip"] . '</td>';
                    $html .= '<td>' . $entry["action"] . '</td>';
                    $html .= '<td>' . $entry["message"] . '</td>';
                    $html .= '<td>' . \HELPER\DateHelper::formatDate($entry["created_at"]) . '</td>';
                $html .= '</tr>';
            }

            $html .= '</tbody>';
        $html .= '</table>';


        $pageNavigator = '<nav>';
            $pageNavigator .= '<ul class="pagination">';

                if($page !== 1) {
                    $pageNavigator .= '<li class="page-item">';
                        $pageNavigator .= '<a class="page-link" href="/administrate/logs/' . ($page - 1) . '" aria-label="Forrige">';
                        $pageNavigator .= '<span aria-hidden="true">&laquo;</span>';
                        $pageNavigator .= '<span class="sr-only">Forrige</span>';
                        $pageNavigator .= '</a>';
                    $pageNavigator .= '</li>';
                }

                if(sizeof($logs["result"]) === 12) {
                    $pageNavigator .= '<li class="page-item">';
                        $pageNavigator .= '<a class="page-link"  href="/administrate/logs/' . ($page + 1) . '" aria-label="Næste">';
                        $pageNavigator .= '<span aria-hidden="true">&raquo;</span>';
                        $pageNavigator .= '<span class="sr-only">Næste</span>';
                        $pageNavigator .= '</a>';
                    $pageNavigator .= '</li>';
                }

            $pageNavigator .= '</ul>';
        $pageNavigator .= '</nav>';

        $html .= $pageNavigator;

        exit($this->renderView("standard/standard.php", ["content" => $html]));
    }

    private function createNewsOptions(array $news) {

        $html = '';

        foreach($news as $post) {
            $html .= '<option value="' . $post["id"] . '">' . $post["title"] . " - [" . $post["author"] . "] - [" . \HELPER\DateHelper::formatDate($post["created_at"],"m-d-Y H:i") . ']</option>';
        }

        return $html;
    }

    private function createProductOptions(array $products) {

        $html = '';

        foreach($products as $product) {
            $html .= '<option value="' . $product["id"] . '">' . $product["name"] . " - [" . \HELPER\DateHelper::formatDate($product["created_at"],"m-d-Y H:i") . ']</option>';
        }

        return $html;
    }

    private function createOptionsHTML(string $type, string $action, array $variables) {

        $labels = [
            "news" => ["delete" => "Choose a news post to delete", "edit" => "Choose a news post to edit"],
            "product" => ["delete" => "Choose a product to delete", "edit" => "Choose a product to edit"]
        ];

        $instance = \HELPER\Dynamic::instance($type, "MODEL");
        $data = \HELPER\Dynamic::call($instance, "getAll");

        $optionMethod = "create" . ucfirst($type) . "Options";

        $options = $this->$optionMethod($data);

        $localVariables = [
            "title" => $labels[$type][$action],
            "action" => $action,
            "type" => $type,
            "CSRF_FIELD" => $this->CSRF_FIELD(),
            "options" => $options,
            "btnType" => ($action === "edit") ? "warning" : "danger"
        ];

        $variables = array_merge($variables, $localVariables);

        return \HELPER\Renderer::render("admin/select-template.php", $variables);
    }

}