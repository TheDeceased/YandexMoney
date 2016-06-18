<?php

namespace TheDeceased\YandexMoney;

interface IResponse
{
	public function __construct($action, $invoiceId, $statusCode, $shopId);

	public function getStatus();
	
	public function asString();

}