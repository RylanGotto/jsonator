<?php
include('Controller.php');
include(dirname(__FILE__) . '/../db_utils/JSONator.php');
include(dirname(__FILE__) . '/../models/User.php');

/**
* UserController handles all request to end points:
* http://localhost/jsonator/api.php/user
* http://localhost/jsonator/api.php/user/$userID
*
* @author Rylan Gotto <rgotto2@gmail.com>
*/
class UserController extends Controller{
     /**
     * Construct UserController Object and establish connection to DB. Invoke respective request
     * handler for User controller
     * @param Request $request The Request that contains the method and data required.
     */
	public function __construct($request) 
	{  
		$configs = include(dirname(__FILE__) . '/../db_utils/settings.php');
		$this->userDB = new JSONator($configs['userCollectionFilePath'], User::jsonSchema());  
		$this->requestHandler($request);
    }
    /**
     * Return all User records from User collection 
     */
    public function getAll($request)
    {
    	$this->renderResponse($this->userDB->read());
    }
    /**
     * Return single User record from User collection based on ID
     * @param Int $id The id of the record to be retreive from the User Collection
     */
    public function getById($id, $request)
    {
    	$this->renderResponse($this->userDB->fetchRecordById($id));
    }
    /**
     * Delete single User record from User collection based on ID
     * @param Int $id The id of the record to be deleted from the User Collection
     */
    public function deleteRecordById($id, $request)
    {
    	$this->renderResponse($this->userDB->delete($id) );
    }
    /**
     * Delete single User record from User collection based on ID
     * @param Int $id The id of the record to be updated in the User Collection
     * @param Request $request The Request that contains the PUT data required to process a response
     */
    public function updateRecordById($id, $request)
    {
    	$data = json_decode($request->put_data, true)[0];
    	$data = $this->userDB->update($id, $data);
    	$this->renderResponse($data);
    }
    /**
     * Add new single User record to User collection
     * @param Int $id The id of the record to be updated in the User Collection
     * @param Request $request The Request that contains the POST data required to process a response
     */
    public function addNewRecord($request)
    {   
        $data = json_decode($request->post_data, true)[0];
        $data = $this->userDB->create($data);
        $this->renderResponse($data);
    }
}
?>