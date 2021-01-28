<?php
namespace Core\File;

use Symfony\Component\Filesystem\Filesystem;

class FileException extends \Exception
{
    use FileTrait;
    
    public $name;

    public function __construct(string $message, string $file)
    {
        $this->name = $file;
        $this->message = '[FILE ERROR]  ' . $message;
        ( new Filesystem )->remove( $this->getTempdir() );
    }
}