<?php
/**
* FileNator handles JSON encoding and decoding, as well, all I/O to collection files:
* http://localhost/jsonator/api.php/login
*
* @author Rylan Gotto <rgotto2@gmail.com>
*/
class FileNator
{
	/**
     * Construct FileNator Object 
     * @param String $filePath The path of file to read/write from/to.
     */
	function __construct($filePath)
	{
		$this->filePath = $filePath;
	}
	/**
     * Read and decode/validate JSON from file.
     */
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
	/**
     * Encode JSON and write to file.
     * @param Array $json JSON to be written to collection file.
     */
	public function writeJSONtoFile($json)
	{

		$filePath = $this->filePath;
		$file = fopen($filePath, 'w')
		    	or exit("Unable to open file ($filePath)");
		if(count($json) == 0){
			fwrite($file, '[{}]'); //if nothing exist write default format to file
		}else{
			fwrite($file, json_encode($json));
		}
		
		fclose($file);
	}
}