<?php

namespace App\Controllers;

use App\Framework\Libs\{
	Controller,
	Request,
	View
};

use App\Models\Blog;

class BlogController extends Controller
{
	public function __construct()
	{
		parent::__construct(new Blog());
	}

	public function list()
	{
		//$posts = $this->model->list('id', 'title', ['content', 'SELECT LEFT', 50]);
		$posts = $this->model->list();

		return new View('blog.home', [
			'active' 	=> 'blog',
			'posts' 	=> $posts
		], ['header', 'footer']);
	}
	public function create()
	{
		return new View('blog.create', [], ['header', 'footer']);
	}
}
