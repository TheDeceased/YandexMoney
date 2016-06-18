<?php

namespace TheDeceased\YandexMoney\Tests;

use TheDeceased\YandexMoney\Client;
use TheDeceased\YandexMoney\Hasher;
use TheDeceased\YandexMoney\Response;

class ClientTest extends \PHPUnit_Framework_TestCase
{
	/** @var  Client */
	protected $client;
	protected function setUp()
	{
		parent::setUp();
		$this->client = new Client(1, 'asd', new Hasher());
	}

	protected $mockData = array(
		'action' => 'checkOrder',
		'md5' => 'some_string',
		'orderSumAmount' => '100',
		'orderSumCurrencyPaycash' => '100',
		'orderSumBankPaycash' => '100',
		'shopId' => '1',
		'invoiceId' => '1',
		'orderNumber' => '1',
		'customerNumber' => '1',
	);

	public function testCheckOrderReturnsXML()
	{
		$response = $this->client->checkOrder($this->mockData);
		$this->assertTrue($response instanceof Response);
	}
	
	public function testCheckResponseSetErrorStatus()
	{
		$response = $this->client->checkOrder($this->mockData);
		$this->assertEquals(1, $response->getStatus());
	}

	public function testCheckResponseCallProcessor()
	{
		$processor = $this->getMockBuilder('\TheDeceased\YandexMoney\IProcessor')
			->setMethods(array('paymentChecked', 'paymentAviso'))
			->getMock();
		$processor->expects($this->once())->method('paymentChecked');
		$client = new Client(1, 'asd', new Hasher(), $processor);
		$client->checkOrder($this->mockData);
	}


	public function testCheckOrderSetSuccessStatusOnCorrectHash()
	{
		$hasher = new Hasher();
		$token = 'asd';
		$client = new Client(1, $token, $hasher);
		$response = $client->checkOrder(array_merge($this->mockData, array(
			'md5' => $hasher->hash(array_merge($this->mockData, array(
				'token' => $token,
			)))
		)));
		$this->assertEquals(0, $response->getStatus());
	}

	public function testAvisoResponseCallProcessor()
	{
		$processor = $this->getMockBuilder('\TheDeceased\YandexMoney\IProcessor')
			->setMethods(array('paymentChecked', 'paymentAviso'))
			->getMock();
		$processor->expects($this->once())->method('paymentAviso');
		$client = new Client(1, 'asd', new Hasher(), $processor);
		$client->paymentAviso($this->mockData);
	}
}
