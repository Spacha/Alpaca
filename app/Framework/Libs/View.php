<?php

namespace App\Framework\Libs;

use App\Framework\Libs\Auth\Authenticator;

/**
 * @todo 	This class should be rethought from the first line to the last.
 * 			Templating engine, layouts...
 *			We shouldn't have to define snippets every time: ['header', 'footer']
 *			Instead, extract the keys from the .phtml files.
 */
class View
{
	protected $snippets = [];
	protected $template = '';
	protected $data = [];

	public function __construct($template, $data = [], $snippets = [])
	{
		$this->template = $this->templatePath($template);
		$this->data = $data + $this->middlewares();
		$this->setSnippets($snippets);

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
			extract($this->data);
			require app_path('views', '_snippets/'.$snippet.'.phtml');
			$result[$snippet] = ob_get_clean();
		}

		return $result;
	}

	protected function middlewares() : array
	{
		return [
			'auth' => new Authenticator()
		];
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
