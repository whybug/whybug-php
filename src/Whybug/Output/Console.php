<?php
namespace Whybug\Output;

use Whybug\Error;
use Whybug\Solution;

class Console implements Output
{

    public function write($string)
    {
        echo $string;
    }

    public function writeError(Error $error)
    {
        $this->write($error);
    }

    public function writeSolution(Solution $solution)
    {
        $this->write($solution);
    }

}
