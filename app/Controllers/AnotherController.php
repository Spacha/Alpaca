<?php

namespace App\Controllers;

use App\Framework\Libs\Controller;
use App\Framework\Libs\Request;

use App\Models\Another;

class AnotherController extends Controller
{
	public function home()
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

	public function test(...$data)
	{
		print_r($data);
	}

	public function pages($pageId = 0, $postId = 0, $commentId = 0)
	{
		echo "PageId: {$pageId}".PHP_EOL;
		echo "PostId: {$postId}".PHP_EOL;
		echo "CommentId: {$commentId}";
	}
}
