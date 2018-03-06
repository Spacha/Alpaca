<?php

namespace App\Framework\Libs;

use App\Framework\Exceptions\InternalException;

/**
* 
*/
class Request
{
	protected $getData = [];
	protected $postData = [];

	public function __construct()
	{
		if ($this->method() == 'POST') {
			$this->postData = $_POST;
		}
	}

	/**
	 * Get a piece or all of the data stored to object's $data property
	 *
	 * @param sting $name 	Name or key of the data piece
	 * @return mixed 		Value of the data or an associative array of them
	 */
	public function data(string $name = '')
	{
		if (!strlen($name))
			return $this->postData;

		if (array_key_exists($name, $this->postData))
			return $this->postData[$name];

		return null;
	}

	/**
	 * Set an array of parameters to object's data property
	 * Setting a single value is easy: $this->setData(['key' => 'value'])
	 *
	 * @param mixed $params Single variable or array
	 * @return void
	 */
	public function setData($data)
	{
		if (is_array($data)) {
			$this->postData += $data;
		}
	}

	// public static function uri() : string {}
	public static function method() : string
	{
		return $_SERVER['REQUEST_METHOD'];
	}
}
