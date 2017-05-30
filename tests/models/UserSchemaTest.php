<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
include_once(dirname(__FILE__) . '/../../models/User.php');


final class UserSchemaTest extends TestCase
{
    public function testUserModelReturnsCorrectSchema()
    {
         $this->assertEquals(
                    array(
            "email" => "string",
            "first" => "string",
            "last" => "string",
            "reservations" => "array"
            ),
            User::jsonSchema()
        );

    }
}
