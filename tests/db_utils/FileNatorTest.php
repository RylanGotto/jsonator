<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
require_once(dirname(__FILE__) . '/../../db_utils/FileNator.php');

final class FileNatorTest extends TestCase
{

    public function testGetJSONfromFile()
    {
    	$configs = include(dirname(__FILE__) . '/../../db_utils/settings.php');
        $FileNator = new FileNator($configs['userCollectionFilePathTest']);;
        $expected = array(array("first"=>"Rylan", "last"=>"Gotto","reservations"=>[1],"id"=>1,"email"=>"rgotto2@gmail.com"));
        $this->assertEquals($expected, $FileNator);
    }
}