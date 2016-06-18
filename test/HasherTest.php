<?php

namespace TheDeceased\YandexMoney\Tests;

use TheDeceased\YandexMoney\Hasher;

class HasherTest extends \PHPUnit_Framework_TestCase
{
	/** @var  \TheDeceased\YandexMoney\IHasher */
	protected $hasher;
	protected function setUp()
	{
		parent::setUp();
		$this->hasher = new Hasher();
	}

	public function testHashGeneratesValid()
	{
		$hashParameters = array(
			'action' => 'checkOrder',
			'orderSumAmount' => '100',
			'orderSumCurrencyPaycash' => '100',
			'orderSumBankPaycash' => '100',
			'shopId' => '1',
			'invoiceId' => '100',
			'customerNumber' => '1',
			'token' => 'thisTokenIsNotSoSecret',
		);
			
		$this->assertEquals(strtoupper(md5(implode(';', $hashParameters))), $this->hasher->hash($hashParameters));
	}

	public function testHasherValidatesCorrectHash()
	{
		$hashParameters = array(
			'action' => 'checkOrder',
			'orderSumAmount' => '100',
			'orderSumCurrencyPaycash' => '100',
			'orderSumBankPaycash' => '100',
			'shopId' => '1',
			'invoiceId' => '100',
			'customerNumber' => '1',
			'token' => 'thisTokenIsNotSoSecret',
		);

		$this->assertTrue($this->hasher->isValid(strtoupper(md5(implode(';', $hashParameters))), $hashParameters));
	}

	public function testHasherInvalidatesInCorrectHash()
	{
		$hashParameters = array(
			'action' => 'checkOrder',
			'orderSumAmount' => '100',
			'orderSumCurrencyPaycash' => '100',
			'orderSumBankPaycash' => '100',
			'shopId' => '1',
			'invoiceId' => '100',
			'customerNumber' => '1',
			'token' => 'thisTokenIsNotSoSecret',
		);

		$this->assertFalse($this->hasher->isValid('some_random_string_parameter', $hashParameters));
	}
}