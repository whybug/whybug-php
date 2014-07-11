<?php
namespace Adri\Wtf;


class ErrorLog implements \JsonSerializable
{
    const VERSION = '0.0.1';

    protected $programmingLanguage;
    protected $programmingLanguageVersion;
    protected $errorMessage;
    protected $errorCode;
    protected $errorLevel;
    protected $filePath;
    protected $line;

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
        $errorLog = new self();

        $errorLog->errorMessage = $message;
        $errorLog->errorCode = (string) $code;
        $errorLog->errorLevel = self::errorCodeToString($code);
        $errorLog->filePath = $file;
        $errorLog->line = $line;

        return $errorLog;
    }

    public static function fromException(\Exception $exception)
    {
        $errorLog = new self();

        $errorLog->errorMessage =  $exception->getMessage();
        $errorLog->errorCode =  (string) $exception->getCode();
        $errorLog->errorLevel =  'exception';
        $errorLog->filePath =  $exception->getFile();
        $errorLog->line =  $exception->getLine();

        return $errorLog;
    }

    public static function errorCodeToString($code)
    {
        switch ($code) {
            case 1:     return 'E_ERROR'; break;
            case 2:     return 'E_WARNING'; break;
            case 4:     return 'E_PARSE'; break;
            case 8:     return 'E_NOTICE'; break;
            case 16:    return 'E_CORE_ERROR'; break;
            case 32:    return 'E_CORE_WARNING'; break;
            case 64:    return 'E_COMPILE_ERROR'; break;
            case 128:   return 'E_COMPILE_WARNING'; break;
            case 256:   return 'E_USER_ERROR'; break;
            case 512:   return 'E_USER_WARNING'; break;
            case 1024:  return 'E_USER_NOTICE'; break;
            case 2048:  return 'E_STRICT'; break;
            case 4096:  return 'E_RECOVERABLE_ERROR'; break;
            case 8192:  return 'E_DEPRECATED'; break;
            case 16384: return 'E_USER_DEPRECATED'; break;
            case 30719: return 'E_ALL'; break;
            default:    return 'E_UNKNOWN'; break;
        }

    }


    /**
     * @inheritdoc
     * @return array
     */
    function jsonSerialize()
    {
        return get_object_vars($this);
    }

    public function __toString()
    {
       return "$this->errorMessage on line $this->line";
    }
}
