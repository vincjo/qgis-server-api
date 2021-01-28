<?php
namespace Core\Qgis;


class Maplayer extends AbstractMaplayer
{
    protected $maplayer;
    public $project;

    public function __construct(\SimpleXMLElement $maplayer, array $project)
    {
        $this->maplayer = $maplayer;
        $this->project = $project;
    }

    public function get(): array
    {
        return [
            'name'          => $this->getName(),
            'title'         => $this->getTitle(),
            'abstract'      => $this->getAbstract(),
            'source'        => $this->getSource(),
            'map'           => $this->project['file'],
            'project_name'  => $this->project['name'],
            'tablename'     => $this->getTablename(),
            'displayfield'  => $this->getDisplayfield(),
            'geomcolumn'    => $this->getGeomcolumn(),
            'geomtype'      => $this->getGeometryType(),
            'srid'          => $this->getSrid(),
            'extent'        => $this->getExtent(),
            'sql'           => $this->getSql(),
            'columns'       => $this->getColumns(),
            'datasource'    => $this->getDatasource(),
            'attributions'  => $this->getSource(),
            'service'       => $this->getService(),
            'url'           => $this->getUrl(),
            'layer'         => $this->getLayer(),
            'format'        => $this->getFormat(),
            'style'         => $this->getStyle(),
        ];
    }

    public function set(array $data): void
    {
        $this->setTitle( $data['title'] );
        $this->setAbstract( $data['abstract'] );
        $this->setSource( $data['source'] );
    }
}