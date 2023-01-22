<?php

namespace App\Framework\Interfaces;

use Closure;

interface TestInterface
{
    public function __construct();
    public function run(Closure $callable) : bool;
    public function getResults() : array;
}
