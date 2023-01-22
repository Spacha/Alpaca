<?php

namespace App\Framework\Interfaces;

interface MdTemplateInterface
{
    public function initialize($arg1 = null, $arg2 = null, $arg3 = null, $arg4 = null) : bool;

    public function name() : string;

    public static function render() : string;
}