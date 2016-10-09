<?php

namespace TheDeceased\YandexMoney;

interface IProcessor
{
	public function paymentChecked($requestData, $responseData);

	public function paymentAviso($requestData, $responseData);

	public function repeatCardPaymentFailed($requestData, $responseData);

	public function repeatCardPaymentSuccess($requestData, $responseData);
}