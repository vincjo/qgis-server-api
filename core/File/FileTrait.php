<?php
namespace Core\File;
use Symfony\Component\Filesystem\Filesystem;

trait FileTrait
{
    public $file;
    protected $tempdir;
    public $fs;

    protected function getFile(): string
    {
        return $this->file;
    }

    protected function setFile(string $file): void
    {
        $this->file = $file;
    }

    public function makeTempdir(): void
    {
        $this->tempdir = PATH_TO_MAPS . uniqid('TEMP_') . '/';
        $this->fs->mkdir( $this->tempdir );
    }

    public function getTempdir(): string
    {
        if ( !isset($this->tempdir) ) {
            $this->tempdir = pathinfo( $this->file, PATHINFO_DIRNAME ) . '/';
            return $this->tempdir;
        }
        return $this->tempdir;
    }

    protected function getFormat(): string
    {
        return pathinfo( $this->file, PATHINFO_EXTENSION );
    }

    protected function getFilename(): string
    {
        return pathinfo($this->file, PATHINFO_FILENAME);
    }    
}