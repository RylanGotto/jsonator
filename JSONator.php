<?php

class FileNator
{
	function __construct($filePath)
	{
		$this->filePath = $filePath;
	}
	public function updateFilePath($filePath)
	{
		$this->filePath = $filePath;
	}
	public function getJSONfromFile()
	{
		$filePath = $this->filePath;
		$file = fopen($filePath, 'r')
    		or exit("Unable to open file ($filePath)");

		$json = json_decode(file_get_contents($filePath), true);
		if($json == NULL)
		{
			exit("Invalid JSON format please validate file ($filePath)");
		}
		else
		{
			return $json;
		}
	}
	public function writeJSONtoFile($json)
	{
		$file = fopen('results.json', 'w')
		    	or exit("Unable to open file ($filePath)");


		fwrite($file, json_encode($json));
		fclose($file);
	}
}



class JSONator 
{
	function __construct($filePath, $schema)
	{
		$this->filePath = $filePath;
		$this->fileNator = new FileNator($filePath);
		$this->JSON = $this->fileNator->getJSONfromFile();
		$this->schema = $schema;
	}
    public function read($type)
    {
    	return $json;
    }
    public function write($type, $record)
    {
    	echo $this->validateJSON($type, $this->JSON) ? 'true' : 'false';
    }
    public function update($type, $id, $record)
    {
    }
    public function delete($type, $id)
    {
    }
    public function printSomeShit()
    {
    	$this->write('u', []);
    }
    public function fetchRecordById($type, $id)
	{
		foreach ($this->JSON as $key => $value) 
		{
			if($id == $value['id'])
			{
				return $json[$key];
			}
		}
		return;
	}

	public function validateJSON($type, $json)
	{
		$json = $json[0]; //TODO: CHANGE THIS LINE TO ACCOMODATE REAL FLOW
		$valid = true;
    	$schema = $this->schema;
    	var_dump($json);
		if(is_array($json))
		{
			foreach($json as $key => $val)
			{
				if(isset($schema[$key]))
				{
					if($schema[$key] != gettype($val))
					{
						$valid = false;
					}
				}
				else
				{
					$valid = false;
					break;
				}
			}
		}
    	return $valid;
	}
}

abstract Class Model
{
	function __construct($json)
	{
		$this->id = $json['id'];
	}
	abstract public static function jsonSchema();
}

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
        	"return" => "string",
        	"id" => "integer",
        	"reservation" => "array"
        	);
    }
}

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
        	"id" => "integer",
        	"reservation" => "array"
        	);
    }
}

$jsonator = new JSONator('user.json', User::jsonSchema());
$jsonator->printSomeShit();
