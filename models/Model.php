<?php
abstract Class Model
{
	function __construct($json)
	{
		$this->id = $json['id'];
	}
	abstract public static function jsonSchema();
}