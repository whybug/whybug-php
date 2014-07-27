<?php
namespace Whybug\Output;

use Whybug\Error;
use Whybug\Solution;

interface Output
{
    public function writeError(Error $errorLog);

    public function writeSolution(Solution $solution);
}
