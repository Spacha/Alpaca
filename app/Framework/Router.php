<?php

namespace App\Framework;

Class Router
{
	protected $url = '';
	protected $routes = [];
	protected $callables = [];

	public function __construct($url = '', $routes = [])
	{
		echo "<li> Router Registered";
		echo "<ul><li><b>Current route</b>: " . $url . "</ul>";

		$this->url = $url;
		$this->routes = $routes;
		$this->splitUrl();
	}

	private function splitUrl()
	{
		return;
	}

	public function getCallables() : array
	{
		return $this->callables;
	}

	public function getRoutes() : array
	{
		return $this->routes;
	}
}
