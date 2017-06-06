<?php
/**
* Abstract Controller determines which part of the REST standard will be invoked based on incoming HTTP method type. 
* Currently GET POST PUT DELETE are supported.
*
* @author Rylan Gotto <rgotto2@gmail.com>
*/
abstract class Controller{
	private $request;
	
    public function __construct($request) {}

    /**
     * Render HTTP response back to client
     * @param Array $content Valid array that will be converted to JSON and delievered via the HTTP response.
     */
    protected function render($content) {
        header('Content-Type: application/json; charset=utf8');
        echo json_encode($content);
        return true;
    }

    /**
     * Determines if HTTP method is allow, and determine if path is valid.
     * Correct combination of method and path is required for a valid request. 
     * Endpoints exist as followed:
     * http://localhost/jsonator/api.php/login - POST
     * http://localhost/jsonator/api.php/user - POST GET
     * http://localhost/jsonator/api.php/user/$userID - GET PUT
     * http://localhost/jsonator/api.php/reservation/$reservationID - POST PUT DELETE
     * http://localhost/jsonator/api.php/reservation - GET
     *
     * @param Request $request The Request that contains the method and data required to process a response.
     */
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

    /**
     * Check to see if $data exists
     * @param Array $context valid array that will be converted to JSON and delievered via the HTTP response.
     */
    public function renderResponse($data)
    {
        $response;
        if($data){
            $response = $data;
            $this->render($response);
        }
        else
        {
            graceful404('Incorrect Format or No records exist.');
        }
        
    }

    public function getAll($request){}
    public function getById($id, $request){}
    public function deleteRecordById($id, $request){}
    public function updateRecordById($id, $request){}
    public function addNewRecord($request){}

}