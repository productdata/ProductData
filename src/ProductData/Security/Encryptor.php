<?php
namespace ProductData\Security;

use Defuse\Crypto\Crypto;

final class Encryptor
{
	private $key;

	public function __construct($key)
	{
		$this->key = base64_decode($key);
	}

	public function decrypt($text)
	{
		return Crypto::decrypt(base64_decode($text), $this->key);
	}

	public function encrypt($text)
	{
		return base64_encode(Crypto::encrypt($text, $this->key));
	}
}