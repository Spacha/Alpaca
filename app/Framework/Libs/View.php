<?php

namespace App\Framework\Libs;

class View
{
	protected $template = '';
	protected $data = [];

	public function __construct($template, $data = [])
	{
		//$this->template = $template;
		$this->template = app_path('views', 'users/list.phtml');
		$this->data = $data;
		$this->render();
	}

	public function render()
	{
		extract($this->data);

		require $this->template;
	}
}
