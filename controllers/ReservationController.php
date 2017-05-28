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
    public function getAll($request){
    	$this->render($this->reservationDB->read());
    }
}
?>