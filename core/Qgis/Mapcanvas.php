<?php
namespace Core\Qgis;

class Mapcanvas extends AbstractMapcanvas
{
    protected $mapcanvas;

    public function __construct(\SimpleXMLElement $mapcanvas)
    {
        $this->mapcanvas = $mapcanvas;
    }
}