<?php

include_once 'Model.php' ;


class User extends Model
{
	function __construct($json)
	{
		$this->first = $json['first'];
		$this->last = $json['last'];
		$this->email = $json['email'];
		$this->reservations = $json['reservation'];
	}
	public static function jsonSchema() 
	{
        return array(
        	"email" => "string",
        	"first" => "string",
        	"last" => "string",
        	"reservations" => "array"
        	);
    }
}