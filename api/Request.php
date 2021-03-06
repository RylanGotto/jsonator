<?php
/**
* Request encapsulates the incoming HTTP request
*
* @author Rylan Gotto <rgotto2@gmail.com>
*/
class Request {

    public $method;
    public $path_info;
    public $parameters;
    public $put_data;
    public $post_data;

    /**
     * Construct Request Object and allow CORS
     */
    public function __construct() {
        
        header("Access-Control-Allow-Orgin: *");
        header("Access-Control-Allow-Methods: *");

        $this->method = $_SERVER['REQUEST_METHOD'];

        if($this->method == 'PUT')
        {
            $this->put_data = $this->getRawDataFromInput();
        }else if($this->method == 'POST'){         
            $this->post_data = $this->getRawDataFromInput();
        }

        if (isset($_SERVER['PATH_INFO'])) {
            $this->path_info = explode('/', trim($_SERVER['PATH_INFO'], '/'));
        }
        $this->parameters = array();
        if (isset($_SERVER['QUERY_STRING'])) {
            parse_str($_SERVER['QUERY_STRING'], $this->parameters);
        }
    }

    /**
     * Get RAW post or put data
     */
    public function getRawDataFromInput()
    {
            $fp = fopen('php://input', 'r');
            $pdata = '';
            while($data = fread($fp, 1024))
                $pdata .= $data;
            fclose($fp);

            return $pdata;
    }

}