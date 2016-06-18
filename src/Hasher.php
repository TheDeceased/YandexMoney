<?php

namespace TheDeceased\YandexMoney;

class Hasher implements IHasher
{
	protected $hashFields = array(
		'action',
		'orderSumAmount',
		'orderSumCurrencyPaycash',
		'orderSumBankPaycash',
		'shopId',
		'invoiceId',
		'customerNumber',
		'token',
	);

	public function hash(array $parameters)
	{
		$values = array();
		foreach ($this->hashFields as $field) {
			$values[] = (string)$parameters[$field];
		}
		return strtoupper(md5(implode(';', $values)));
	}

	public function isValid($hash, array $parameters)
	{
		return $hash === $this->hash($parameters);
	}
}