<?php
namespace Whybug\Store;

use Whybug\Solutions;
use Whybug\Error;

class HttpStore implements Store
{
    protected $timeout;
    protected $proxy;
    protected $endpoint;

    public function __construct($endpoint, $timeout = 1, $proxy = '')
    {
        $this->endpoint = $endpoint;
        $this->timeout = $timeout;
        $this->proxy = $proxy;
    }

    /**
     * @param Error $error
     *
     * @return Solutions
     */
    public function storeError(Error $error)
    {
        $context = stream_context_create(array(
            'http' => array(
                'method' => 'POST',
                'header' => "Content-type: application/json\r\n",
                'content' => json_encode($error->toArray()),
                'timeout' => $this->timeout,
                'proxy' => $this->proxy,
            )
        ));

        $response = file_get_contents($this->endpoint, false, $context);

        return Solutions::fromResponse($response);
    }
}
