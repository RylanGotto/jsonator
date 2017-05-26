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
	function __construct($userFilePath, $reserveationFilePath)
	{
		$this->userFilePath = $userFilePath;
		$this->resvationFilePath = $reserveationFilePath;

		$this->fileNator = new FileNator($userFilePath);

		$this->userJSON = $this->fileNator->getJSONfromFile();
		$this->fileNator->updateFilePath($reserveationFilePath);
		$this->reservationJSON = $this->fileNator->getJSONfromFile();
	}
    public function read($type)
    {
    	$json = $this->setJSONFile($type);
    	return $json;
    }
    public function write($type, $record)
    {
    	$json = $this->setJSONFile($type);
    	echo $this->validateJSON($type, $json) ? 'true' : 'false';
    }
    public function update($type, $id, $record)
    {
    	$json = $this->setJSONFile($type);
    }
    public function delete($type, $id)
    {
    	$json = $this->setJSONFile($type);
    }
    public function printSomeShit()
    {
    	$this->write('u', []);
    }
    public function fetchRecordById($type, $id)
	{
		$json = $this->setJSONFile($type);

		foreach ($json as $key => $value) 
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
		$json = $json[0];
		$schema;
		$valid = true;
		if($type == 'u')
    	{
    		$schema = User::jsonSchema();
    	}
    	else if($type == 'r')
    	{
    		$schema = Reservation::jsonSchema();
    	}

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

    private function setJSONFile($type)
    {
    	$json;
    	if($type == 'u')
    	{
    		$json = $this->userJSON;
    		$this->fileNator->updateFilePath($this->userFilePath);
    	}
    	else if($type == 'r')
    	{
    		$json = $this->reservationJSON;
    		$this->fileNator->updateFilePath($this->resvationFilePath);
    	}
    	return $json;
    }
}

class Reservation
{
	function __construct($json)
	{
		$this->id = $json['id'];
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

class User
{
	function __construct($json)
	{
		$this->first = $json['first'];
		$this->last = $json['last'];
		$this->id = $json['id'];
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

$jsonator = new JSONator('user.json', 'reservation.json');
$jsonator->printSomeShit();
