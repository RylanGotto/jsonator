<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
include_once(dirname(__FILE__) . '/../../db_utils/JSONator.php');
include_once(dirname(__FILE__) . '/../../models/User.php');

final class JSONatorTest extends TestCase
{
    public function testRead()
    {
        $configs = include(dirname(__FILE__) . '/../../db_utils/settings.php');
        $this->JSONator = new JSONator($configs['userCollectionFilePathTest'], User::jsonSchema());
        
        $expected = array(array("first"=>"Rylan", "last"=>"Gotto","reservations"=>[1],"id"=>1,"email"=>"rgotto2@gmail.com"));
        $this->assertEquals($expected, $this->JSONator->read());
    }
}
