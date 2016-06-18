<?php

namespace TheDeceased\YandexMoney;

class Client
{
	/** @var  string */
	protected $shopId;
	/** @var  string */
	protected $token;
	/** @var IHasher */
	protected $hasher;
	/** @var IProcessor|null */
	protected $processor;

	protected $requestParameters =  array(
		'action',
		'md5',
		'orderSumAmount', // Сумма платежа, без учета коммиссий
		'orderSumCurrencyPaycash', //Валюта платежа
		'orderSumBankPaycash', // Код процессингового центра в Яндекс.Деньгах для суммы заказа
		'shopId', // ID магазина - получается из Я.Кассы
		'invoiceId', // ID транзакции в Я.Кассе
		'orderNumber', // Номер заказа (отдаём в форме)
		'customerNumber', // Номер посетителя (отдаём в форме)
	);

	public function __construct($shop_id, $token, IHasher $hasher, IProcessor $processor = null)
	{
		$this->shopId = $shop_id;
		$this->token = $token;
		$this->processor = $processor;
		$this->hasher = $hasher;
	}

	/**
	 * @param $request
	 *
	 * @return IResponse
	 */
	public function checkOrder($request)
	{
		$requestData = array();
		foreach ($this->requestParameters as $parameter) {
			$requestData[$parameter] = $request[$parameter];
		}
		$code = 0;
		if (!$this->hasher->isValid($requestData['md5'], array_merge($requestData, array('token' => $this->token)))) {
			$code = 1;
		}
		$response = $this->buildResponse('checkOrder', $requestData['invoiceId'], $code);
		if ($this->processor) {
			$this->processor->paymentChecked($requestData, $response);
		}
		return $response;
	}

	/**
	 * @param $request
	 *
	 * @return IResponse
	 */
	public function paymentAviso($request)
	{
		$requestData  = array();
		foreach ($this->requestParameters as $parameter) {
			$requestData[$parameter] = $request[$parameter];
		}
		$response = $this->buildResponse('paymentAviso', $requestData['invoiceId'], 0);
		if ($this->processor) {
			$this->processor->paymentAviso($requestData, $response);
		}
		return $response;
	}

	/**
	 * @param string $action
	 * @param string $invoiceId
	 * @param int    $statusCode
	 *
	 * @return IResponse
	 */
	protected function buildResponse($action, $invoiceId, $statusCode)
	{
		return new Response($action, $invoiceId, $statusCode, $this->shopId);
	}
}
