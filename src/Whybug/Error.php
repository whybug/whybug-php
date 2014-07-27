<?php
namespace Whybug;

class Error
{
    const VERSION = '0.0.1';

    protected $programmingLanguage;
    protected $programmingLanguageVersion;
    protected $errorMessage;
    protected $errorCode;
    protected $errorLevel;
    protected $filePath;
    protected $line;
    protected $version;

    public function __construct()
    {
        $this->version = self::VERSION;
        $this->programmingLanguage = 'php';
        $this->programmingLanguageVersion = PHP_VERSION;

        // todo: url, framework, stacktrace?
        // $errorLog->url = $error['url'];
    }

    public static function fromError($code, $message, $file, $line)
    {
        $error = new self();

        $error->errorMessage = $message;
        $error->errorCode = (string) $code;
        $error->errorLevel = self::errorCodeToString($code);
        $error->filePath = $file;
        $error->line = $line;

        return $error;
    }

    public static function fromException(\Exception $exception)
    {
        $error = new self();

        $error->errorMessage =  $exception->getMessage();
        $error->errorCode =  (string) $exception->getCode();
        $error->errorLevel =  'exception';
        $error->filePath =  $exception->getFile();
        $error->line =  $exception->getLine();

        return $error;
    }

    public static function errorCodeToString($code)
    {
        switch ($code) {
            case 1:     return 'E_ERROR';
            case 2:     return 'E_WARNING';
            case 4:     return 'E_PARSE';
            case 8:     return 'E_NOTICE';
            case 16:    return 'E_CORE_ERROR';
            case 32:    return 'E_CORE_WARNING';
            case 64:    return 'E_COMPILE_ERROR';
            case 128:   return 'E_COMPILE_WARNING';
            case 256:   return 'E_USER_ERROR';
            case 512:   return 'E_USER_WARNING';
            case 1024:  return 'E_USER_NOTICE';
            case 2048:  return 'E_STRICT';
            case 4096:  return 'E_RECOVERABLE_ERROR';
            case 8192:  return 'E_DEPRECATED';
            case 16384: return 'E_USER_DEPRECATED';
            case 30719: return 'E_ALL';
            default:    return 'E_UNKNOWN';
        }
    }

    /**
     * @inheritdoc
     * @return array
     */
    function toArray()
    {
        return get_object_vars($this);
    }

    public function __toString()
    {
       return "$this->errorMessage on line $this->line";
    }
}
