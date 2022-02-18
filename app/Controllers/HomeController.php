<?php

namespace App\Controllers;

use App\Framework\Libs\{
	Controller,
	Request,
	View
};

use App\Models\Blog;

class HomeController extends Controller
{
	const SNIPPETS = ['header', 'footer'];
	const SLOGANS = [
		"It works on my machine 🤷‍♂️",
		"Have you tried turning it <b>off</b> and <b>on</b> again? 🤨",
		"It's a beautiful day, isn't it 🌤",
		"Hello, stranger. Nice to meet you 👋",
		"I'm bad with slogans 🤦",
		"To the Moon 🚀",
		"A QA engineer went to a bar and ordered -1 beers. 🍺",
		"A QA engineer went to a bar and ordered <code>null</code> beers 🍺"
	];


	public function home()
	{
		$blogModel = new Blog();

		return new View('home.home', [
			'title' 		=> 'Spacha — A nerd with attitude',
			'active' 		=> 'home',
			'slogan' 		=> self::SLOGANS[array_rand(self::SLOGANS)],
			'recentPosts' 	=> $blogModel->titles(5),
			'topSection' 	=> true
		], self::SNIPPETS);
	}

	public function about()
	{
		return new View('about.about', ['active' => 'about'], self::SNIPPETS);
	}
}
