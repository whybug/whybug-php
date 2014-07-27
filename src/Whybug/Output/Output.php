<?php
namespace Whybug\Output;

use Whybug\Error;
use Whybug\Solution;

interface Output
{
    public function writeError(Error $error);

    public function writeSolution(Solution $solution);
}
