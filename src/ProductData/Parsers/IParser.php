<?php
namespace ProductData\Parsers;

use Nette\Http\Url;

interface IParser
{
	public function setData($data, Url $url);
}