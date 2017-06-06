<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
include_once('HttpRequest.php');

final class LoginControllerTest extends TestCase
{

	public function setUp() {
		$this->dummyUserData = array(array("first"=>"Rylan", "last"=>"Gotto","reservations"=>[1],"id"=>1,"email"=>"rgotto2@gmail.com"));
	}
	public function testLoginRequestHandlerWithValidLoginAttempt()
	{
		$url = 'http://localhost/jsonator/api.php/login';

		$data = array(array('email' => 'rgotto2@gmail.com'));

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS,
		            json_encode($data));

		// receive server response ...
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$server_output = curl_exec ($ch);

		curl_close ($ch);

		if ($server_output != "<h1>404 Not Found</h1>Invalid Email Address") { 
			$input = json_decode($server_output, true);
			$expected = $this->dummyUserData[0];
			$this->assertEquals($expected, $input);
		} else {
			$this->assertTrue(false); 
		}
	}

}