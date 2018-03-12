<?php

namespace App\Framework\Libs;

class View
{
	protected $template = '';
	protected $data = [];

	public function __construct($template, $data = [])
	{
		$this->template = $this->templatePath($template);
		$this->data = $data;
		$this->render();
	}

	public function render()
	{
		extract($this->data);

		require $this->template;
	}

	protected function templatePath(string $template) : string
	{
		return app_path('views', str_replace('.', DIRECTORY_SEPARATOR, $template).'.phtml');
	}
}
