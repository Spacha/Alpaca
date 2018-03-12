<?php

namespace App\Framework\Libs;

class View
{
	protected $snippets = [];
	protected $template = '';
	protected $data = [];

	public function __construct($template, $data = [], $snippets = [])
	{
		$this->template = $this->templatePath($template);
		$this->setSnippets($snippets);
		$this->data = $data;

		$this->render();
	}

	public function render()
	{
		extract($this->data);

		$snippets = $this->snippets;

		require $this->template;
	}

	public function setSnippets(array $snippets)
	{
		$this->snippets += $this->getSnippets($snippets);
	}

	protected function getSnippets(array $snippets) : array
	{
		$result = [];

		foreach($snippets as $snippet) {
			ob_start();
			require app_path('views', '_snippets/'.$snippet.'.phtml');
			$result[$snippet] = ob_get_clean();
		}

		return $result;
	}

	protected function layoutPath(string $layout) : string
	{
		return app_path('views', "_layouts/{$layout}.phtml");
	}

	protected function templatePath(string $template) : string
	{
		return app_path('views', str_replace('.', DIRECTORY_SEPARATOR, $template).'.phtml');
	}
}
