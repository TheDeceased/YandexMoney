<?php

namespace TheDeceased\YandexMoney;

interface IProcessor
{
	public function paymentChecked($requestData, $responseData);

	public function paymentAviso($requestData, $responseData);
}