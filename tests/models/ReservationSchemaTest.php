<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
include_once(dirname(__FILE__) . '/../../models/Reservation.php');

final class ReservationSchemaTest extends TestCase
{
    public function testReservationModelReturnsCorrectSchema()
    {
         $this->assertEquals(
                    array(
            "depart" => "string",
            "return" => "string"
            ),
            Reservation::jsonSchema()
        );

    }
}
