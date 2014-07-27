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

    public function writeError(Error $errorLog)
    {
        $this->write($errorLog);
    }

    public function writeSolution(Solution $solution)
    {
        $this->write($solution);
    }

}
