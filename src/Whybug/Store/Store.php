<?php
namespace Whybug\Store;

use Whybug\Error;
use Whybug\Solutions;

interface Store
{
    /**
     * @param Error $error
     * @return Solutions
     */
    public function storeError(Error $error);
}
