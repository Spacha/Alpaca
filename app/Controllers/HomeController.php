<?php

namespace App\Controllers;

use App\Framework\Libs\{
	Controller,
	Request,
	View
};

use App\Models\Test;

class HomeController extends Controller
{
	const SLOGANS = [
		"It works on my machine.",
		"Have you tried turning it off and on again?",
		"It's a beautiful day, isn't it.",
		"Hello, stranger. Nice to meet you.",
		"I'm bad with slogans.",
		"To the Moon!"
	];

	public function home()
	{
		return new View('home.home', [
			'active' => 'home',
			'slogan' => self::SLOGANS[array_rand(self::SLOGANS)],
			'topSection' => true
		], ['header', 'footer']);
	}

	public function about()
	{
		return new View('about.about', ['active' => 'about'], ['header', 'footer']);
	}
}
