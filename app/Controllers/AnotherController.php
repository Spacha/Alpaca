<?php

namespace App\Controllers;

use App\Framework\Libs\Controller;

use App\Models\Another;

class AnotherController extends Controller
{
	public function home($data)
	{
		$pageId = data($data, 'pageId');

		if (!isset($pageId)) {
			echo "<p>Not found...</p>";
			return;
		}

		$items = Another::items($pageId);

		echo "<hr>";

		array_map(function($item) {
			echo $item.'<br>';
		}, $items);

		echo "<p><a href='/'>Go to home</a></p>";
	}
}
