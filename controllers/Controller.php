<?php
abstract class Controller{
	private $request;
	public function __construct($request) {}
    protected function render($content) {
        header('Content-Type: application/json; charset=utf8');
        echo json_encode($content);
        return true;
    }
    protected function requestHandler($request){
        if ($request->method == 'GET'){
                //check to see if path contains more than one level, if only one level exists trigger getAll, if more than one level is present determine if path is valid and trigger correct response
                $path_count = count($request->path_info);
                if ($path_count == 1){ 
                    if(count($request->parameters) == 0){
                        //check if parameters exist, if no parameters return all of the records LIMIT 1000
                        $this->getAll($request);
                    }else{
                        graceful404();
                    }
                }elseif($path_count == 2 && preg_match('/^[0-9]{1,6}+$/', $request->path_info[1])){ //path should contain at most 2 levels, level two should only contain numeric characters 
                    $this->getById($request);
                }elseif($path_count >= 3){
                    graceful404();                        
                }              
            }else{
                graceful404('Method type is not supported');
            }   
    }  
    //get all
    public function getAll($request){
        echo 'get all';
    }
    //get by id
    public function getById($request){
        echo 'get by id';
    }
}