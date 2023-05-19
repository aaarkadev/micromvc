<?php

require_once(APP_BASE.'src/Response.php');

class View {

    private $template;
    private $path;


    public function __construct() { 
        $this->path = APP_BASE.'src/templates/';
    }

    public function SetTemplate(string $template) {
        $this->template = $template;
    }

    public function Render(Response $response) {
         include($this->path.'header.php');
        if(file_exists($this->path.$this->template.'.php')) {
            extract($response->data, EXTR_OVERWRITE|EXTR_PREFIX_INVALID, 'err');
            include($this->path.$this->template.'.php');
        }
        include($this->path.'footer.php');
    }
}
