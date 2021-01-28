<?php
namespace Sigapp\Layers;

use \Sigapp\Layers\IO\{ Ogr, Excel };

class LayersIO
{
    public $id;

    public function __construct(int $id, string $format, string $filter, array $extent = [])
    {
        $this->id = $id;
        $this->format = $format;
        $this->filter = $filter;
        $this->extent = $extent;
    }

    public function extract()
    {
        switch ($this->format) {
            case 'xlsx': return $this->toExcel()->getOutput();
            case 'shp': 
            case 'gml': 
            case 'gpkg': 
            case 'kml': 
            case 'sqlite': 
            case 'geojson': return $this->toOgr()->getOutput();    
            default:
                throw new \Exception('Invalid file format : "'. $this->format . '". Accepted : "xlsx", "shp", "geojson", "gml", "gpkg", "kml", "sqlite".');            
        }
    }

    public function toExcel()
    {
        $excel = new Excel($this->id, $this->filter, $this->extent);
        $this->output = $excel->createSpreadsheet();
        return $this;
    }

    public function toOgr()
    {
        $ogr = new Ogr($this->id, $this->format, $this->filter, $this->extent);
        $this->output = $ogr->create();
        return $this;
    }

    public function getOutput()
    {
        $file = pathinfo($this->output, PATHINFO_BASENAME);
        return [
            'file' => API_URL . 'files/' . $file,
            'name' => $file
        ];
    }
}