<?php

namespace App\Framework\Libs;

/*
NOTE: 	This will also replace code in code blocks etc. which can be very annoying!
		=> Skip ones with backslash and remove it, consider this example:
			"\@img{'ass'}" => "@img{'ass'}"
			"@img{'ass'}"  => "<img src=\"uploads/ass.png\" />"
Template syntax:
	@listRecentTitles{}
	@listRecentTitles{'latest', 5}
 */

use App\Framework\Interfaces\MdTemplateInterface;
class PostTitlesTemplate implements MdTemplateInterface
{
	const NAME = "postTitles";

	public function initialize($arg1 = null, $arg2 = null, $arg3 = null, $arg4 = null) : bool
	{
		// Set the class variables. Defines the 'public interface'
		if (!in_array($arg2, ['latest', 'oldest']) || $arg1 < 0)
			return false;

		$order = '';
		if (strtolower($arg2) == 'latest')
			$order = 'ASC';
		else if (strtolower($arg2) == 'oldest')
			$order = 'DESC';
		else
			return false;

		$this->order = $order;
		$this->num = $arg1;

		return true;
	}

	public function name() : string
	{
		return self::NAME;
	}

	public static function render() : string
	{
		$blog = new \App\Models\Blog();
		$titles = $blog->titles($this->num, $this->order);

		$list = "";
		foreach($titles as $title)
		{
			$output .= "<li>{$title->created_at}: {$title->title}</li>\n";
		}

		return "<ul>{$list}<ul>";
	}
}

/**
* This library handles markdown templating.
*/
class MdTemplater
{
	/**
	 * Initialize the class.
	 *
	 * @return void
	 */
	public function __construct()
	{
		// ...
	}

	/**
	 * Transform a string containing templating, with corresponding templates.
	 */
	public static function render(string $html) : string
	{
		return self::renderTemplates($html);
	}

	/**
	 * ...
	 */
	public static function renderTemplates(string $html) : string
	{
		$templates = [];

		// regex magic here...
		// replace templates:
		// foreach($templates as $template)
		// 	preg_replace($match, $template->render()));

		return $html;
	}
}
