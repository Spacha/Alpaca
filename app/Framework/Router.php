<?php

namespace App\Framework;

Class Router
{
	public function __construct($route = "")
	{
		echo "<li> Router Registered";
		echo "<ul><li><b>Current route</b>: " . $route . "</ul>";
	}
}
