<?php
namespace Adri\Wtf;


class ErrorLog implements \JsonSerializable
{
    protected $language;
    protected $languageVersion;
    protected $errorMessage;
    protected $errorCode;
    protected $filePath;
    protected $line;

    public function __construct()
    {
        $this->language = 'php';
        $this->languageVersion = PHP_VERSION;

        // todo: url, framework, stacktrace?
        // $errorLog->url = $error['url'];
    }

    public static function fromError(array $error)
    {
        $errorLog = new self();

        $errorLog->errorMessage = $error['message'];
        $errorLog->errorCode = $error['code'];
        $errorLog->filePath = $error['file'];
        $errorLog->line = $error['line'];

        return $errorLog;
    }

    public static function fromException(\Exception $exception)
    {
        $errorLog = new self();

        $errorLog->errorMessage =  $exception->getMessage();
        $errorLog->errorCode =  $exception->getCode();
        $errorLog->filePath =  $exception->getFile();
        $errorLog->line =  $exception->getLine();

        return $errorLog;
    }

    /**
     * @inheritdoc
     * @return array
     */
    function jsonSerialize()
    {
        return (array) $this;
    }
}
