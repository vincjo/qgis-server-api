<?php
namespace Core\File;

use PhpZip\ZipFile;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class FileArchive
{
    use FileTrait;

    public $file;
    protected $tempdir;
    private $content;

    public function __construct(string $file)
    {
        $this->file = $file;
        $this->tempdir = $this->getTempdir();
        $this->content = $this->tempdir . uniqid() . '/';
        $this->fs = new Filesystem;
    }

    public function extract(): FileArchive
    {
        $this->fs->mkdir( $this->content );
        $zipFile = new ZipFile();
        try {
            $zipFile
                ->openFile( $this->file ) 
                ->extractTo( $this->content )
                ->close();
        } catch(\Exception $e) {
            $zipFile->close();
            $error = 'Archive seems corrupted. ' . $e->getMessage() . ' - ' . $this->file;
            throw new FileException($error, $this->file);
        } 
        // $this->fs->remove( $this->file );
        return $this;
    }

    public function getProjectfile(): FileArchive
    {
        $this->find();
        if ( $this->getFormat() === 'qgz' ) {
            return ( new self($this->file) )->extract()->getProjectfile();
        }
        return $this;
    }

    protected function find(): void
    {
        $finder = new Finder();
        $finder->in( $this->content );
        $finder->files()->name(['*.qgz', '*.qgs']);
        foreach ($finder as $file) {
            $project[] = $file->getRealPath();
        }
        if ( !isset($project) ) {
            throw new FileException('No project found in the archive', $this->file);
        }
        $this->setFile( $this->tempdir . pathinfo($project[0], PATHINFO_BASENAME) );
        $this->fs->rename( $project[0], $this->file );
    }

    public function getContent(): string
    {
        return $this->content;
    }
}