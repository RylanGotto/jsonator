<?php

include_once 'Model.php' ;


class User extends Model
{
	function __construct($json)
	{
		$this->first = $json['first'];
		$this->last = $json['last'];
		$this->reservations = $json['reservation'];
	}
	public static function jsonSchema() 
	{
        return array(
        	"first" => "string",
        	"last" => "string",
        	"reservation" => "array"
        	);
    }
}