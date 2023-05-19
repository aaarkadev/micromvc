<?php

class Request {

    public $data = array();

    public function __construct(array $params, array $server) { 
        $this->data['params'] = $params;
        $this->data['server'] = $server;

        $baseUrl = $this->data['server']['PHP_SELF'];
        $pos = stripos($baseUrl,'index.php');
        if($pos !== false) {
            $baseUrl = substr($baseUrl,0,$pos);
        }
        $baseUrl = rtrim($baseUrl.'/',' /').'/index.php/';
        $this->data['baseUrl'] = $baseUrl;
    }

}
