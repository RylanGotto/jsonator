<?php
include('Controller.php');
include(dirname(__FILE__) . '/../db_utils/JSONator.php');
include(dirname(__FILE__) . '/../models/Reservation.php');
/**
* ReservationController handles all request to end points:
* http://localhost/jsonator/api.php/reservation
* http://localhost/jsonator/api.php/reservation/$reservationID
*
* @author Rylan Gotto <rgotto2@gmail.com>
*/
class ReservationController extends Controller
{
    /**
     * Construct ReservationController Object and establish connection to DB. Invoke respective request
     * handler for ReservationController
     * @param Request $request The Request that contains the method and data required.
     */
	public function __construct($request) 
	{  
		$configs = include(dirname(__FILE__) . '/../db_utils/settings.php');    
		$this->reservationDB = new JSONator($configs['reservationCollectionFilePath'], Reservation::jsonSchema());  
		$this->requestHandler($request);
    }
    /**
     * Return all Reservation records from Reservation collection 
     */
    public function getAll()
    {
    	$this->renderResponse($this->reservationDB->read());
    }
    /**
     * Return single Reservation record from Reservation collection based on ID
     * @param Int $id The id of the record to be retreive from the Reservation Collection
     */
    public function getById($id)
    {
    	$this->renderResponse($this->reservationDB->fetchRecordById($id));
    }
    /**
     * Delete single Reservation record from Reservation collection based on ID
     * @param Int $id The id of the record to be deleted from the Reservation Collection
     */
    public function deleteRecordById($id)
    {
    	$this->renderResponse($this->reservationDB->delete($id) );
    }
    /**
     * Delete single Reservation record from Reservation collection based on ID
     * @param Int $id The id of the record to be updated in the Reservation Collection
     * @param Request $request The Request that contains the PUT data required to process a response
     */
    public function updateRecordById($id, $request)
    {
    	$data = json_decode($request->put_data, true)[0];
    	$data = $this->reservationDB->update($id, $data);
    	$this->renderResponse($data);
    }
    /**
     * Add new single Reservation record to eservation collection
     * @param Int $id The id of the record to be updated in the Reservation Collection
     * @param Request $request The Request that contains the POST data required to process a response
     */
    public function addNewRecord($request)
    {   
        $data = json_decode($request->post_data, true)[0];
        $data = $this->reservationDB->create($data);
        $this->renderResponse($data);
    }
}
?>