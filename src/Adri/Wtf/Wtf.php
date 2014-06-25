<?php

namespace Adri\Wtf;

use Adri\Wtf\Output\Terminal;

class Wtf
{
    protected $output;
    protected $config;
    protected $existingErrorHandler;
    protected $existingExceptionHandler;

    public function __construct(array $config)
    {
        $this->config = $config;
        $this->output = new Terminal;
    }

    public static function register()
    {
        // When installed using composer, the user config is in the project root.
        $userConfig = __DIR__ . '/../../../../../wtf.config.ini';
        $config = file_exists($userConfig) ? $userConfig : __DIR__ . '/../../../config.default.ini';

        $wtf = new self(parse_ini_file($config));
        $wtf->existingErrorHandler = set_error_handler(array($wtf, 'handleError'), E_ALL);
        $wtf->existingExceptionHandler = set_exception_handler(array($wtf, 'handleException'));
        register_shutdown_function(array($wtf, 'handleFatalError'));

        return $wtf;
    }

    public function unregister()
    {
        if (is_callable($this->existingErrorHandler)) {
            set_error_handler($this->existingErrorHandler, error_reporting());
        }

        if (is_callable($this->existingExceptionHandler)) {
            set_exception_handler($this->existingExceptionHandler);
        }
    }

    /**
     * @param $error
     */
    public function handleError($code, $message, $file = '', $line = 0, $context=array())
    {
        if (is_callable($this->existingErrorHandler)) {
            call_user_func($this->existingErrorHandler, $code, $message, $file, $line, $context);
        }

        $error = compact('code', 'message', 'file', 'line', 'context');
        $this->printSolutions($this->getSolutions(ErrorLog::fromError($error)));
    }

    public function handleFatalError()
    {
        if (null === $error = error_get_last()) {
            return;
        }

        $errors = E_ERROR | E_PARSE | E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_COMPILE_WARNING | E_STRICT;

        if ($error['type'] & $errors) {
            $this->handleException(new \ErrorException(
                @$error['message'], @$error['type'], @$error['type'],
                @$error['file'], @$error['line']
            ));
        }
    }

    public function handleException(\Exception $exception)
    {
        $this->printSolutions($this->getSolutions(ErrorLog::fromException($exception)));
    }

    /**
     * @param ErrorLog $error
     * @return Solution[]
     */
    protected function getSolutions(ErrorLog $error)
    {
        $url = $this->config['wtf.url'];

        $context = stream_context_create(array(
            'http' => array(
                'header' => 'Content-type: application/json',
                'content' => json_encode($error),
                'proxy' => $this->config['wtf.proxy']
            )
        ));

        $response = @file_get_contents($url, null, $context);

        if (!$response) {
            return array();
        }

        return array_map('\Adri\Wtf\Solution::fromArray', json_decode($response, true));
    }

    /**
     * @param Solution[] $solutions
     */
    protected function printSolutions(array $solutions)
    {
        foreach ($solutions as $solution) {
            $this->output->write($solution->getDescription());
        }
    }
}

