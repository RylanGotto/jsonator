<?php
class Request {
    public $method;
    public $path_info;
    public $parameters;
    public function __construct() {
        header("Access-Control-Allow-Orgin: *");
        header("Access-Control-Allow-Methods: *");

        $this->method = $_SERVER['REQUEST_METHOD'];
        if (isset($_SERVER['PATH_INFO'])) {
            $this->path_info = explode('/', trim($_SERVER['PATH_INFO'], '/'));
        }
        $this->parameters = array();
        if (isset($_SERVER['QUERY_STRING'])) {
            parse_str($_SERVER['QUERY_STRING'], $this->parameters);
        }
    }
}