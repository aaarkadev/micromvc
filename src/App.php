<?php

require_once(APP_BASE.'src/Router.php');
require_once(APP_BASE.'src/Request.php');
require_once(APP_BASE.'src/View.php');
require_once(APP_BASE.'src/Db.php');
require_once(APP_BASE.'src/controllers/Category.php');
require_once(APP_BASE.'src/controllers/Tovar.php');


class App {

    public function __construct(array $config) {

        $this->request =  new Request($_REQUEST, $_SERVER);
        $this->router = new Router();
        $this->view = new View();

        try {
            $this->db = new Db($config['DB']['host'],$config['DB']['username'],$config['DB']['password'],$config['DB']['database']);
        } catch (\Throwable $e) {
            echo '['.__FILE__.':'.__LINE__.'] '. $e->getMessage();
            die;
        }

        $this->Init();
    }

    public function Init() {

        $this->router->AddRoute('#/tovar/([0-9]+).*?$#', Tovar::class);
        $this->router->AddRoute('#/(.*?)$#', Category::class);
    }

    public function Run() {

        $response = $this->router->Handle($this->request, $this->view, $this->db);
        $this->view->Render($response);

    }
}
