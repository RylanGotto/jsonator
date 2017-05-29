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
        $path_count = count($request->path_info);
        if ($request->method == 'GET'){
            
                //check to see if path contains more than one level, if only one level exists trigger getAll, if more than one level is present determine if path is valid and trigger correct response
                
                if ($path_count == 1){ 
                    if(count($request->parameters) == 0){
                     
                        //check if parameters exist, if no parameters return all of the records
                        $this->getAll($request);
                    }else{
                        graceful404();
                    }
                }elseif($path_count == 2 && preg_match('/^[0-9]{1,6}+$/', $request->path_info[1])){ //path should contain at most 2 levels, level two should only contain numeric characters 
                    $recordId = $request->path_info[1];
                    $this->getById($recordId, $request);
                }elseif($path_count >= 3){
                    graceful404();                        
                }              

            }else if($request->method == 'POST'){
                $this->addNewRecord($request);

            }else if($request->method == 'PUT'){
                if($path_count == 2 && preg_match('/^[0-9]{1,6}+$/', $request->path_info[1])){ //path should contain at most 2 levels, level two should only contain numeric characters 
                    $recordId = $request->path_info[1];
                    $this->updateRecordById($recordId, $request);
                }else{
                    graceful404();
                }
                
            }else if($request->method == 'DELETE'){
                if($path_count == 2 && preg_match('/^[0-9]{1,6}+$/', $request->path_info[1])){ //path should contain at most 2 levels, level two should only contain numeric characters 
                    $recordId = $request->path_info[1];
                    $this->deleteRecordById($recordId, $request);
                }else{
                    graceful404();
                }
            }else{
                graceful404('Method type is not supported');
            }
    }
    public function failRecordError($errorMsg)
    {
        return array(
                "error" => $errorMsg
            );
    }
    public function renderResponse($data)
    {
        $response;
        if($data){
            $response = $data;
        }
        else
        {
            $response = $this->failRecordError('Incorrect Format or No records exist.');
        }
        $this->render($response);
    }
    public function getAll($request){}
    public function getById($id, $request){}
    public function deleteRecordById($id, $request){}
    public function updateRecordById($id, $request){}
    public function addNewRecord($request){}
}