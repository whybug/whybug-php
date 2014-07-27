<?php

class Test
{
    private $name;

    public static function example() {
        $test = new self;
        // Provokes an error.
        $test->$name = 'example';

        return $test;
    }
}

$example = Test::example();
