<?php
namespace Sigapp\Layers\IO;

use Symfony\Component\Filesystem\Filesystem;

class OgrException extends \Exception
{
    public function __construct(string $message, Ogr $ogr)
    {
        $this->message = '[OGR ERROR]  ' . $message;
        ( new Filesystem )->remove( $ogr->tempdir );
    }
}