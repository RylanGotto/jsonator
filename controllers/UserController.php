<?php
include('Controller.php');
include(dirname(__FILE__) . '/../db_utils/JSONator.php');
include(dirname(__FILE__) . '/../models/User.php');


class UserController extends Controller{
	public function __construct($request) 
	{  
		$configs = include(dirname(__FILE__) . '/../db_utils/settings.php');
		$this->userDB = new JSONator($configs['userCollectionFilePath'], User::jsonSchema());  
		$this->requestHandler($request);
    }

    public function getAll($request)
    {
    	$this->renderResponse($this->userDB->read());
    }
    public function getById($id, $request)
    {
    	$this->renderResponse($this->userDB->fetchRecordById($id));
    }
    public function deleteRecordById($id, $request)
    {
    	$this->renderResponse($this->userDB->delete($id) );
    }
    public function updateRecordById($id, $request){}
    public function addNewRecord($record, $request){}
}
?>