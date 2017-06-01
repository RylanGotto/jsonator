<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
include_once(dirname(__FILE__) . '/../../db_utils/JSONator.php');
include_once(dirname(__FILE__) . '/../../models/User.php');

final class JSONatorTest extends TestCase
{
	private $JSONator;
	

	public function setUp() {
		$configs = include(dirname(__FILE__) . '/../../db_utils/settings.php');
		$this->collectionPaths = [$configs['userCollectionFilePathTest'], $configs['reservationCollectionFilePathTest']];
		$this->collectionDir = $configs['collectionDir'];
		$this->JSONator = new JSONator($configs['userCollectionFilePathTest'], User::jsonSchema());
		$this->dummyUserData = array(array("first"=>"Rylan", "last"=>"Gotto","reservations"=>[1],"id"=>1,"email"=>"rgotto2@gmail.com"));
		$this->dummyUserDataNoID = array(array("first"=>"Rylan", "last"=>"Gotto","reservations"=>[1],"email"=>"rgotto2@gmail.com"));
	}

	public function tearDown() {
		$this->JSONator = null;
	}
	public function testReadRecordFromFile()
    {
        $expected = $this->dummyUserData;
        $this->assertEquals($expected, $this->JSONator->read());
    }
    public function testCreateRecordAndStoreInFile()
    {
        $input = $this->dummyUserDataNoID[0];
    	$expected = $this->dummyUserData[0];
    	$expected['id'] = 2;
        $this->assertEquals($expected, $this->JSONator->create($input));
    }
    public function testUpdateRecordAndStoreInFile()
    {
        $input = $this->dummyUserDataNoID[0];
        $input['first'] = 'Tyler';
        $expected = $this->dummyUserData[0];
        $expected['id'] = 2;
        $expected['first'] = 'Tyler';
        $this->assertEquals($expected, $this->JSONator->update($expected['id'], $input));
    }
    public function testDeleteRecordAndStoreInFile()
    {
        $expected = $this->dummyUserData;
        $this->assertEquals($expected, $this->JSONator->delete(2));
    }
	public function testDoesDirExist()
	{
		$this->assertDirectoryExists($this->collectionDir);
	}
	public function testIsDirectoryWriteable()
	{
		$this->assertDirectoryIsWritable($this->collectionDir);
	}
	public function testISDirectoryReadable()
	{
		$this->assertDirectoryIsReadable($this->collectionDir);
	}
	public function testIfCollectionsFileExist()
	{
		foreach($this->collectionPaths as $val){
			$this->assertFileExists($val);
		}
		
	}
	public function testIfCollectionFileReadable()
	{
		foreach($this->collectionPaths as $val){
			$this->assertFileExists($val);
		}
	}
	public function testIfCollectionFileWriteable()
	{
		foreach($this->collectionPaths as $val){
			$this->assertFileExists($val);
		}
	}


}
