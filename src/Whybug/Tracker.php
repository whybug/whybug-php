<?php
namespace Whybug;

use Whybug\Output\Console;
use Whybug\Output\Output;
use Whybug\Store\HttpStore;
use Whybug\Store\Store;

class Tracker
{
    protected $output;
    protected $config;
    protected $store;
    protected $existingErrorHandler;
    protected $existingExceptionHandler;

    public function __construct(array $config, Output $output, Store $store)
    {
        $this->config = $config;
        $this->output = $output;
        $this->store = $store;
    }

    public static function trackErrors(array $config = array())
    {
        $wtf = self::fromConfig($config);
        $wtf->register();

        return $wtf;
    }

    public static function fromConfig(array $config = array())
    {
        $output = new Console;
        $store = new HttpStore($config['endpoint'], $config['timeout'], $config['proxy']);

        return new self($config, $output, $store);
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

        $this->printSolutions(Error::fromError($code, $message, $file, $line));
    }

    public function handleException(\Exception $exception)
    {
        if (is_callable($this->existingExceptionHandler)) {
            call_user_func($this->existingErrorHandler, $exception);
        }

        $this->printSolutions(Error::fromException($exception));
    }

    public function handleFatalError()
    {
        if (null === $error = error_get_last()) {
            return;
        }

        $errors = E_ERROR | E_PARSE | E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_COMPILE_WARNING | E_STRICT;

        if ($error['type'] & $errors) {
            $this->handleException(new \ErrorException(
                $error['message'], $error['type'], $error['type'],
                $error['file'], $error['line']
            ));
        }
    }

    /**
     * @param Error $error
     *
     * @return Solution[]
     */
    protected function getSolutions(Error $error)
    {
        $solutions = $this->store->storeError($error);

        return $solutions->getSolutions();
    }

    /**
     * @param Error $error
     */
    protected function printSolutions(Error $error)
    {
        foreach ($this->getSolutions($error) as $solution) {
            $this->output->writeSolution($solution);
        }
    }
}

