<?php

namespace App\Framework\Libs;

class View
{
	protected $data = [];

	public function __construct($view, $data = [])
	{
		$this->data = $data;
		$this->render();
	}

	public function render()
	{
		extract($this->data);
		echo $header;
		echo "This is the view render...";
	}
}
