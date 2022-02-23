<?php

namespace App\Framework\Libs;

use Michelf\MarkdownExtra as MdParser;
use App\Framework\Libs\MdTemplater;

/**
* This library handles markdown compilation and caching.
* @todo Add a middle layer to create custom components
*       such as infoboxes or even dynamic content on in
*       the markdown. One thing would be the recent titles.
*/
class Markdown extends MdParser
{
	const HIGHLIGHT_KEYS = [
		'php',
		'html',
		'css',
		'c',
		'cpp',
		'http',
		'javascript', 'js',
		'php',
		'python',
		'bash', 'shell',
		'makefile',
		'markdown', 'md',
		'markup-templating',
		'json', 'webmanifest',
		'sql'
	];

	/**
	 * Initialize the class.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Transform a markdown string into html.
	 */
	public static function toHtml(string $markdown) : string
	{
		return self::render($markdown);
	}

	/**
	 * Transform a markdown string into html.
	 */
	protected static function render(string $markdown) : string
	{
		$html = self::defaultTransform($markdown);
		$html = self::postProcess($html);

		// post-processing
		return MdTemplater::render($html);
	}

	/**
	 * Post-process the html.
	 */
	protected static function postProcess(string $html) : string
	{
		// add 'language-x' class for each code span of form:
		// '{x}`program_code_here`' for syntax highlighting
		$html = self::classifyInlineCode($html);

		// add 'language-x' class for each code span and block of form:
		// <code class="x">program_code_here</code>' for syntax highlighting
		$html = self::addLanguagePrefix($html);

		// add a 'language-plain' class to each non-formatted block anf form
		$html = self::addPlainLanguagePrefix($html);

		return $html;
	}

	/**
	 * Finds inline code spans that have a language set using following syntax:
	 *   {php}`<?=$var;?>`
	 * which is, after Markdown compilation:
	 *   {php}<code>`<?=$var;?>`</code>,
	 * PHP is recognized as type and the hint is replaced by a class:
	 *   <code class="language-php">`<?=$var;?>`</code>
	 */
	private static function classifyInlineCode(string $html) : string
	{
		$pattern = "/\{([" .self::matchCodes(). "]+)\}<code>/i"; // i = insensitive
		if (preg_match_all($pattern, $html, $matches) == 0)
			return $html;

		// replace all matches with groups
		// e.g. $found = "{php}<code>", $code = "php"
		foreach ($matches[0] as $k => $match)
		{
			// replace the occurrences with classes
			$found = $matches[0][$k];
			$code = $matches[1][$k];
			$html = str_replace($found, "<code class=\"language-{$code}\">", $html);
		}

		return $html;
	}

	/**
	 * Adds a prefix to code class names. Prism requires
	 * code spans' and blocks' classes to be of format:
	 *   <code class="language-x">...</code>,
	 * where x is the highlight language. This method
	 * transforms simpler format x --> language-x.
	 */
	private static function addLanguagePrefix(string $html) : string
	{
		// match example: <code class="c"> => <code class="language-c">
		$pattern =  "/\<[code|pre]+? class\=\"([". self::matchCodes() ."]+)\"\>/i";
		
		if (preg_match_all($pattern, $html, $matches) == 0)
			return $html;

		// replace all matches with groups
		// e.g. $found = "<code class=\"php\">", $code = "php"
		foreach ($matches[0] as $k => $match)
		{
			// replace the occurrences with classes (add 'language' prefix)
			$found = $matches[0][$k];
			$code = $matches[1][$k];
			$html = str_replace($found, "<code class=\"language-{$code}\">", $html);
		}

		return $html;
	}

	/**
	 * If at this point there are plain <code> or <pre> parts, add them
	 * a generic formatting class 'language-plain' which adds basic
	 * formatting to them to make them consistent
	 * with the syntax-highlighted parts.
	 */
	private static function addPlainLanguagePrefix($html) : string
	{
		$html = str_replace("<code>", "<code class=\"language-plain\">", $html);
		$html = str_replace("<pre>", "<pre class=\"language-plain\">", $html);

		return $html;
	}

	private static function matchCodes() : string
	{
		return implode('|', self::HIGHLIGHT_KEYS);
	}
}
