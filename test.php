<?php

require_once 'vendor/autoload.php';

\Adri\Wtf\Wtf::register();

class Test
{
    private $name;

    public static function example() {
        $test = new self;
        $test->$name = 'example';

        return $test;
    }
}

$example = Test::example();
