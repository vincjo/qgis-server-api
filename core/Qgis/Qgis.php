<?php
namespace Core\Qgis;

class Qgis extends AbstractQgis
{
    private const NEW_PROJECT = PATH_TO_MAPS . 'template.project.qgs';
    protected $projectfile;

    public function __construct(string $projectfile)
    {
        $this->projectfile = $projectfile;
        $this->getSimpleXMLElement();
    }

    public static function createNewProject(string $file)
    {
		copy( self::NEW_PROJECT, $file );
		return new self($file);
    }
}