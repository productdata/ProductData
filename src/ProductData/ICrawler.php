<?php
	
	
12345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123

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
	public function start();
}
