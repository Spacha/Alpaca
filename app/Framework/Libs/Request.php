<?php

namespace App\Framework\Libs;

use App\Framework\Exceptions\InternalException;

/**
 * This class is not in real use. It's just an experimental thing!
 * Yeshh.
 */
class Request
{
	protected $params = [];

	/**
	 * Sets array of parameters as the object's properties.
	 * 
	 * NOT IN USE!
	 *
	 * @param array $params
	 * @return void
	 */
	public function setParams(array $params) : void
	{
		foreach ($params as $key => $value) { 
			$this->__set($key, $value);
		}
	}

	/**
	 * Set a single parameter
	 *
	 * @param string $ey 
	 * @param $value 
	 * @return void
	 */
	public function __set(string $key, $value) : void
	{
		$this->params[$key] = $value;
	}

	/**
	 * Get a single parameter
	 *
	 * @param string $param
	 * @return $param
	 **/
	public function __get(string $key)
	{
		if (array_key_exists($key, $this->params)) {
			return $this->params[$key];
		}

		throw new InternalException("Parameter {$key} not found.");
	}
}
