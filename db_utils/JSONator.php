<?php

include("FileNator.php");

/**
* Lightweight mini nosql DBMS w/ schema validtion
*
* JSONator handles enforced schema validation of all JSON to be wrote to its repsective collection. Schemas can be       * defined in model files. JSONator offers CRUD style access to collection files in a noSQL style document storage.
* Very lightweight document references must be done manually. No unique enforcements 
*
* @author Rylan Gotto <rgotto2@gmail.com>
*/
class JSONator 
{
    /**
     * Construct FileNator Object 
     * @param String $filePath The path of file to read/write from/to.
     * @param Array $schema The path of file to read/write from/to.
     */
    function __construct($filePath, $schema)
    {
        $this->filePath = $filePath;
        $this->fileNator = new FileNator($filePath);
        $this->JSON = $this->fileNator->getJSONfromFile();
        $this->schema = $schema;
    }
    /**
     * Reload JSON from file into memory, used after a change is made to the collection file in order 
     * to keep collections file and JSON in memory in sync
     */
    private function reloadJSONfromFile()
    {
        $this->JSON = $this->fileNator->getJSONfromFile();
    }
    /**
     * Return ALL records in collection
     */
    public function read()
    {
        if(count($this->JSON[0]) != 0)
        {
            return $this->JSON;
        }
        return false;
        
    }
    /**
     * Append new record to collection if candidate is valid
     * @param Array $record New candidate array to be wrote to collection file 
     */
    public function create($record)
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
        return end($this->JSON);
    }
    /**
     * Update record in collection if ID exists and if candidate is valid
     * @param Array $record New candidate array to be wrote to collection file 
     * @param Int $id ID of record to lookup and modify
     */
    public function update($id, $record)
    {
        $indexToUpdate;
        $foundId = true;

        if($this->validateJSON($record))
        {
            $this->reloadJSONfromFile();

            foreach(array_values($this->JSON) as $i => $val) //find record in collection to update.
            {
                if(intval($id) == intval($val['id'])) //if id is found update values of record found in collection.
                {

                    foreach($record as $key => $val1) 
                    {

                        $this->JSON[$i][$key] = $val1;  
                    }
                    $foundId = true;
                    break;
                }else{
                    $foundId = false;
                }
            }
            if(!$foundId)
            {
                return false;
            }

            

            $this->fileNator->writeJSONtoFile($this->JSON);

            $this->reloadJSONfromFile();
            return end($this->JSON);

        }
        else
        {
            return false;
        }

    }
    /**
     * Delete record in collection if ID exists
     * @param Int $id ID of record to lookup and delete
     */
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
    /**
     * Fetch record in collection if ID exists
     * @param Int $id ID of record to lookup and return
     */
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

    /**
     * Validate candidate record against schema. Schema enforces 
     * correct Key NAME and value TYPE. See models for schema definition
     * @param Array $json The record to be validated against schema
     */
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