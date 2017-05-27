<?php

include("FileNator.php");
include("../models/User.php");
include("../models/Reservation.php");


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
    public function printSomething()
    {
        $res = array(
            "depart" => "string",
            "return" => "string",
            "reservation" => array()
            );
        var_dump($this->write($res));
    }
    public function fetchRecordById($id)
    {
        $this->reloadJSONfromFile();
        if(count($this->JSON[0]) != 0)
        {           
            foreach ($this->JSON as $key => $value) 
            {
                if($id == $value['id'])
                {
                    return $this->JSON[$key];
                }
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


$jsonator = new JSONator('../collections/reservation.json', Reservation::jsonSchema());
$jsonator->printSomething();