<?php
/**
 * @author Wojciech Niewiadomski
 * @email wojtek@uniwizard.com
 */

namespace App\Services;

class FileException extends \Exception
{
    const MESSAGE_EMPTY = 'No file present';
    const MESSAGE_COMPLETED = 'File [%s] not exists';

    /**
     * @var string|null
     */
    private $fileName = null;

    /**
     * FileException constructor.
     * @param string $message
     * @param int $code
     * @param \Throwable|null $previous
     */
    public function __construct($message = "", $code = 0, \Throwable $previous = null)
    {
        if(empty($message)) {
            $message = static::MESSAGE_EMPTY;
        }

        parent::__construct($message, $code, $previous);
    }

    /**
     * @param string $fileName
     */
    public function setFileName(string $fileName)
    {
        $this->fileName = $fileName;
        $this->message = sprintf(static::MESSAGE_COMPLETED, $fileName);
    }

    /**
     * @return string|null
     */
    public function getFileName(): ?string
    {
        return $this->fileName;
    }
}
