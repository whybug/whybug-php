<?php
namespace Adri\Wtf\Output;

use Adri\Wtf\ErrorLog;
use Adri\Wtf\Solution;

class Html {

    public function write($string)
    {
       echo $string;
    }

    public function writeError(ErrorLog $errorLog)
    {
        $this->write($errorLog);
    }

    public function writeSolution(Solution $solution)
    {
        $this->write($solution);
    }
}
