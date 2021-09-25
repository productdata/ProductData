<?php
namespace ProductData;

use Nette\Http\Url;

interface ICrawler
{
	/**
	 * @param Url $url
	 * @return array|null
	 */
	public function debug(Url $url);

	/**
	 * @return void
	 */
	public function start(\DibiRow $importRow);
}
