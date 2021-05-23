<?php

namespace App\Response;

class Response {

    private $content;

    public function __construct($content){
        $this->content=$content;
    }

    // display the content of the response
    public function send(){
        echo $this->content;
    }

}
