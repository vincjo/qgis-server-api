<?php
namespace Sigapp\Layers\IO;

use PhpZip\ZipFile;
use Symfony\Component\Filesystem\Filesystem;

class Zip
{
    public function __construct(string $dir, string $name = null)
    {
        $this->dir = $dir;
        $this->name = $name ? $name : pathinfo($dir, PATHINFO_FILENAME);
    }

    public function addFiles()
    {
        $zipFile = new ZipFile();
        $zipFile->addDirRecursive($this->dir);
        $output = PATH_TO_FILES . $this->name . '.zip';
        $zipFile->saveAsFile($output);
        return $output;
    }
}