<?php

class FileNator
{
	function __construct($filePath)
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
		$filePath = $this->filePath;
		$file = fopen($filePath, 'w')
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
	private function reloadJSONfromFile()
	{
		$this->JSON = $this->fileNator->getJSONfromFile();
	}
    public function read($type)
    {
    	return $json;
    }
    public function write($record)
    {
    	if($this->validateJSON($record))
    	{
    		$newRecordID;
    		$this->reloadJSONfromFile();
    		if(count($this->JSON[0]) != 0)
    		{
    			$newRecordID = $this->JSON[count($this->JSON) - 1]['id'] + 1; //get last ID in collection and set the new record's ID to last ID plus one.
    		}
    		else
    		{
    			array_pop($this->JSON); //remove default array when file is empty.
    			$newRecordID = 1;
    		}
    		
    		$record['id'] = $newRecordID;
    		array_push($this->JSON, $record);
    		$this->fileNator->writeJSONtoFile($this->JSON);
    		
    	}
    	else
    	{
    		return false;
    	}
    	$this->reloadJSONfromFile(); //reload new collection are return.
    	return $this->JSON;
    }
    public function update($id, $record)
    {
    	$indexToUpdate;
    	if($this->validateJSON($record))
    	{
    		$this->reloadJSONfromFile();

    		foreach(array_values($this->JSON) as $i => $val) //find record in collection to update.
    		{
    			if($id == $val['id']) //if id is found update values of record found in collection.
    			{
    				foreach($record as $key => $val1) 
    				{
    					$this->JSON[$i][$key] = $val1;
    					break;
    				}
    			}
    		}

    		$this->fileNator->writeJSONtoFile($this->JSON);
    		$this->reloadJSONfromFile();

    	}
    	else
    	{
    		return false;
    	}
    }
    public function delete($id)
    {
    	foreach(array_values($this->JSON) as $i => $val)
    	{
    		if($id == $val['id'])
    		{
    			unset($this->JSON[$i]);
    			$this->JSON = array_values($this->JSON);
    			$this->fileNator->writeJSONtoFile($this->JSON);
    			$this->reloadJSONfromFile();
    			
    			return $this->JSON;
    		}
    	}
    	return false;

    }
    public function printSomeShit()
    {
    	var_dump($this->fetchRecordById(4));
    }
    public function fetchRecordById($id)
	{
		$this->reloadJSONfromFile();
		foreach ($this->JSON as $key => $value) 
		{
			if($id == $value['id'])
			{
				return $this->JSON[$key];
			}
		}
		return false;
	}

	public function validateJSON($json)
	{
		$valid = true;
    	$schema = $this->schema;
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
        	"reservation" => "array"
        	);
    }
}

$jsonator = new JSONator('user.json', User::jsonSchema());
$jsonator->printSomeShit();
