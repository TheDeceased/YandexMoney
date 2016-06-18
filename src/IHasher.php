<?php

namespace TheDeceased\YandexMoney;

interface IHasher
{
	public function hash(array $parameters);

	public function isValid($hash, array $parameters);
}