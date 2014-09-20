<?php
namespace Whybug;

class Error
{
    const PROTOCOL_VERSION = '1.0';

    protected $programminglanguage;
    protected $programminglanguage_version;
    protected $message;
    protected $code;
    protected $level;
    protected $file_path;
    protected $line;
    protected $os;
    protected $os_version;

    public function __construct()
    {
        $this->protocol_version = self::PROTOCOL_VERSION;
        $this->programminglanguage = 'php';
        $this->programminglanguage_version = PHP_VERSION;
        $this->os = php_uname('s');
        $this->os_version = php_uname('r');
        // todo: url, framework, stacktrace?
    }

    public static function fromError($code, $message, $file, $line)
    {
        $error = new self();

        $error->message = $message;
        $error->code = (string) $code;
        $error->level = self::errorCodeToString($code);
        $error->file_path = $file;
        $error->line = $line;

        return $error;
    }

    public static function fromException(\Exception $exception)
    {
        $error = new self();

        $error->message =  $exception->getMessage();
        $error->code =  (string) $exception->getCode();
        $error->level =  'exception';
        $error->file_path =  $exception->getFile();
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
       return "$this->message on line $this->line";
    }
}
