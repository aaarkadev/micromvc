<?php

class Response {

    public $data = array();

    public function __construct(array $data) { 
        $this->data = $data;
    }

}
