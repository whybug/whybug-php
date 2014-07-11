<?php

namespace Adri\Wtf;


use Adri\Wtf\Output\Console;

class Wtf
{
    protected $output;
    protected $config;
    protected $existingErrorHandler;
    protected $existingExceptionHandler;

    public function __construct(array $config)
    {
        $this->config = $config;
        $this->output = new Console();
    }

    public static function init()
    {
        // When installed using composer, the user config is in the project root.
        $userConfig = __DIR__ . '/../../../../../wtf.config.ini';
        $config = file_exists($userConfig) ? $userConfig : __DIR__ . '/../../../config.default.ini';

        $wtf = new self(parse_ini_file($config));
        $wtf->register();

        return $wtf;
    }

    public function register()
    {
        $this->existingErrorHandler = set_error_handler(array($this, 'handleError'), E_ALL);
        $this->existingExceptionHandler = set_exception_handler(array($this, 'handleException'));
        register_shutdown_function(array($this, 'handleFatalError'));
    }

    public function unregister()
    {
        if (is_callable($this->existingErrorHandler)) {
            restore_error_handler();
        }

        if (is_callable($this->existingExceptionHandler)) {
            set_exception_handler($this->existingExceptionHandler);
        }
    }

    /**
     * @param $error
     */
    public function handleError($code, $message, $file = '', $line = 0, $context = array())
    {
        if (is_callable($this->existingErrorHandler)) {
            call_user_func($this->existingErrorHandler, $code, $message, $file, $line, $context);
        }

        $this->printSolutions(ErrorLog::fromError($code, $message, $file, $line));
    }

    public function handleException(\Exception $exception)
    {
        if (is_callable($this->existingExceptionHandler)) {
            call_user_func($this->existingErrorHandler, $exception);
        }

        $this->printSolutions(ErrorLog::fromException($exception));
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

    /**
     * @param ErrorLog $error
     * @return Solution[]
     */
    protected function getSolutions(ErrorLog $error)
    {
        $url = $this->config['wtf.url'];

        $context = stream_context_create(array(
            'http' => array(
                'method' => 'POST',
                'header' => "Content-type: application/json\r\n",
                'content' => json_encode($error),
                'proxy' => $this->config['wtf.proxy'],
                'timeout' => $this->config['wtf.timeout']
            )
        ));

        $response = file_get_contents($url, null, $context);

        if (!$response) {
            return array();
        }

        $error = json_decode($response, true);

        if (!empty($error['solutions'])) {
            return array_map('\Adri\Wtf\Solution::fromArray', $error['solutions']);
        }

        return array();
    }

    /**
     * @param Solution[] $solutions
     */
    protected function printSolutions(ErrorLog $errorLog)
    {
        foreach ($this->getSolutions($errorLog) as $solution) {
            $this->output->writeSolution($solution);
        }
    }
}

