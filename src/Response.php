<?php

namespace TheDeceased\YandexMoney;

class Response implements IResponse
{
	private $action;
	private $invoiceId;
	private $statusCode;
	private $shopId;

	public function __construct($action, $invoiceId, $statusCode, $shopId)
	{
		$this->action = $action;
		$this->invoiceId = $invoiceId;
		$this->statusCode = $statusCode;
		$this->shopId = $shopId;
	}

	public function getStatus()
	{
		return $this->statusCode;
	}
	
	public function asString()
	{
		$doc = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?>' . '<' . $this->action . 'Response/>');
		$doc->addAttribute('performedDatetime', $this->getCurrentDate());
		$doc->addAttribute('code', $this->statusCode);
		$doc->addAttribute('invoiceId', $this->invoiceId);
		$doc->addAttribute('shopId', $this->shopId);
		return $doc->asXML();
	}

	/**
	 * Пример желаемого формата: 2011-05-04T20:38:01.000+04:00
	 * @return bool|string
	 */
	private function getCurrentDate()
	{
		return date('Y-m-d\TH:i:s.000P');
	}
}