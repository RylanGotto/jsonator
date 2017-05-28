<?php
include('Controller.php');
include(dirname(__FILE__) . '/../db_utils/JSONator.php');
include(dirname(__FILE__) . "/../models/User.php");


class UserController extends Controller{
	public function __construct($request) {  
		$configs = include(dirname(__FILE__) . '/../db_utils/settings.php');
		$this->userDB = new JSONator($configs['userCollectionFilePath'], User::jsonSchema());  
		$this->requestHandler($request);
    }
    public function getAll($request){
    	$this->render($this->userDB->read());
    }
    public function getById($request){
    	echo 1234;
    }
    public function deleteRecordById($request){}
    public function updateRecordById($request){}
    public function addNewRecord($record){}
}
?>