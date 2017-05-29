<?php

include_once 'Model.php';

class Reservation extends Model
{
	function __construct($json)
	{
		$this->depart = $json['depart'];
		$this->return = $json['return'];
	}
	public static function jsonSchema() 
	{
        return array(
        	"depart" => "string",
        	"return" => "string"
        	);
    }
}