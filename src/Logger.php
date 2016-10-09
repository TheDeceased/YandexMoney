<?php

namespace TheDeceased\YandexMoney;

class Logger
{
	private $path = './wp-content/plugins/gita_nagari_yandex_money/logs/log.log';
	public function __construct()
	{
	}

	public function add_message($message)
	{
		file_put_contents($this->path, $message . "\n", FILE_APPEND);
	}

	public function add_array($array, $message = '')
	{
		$text = $message ? ($message . ': ') : '';
		$text .= print_r($array, true);
		file_put_contents($this->path, $text . "\n", FILE_APPEND);
	}
}