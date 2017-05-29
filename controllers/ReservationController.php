<?php
include('Controller.php');
include(dirname(__FILE__) . '/../db_utils/JSONator.php');
include(dirname(__FILE__) . '/../models/Reservation.php');

class ReservationController extends Controller
{
	public function __construct($request) 
	{  
		$configs = include(dirname(__FILE__) . '/../db_utils/settings.php');    
		$this->reservationDB = new JSONator($configs['reservationCollectionFilePath'], Reservation::jsonSchema());  
		$this->requestHandler($request);
    }

    public function getAll($request)
    {
    	$this->renderResponse($this->reservationDB->read());
    }
    public function getById($id, $request)
    {
    	$this->renderResponse($this->reservationDB->fetchRecordById($id));
    }
    public function deleteRecordById($id, $request)
    {
    	$this->renderResponse($this->reservationDB->delete($id) );
    }
    public function updateRecordById($id, $request)
    {
    	$data = json_decode($request->put_data, true)[0];
    	$data = $this->reservationDB->update($id, $data);
    	$this->renderResponse($data);
    }
    public function addNewRecord($request)
    {   
        $data = json_decode($request->post_data, true)[0];
        $data = $this->reservationDB->create($data);
        $this->renderResponse($data);
    }
}
?>