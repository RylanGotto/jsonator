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