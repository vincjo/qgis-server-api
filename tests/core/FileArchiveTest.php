<?php
use \Core\File\FileArchive;
use Symfony\Component\Filesystem\Filesystem;
use PHPUnit\Framework\TestCase;

class FileArchiveTest extends TestCase
{
    protected $zip;

    protected function setUp(): void
    {
        $tempdir = __DIR__ . '/templates/' . uniqid('TEMP_') . '/';
        ( new Filesystem() )->mkdir( $tempdir );
        $file = $tempdir . 'testing.zip';
        copy(__DIR__ . '/templates/' . 'test-zip.zip', $file);
        $this->zip = new FileArchive($file);
    }

    public function testExtract()
    {
        $scandir = [
            '.',
            '..',
            'departements.sqlite',
            'geojson',
            'shapes',
            'test-qgs.qgs',
        ];
        $this->zip->extract();
        $this->assertEquals( $scandir, scandir($this->zip->getContent()) );
        ( new Filesystem )->remove( $this->zip->getTempdir() );
    }

    public function testGetProjectfile()
    {
        $name = $this->zip->getTempdir() . 'test-qgs.qgs';
        $this->zip->extract()->getProjectfile();
        $this->assertEquals( $name, $this->zip->name );
        ( new Filesystem )->remove( $this->zip->getTempdir() );
    }
}