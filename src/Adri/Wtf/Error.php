<?php
namespace Adri\Wtf;

/**
 * ErrorMessage
 * Stacktrace
 * ProgrammingLanguage
 *
 * Solution (description, upvotes, downvotes)
 */

class Error
{
    protected $language;
    protected $level;
    protected $message;
    protected $filePath;
    protected $line;
    protected $timestamp;
    protected $url;

    public static function fromLogLine(array $log)
    {
        $error = new self;
        $error->language = $log['language'];
    }
}

