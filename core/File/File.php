<?php
namespace Core\File;

use Symfony\Component\Filesystem\Filesystem;

class File
{
    use FileTrait;
    
    public $file;
    protected $tempdir;
    public $fs; // Filesystem instance
    public $errors;

    public function __construct(string $file = null)
    {
        $this->file = $file;
        $this->fs = new Filesystem;
        $this->errors = [];
        $this->makeTempdir();
        if ($this->file) {
            $this->moveToTempdir();
        }
    }

    public function moveUploadedFile(\Slim\Http\UploadedFile $uploadedFile): File
    {
        $this->clientFilename = $uploadedFile->getClientFilename();
        $this->setFile( $this->tempdir . $this->clientFilename );
        $uploadedFile->moveTo( $this->file );
        return $this;
    }

    public function moveToTempdir(): File
    {
        $this->clientFilename = $this->getFilename() . '.' . $this->getFormat();
        $file = $this->tempdir . $this->clientFilename;
        $this->fs->rename( $this->file, $file );
        $this->setFile($file);
        return $this;
    }

    public function getProjectfile(): File
    {
        switch ( $this->getFormat() ) {
            case 'zip': 
                $this->extract()->normalize()->storeLocalDatasets();
                break;
            case 'qgz': 
                $this->extract()->normalize();
                break;
            case 'qgs': 
                $this->normalize();
                break;        
            default:
                throw new FileException('Invalid file format : '. $this->ext, $this->name);
        }
        return $this->rename();
    }

    private function normalize(): File
    {
        $normalizer = new FileNormalizer($this->file);
        $normalizer->normalize();
        $this->setFile( $normalizer->file );
        return $this;
    }

    private function extract(): File
    {
        $archive = new FileArchive($this->file);
        $archive->extract()->getProjectfile();
        $this->setFile( $archive->file );
        return $this;
    }

    private function storeLocalDatasets(): File
    {
        $storage = new FileDatastore($this->file);
        $storage->dump();
        $this->errors = $storage->errors;
        return $this;
    }

    private function rename(): File
    {
        $file = PATH_TO_MAPS . $this->getFilename() . '.' . $this->getFormat();
        $this->fs->rename( $this->file, $file );
        $this->fs->remove( $this->getTempdir() );
        $this->setFile($file);
        return $this;
    }
}