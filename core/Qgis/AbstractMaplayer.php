<?php
namespace Core\Qgis;

use Core\Qgis\Maplayer\{Datasource, Extent, Columns, Qml};

abstract class AbstractMaplayer
{
    protected $maplayer;
    public $project;

    /**
     * Return maplayer xml node
     */
    public function getSimpleXMLElement(): \SimpleXMLElement
    {
        return $this->maplayer;
    }

    /**
     * Return <layerid> value
     */
    public function getName(): string
    {
        return (string) $this->maplayer->id;
    }

    /**
     * Return layer name from legend
     */
    public function getTitle(): string
    {
        return (string) $this->maplayer->layername;
    }

    /**
     * Set a tilte in projectfile
     */
    public function setTitle(string $value): void
    {
        $qgis = new Qgis( $this->project['file'] );
        $maplayer = $qgis->getMaplayerByName( $this->getName() )->getSimpleXMLElement();
        $maplayer->layername = $value;
        $qgis->save();
    }

    /**
     * Return abstract from qgis-server settings
     */
    public function getAbstract(): ?string
    {
        if ( isset($this->maplayer->abstract) ) {
            return (string) $this->maplayer->abstract;
        }
        return null;
    }

    /**
     * Set abstract in projectfile
     */
    public function setAbstract($value): void
    {
        $qgis = new Qgis( $this->project['file'] );
        $maplayer = $qgis->getMaplayerByName( $this->getName() )->getSimpleXMLElement();
        if ( !isset($maplayer->abstract) ) {
            $maplayer->addChild('abstract');
        }
        $maplayer->abstract = (string) $value;
        $qgis->save();
    }

    /**
     * Return <attribution> from qgis-server settings
     */
    public function getSource(): ?string
    {
        if ( isset($this->maplayer->attribution) ) {
            return (string) $this->maplayer->attribution;
        }
        return null;
    }

    /**
     * Set source in <attribution> from qgis-server settings
     */
    public function setSource($value): void
    {
        $qgis = new Qgis( $this->project['file'] );
        $maplayer = $qgis->getMaplayerByName( $this->getName() )->getSimpleXMLElement();
        if ( !isset($maplayer->attribution) ) {
            $maplayer->addChild('attribution');
        }
        $maplayer->attribution = $value;
        $qgis->save();
    }

    /**
     * Return 'ogr' (shapefile, geojson etc.), 'postgres', 'spatialite', 'oracle'...
     */
    public function getProvider(): string
    {
        return (string) $this->maplayer->provider[0];
    }

    /**
     * Return the name of the table from <datasource>
     */
    public function getTablename(): ?string
    {
        $datasource = ( new Datasource( $this->getSimpleXMLElement() ) )->get();
        return isset( $datasource['tablename'] ) ? $datasource['tablename'] : null;
    }

    /**
     * Return the name of the geom column from <datasource>
     */
    public function getGeomcolumn(): ?string
    {
        $datasource = ( new Datasource( $this->getSimpleXMLElement() ) )->get();
        return isset( $datasource['geomcolumn'] ) ? $datasource['geomcolumn'] : null;
    }

    /**
     * Return table unique attribute used for selections
     */
    public function getDisplayfield(): ?string
    {
		return (string) $this->maplayer->previewExpression;
    }

    /**
     * Set displayfield in <previewExpression> (displayfield node has disapeared since qgis 3)
     */
    public function setDisplayfield(string $value): void
    {
        $qgis = new Qgis( $this->project['file'] );
        $maplayer = $qgis->getMaplayerByName( $this->getName() )->getSimpleXMLElement();
        $maplayer->previewExpression = $value;
        $qgis->save();
    }

    /**
     * Paliative function to normalize displayfield value in <previewExpression>
     */
    public function setDisplayfieldFromColumnsDefinition(): void
    {
		foreach ($this->maplayer->constraints->constraint as $constraint) {
            $attributeName = (string) $constraint['field'];
			if ( (string) $constraint['unique_strength'] === 1 ) {
                $this->setDisplayfield($attributeName);
            }
        }
        $this->setDisplayfield($attributeName);
    }

    /**
     * Transform <datasource> node in array of key => value
     */
    public function getDatasource(): array
    {
        return ( new Datasource( $this->getSimpleXMLElement() ) )->get();
    }

    /**
     * Set <datasource> from \Sigapp\Datasouces\DatasourcesModel values
     */
    public function setDatasource(array $datasource, string $sql = '', string $geomcolumn = 'geom'): void
    {
        $datasource['sql'] = $sql;
        $datasource['geomcolumn'] = $geomcolumn;
        $qgis = new Qgis( $this->project['file'] );
        $maplayer = $qgis->getMaplayerByName( $this->getName() )->getSimpleXMLElement();
        ( new Datasource($maplayer) )->set($datasource);
        $qgis->save();
    }

    /**
     * Return <srid> value
     */
    public function getSrid(): int
    {
        return (int) $this->maplayer->srs->spatialrefsys->srid[0];
    }

    /**
     * Return bounding box with EPSG:3857
     */
    public function getExtent(): array
    {
        return ( new Extent( $this->getSimpleXMLElement() ) )->get();
    }

    /**
     * Set extent in projectfile
     */
    public function setExtent(array $extent): void
    {
        $qgis = new Qgis( $this->project['file'] );
        $maplayer = $qgis->getMaplayerByName( $this->getName() )->getSimpleXMLElement();
        ( new Extent( $maplayer ) )->set($extent);
        $qgis->save();
    }

    /**
     * Return sql filter from <datasource>
     */
    public function getSql(): string
    {
        $datasource = ( new Datasource( $this->getSimpleXMLElement() ) )->get();
        return isset( $datasource['sql'] ) ? $datasource['sql'] : '';
    }

    /**
     * Set sql filter in <datasource>
     */
    public function setSql(string $sql): void
    {
        $qgis = new Qgis( $this->project['file'] );
        $maplayer = $qgis->getMaplayerByName( $this->getName() )->getSimpleXMLElement();
        $datasource = ( new Datasource($maplayer) )->get();
        $datasource['sql'] = $sql;
        ( new Datasource( $this->getSimpleXMLElement() ) )->set($datasource);        
        $qgis->save();
    }

    /**
     * Return opacity value from <layerOpacity>
     */
    public function getOpacity(): float
    {
        return (float) $this->maplayer->layerOpacity;
    }

    /**
     * Set opacity value in projectfile
     */
    public function setOpacity(float $value): void
    {
        $qgis = new Qgis( $this->project['file'] );
        $maplayer = $qgis->getMaplayerByName( $this->getName() )->getSimpleXMLElement();
        $maplayer->layerOpacity = $value;
        $qgis->save();
    }

    /**
     * Return columns as it is stored in Sigapp DB
     */
    public function getColumns(): array
    {
        if ($this->getProvider() === 'wms') {
            return [];
        }
        return ( new Columns( $this->getSimpleXMLElement() ) )->get();
    }

    /**
     * Set columns from \Sigapp\Layers\LayersModel->columns value
     */
    public function setColumns(array $columns): void
    {
        $qgis = new Qgis( $this->project['file'] );
        $maplayer = $qgis->getMaplayerByName( $this->getName() )->getSimpleXMLElement();
        ( new Columns( $maplayer ) )->set($columns);
        $qgis->save();
    }

    /**
     * Return type of service for basemaps only
     */
    public function getService(): ?string
    {
        if ($this->getProvider() === 'wms') {
            return isset( $this->getDatasource()['type'] ) ? 'TMS' : 'WMTS';
        }
        return null;
    }

    /**
     * Return url of service for basemaps (TMS & WMTS) only
     */   
    public function getUrl(): ?string
    {
        if ($this->getProvider() === 'wms') {
            return $this->getDatasource()['url'];
        }
        return null;
    }

    /**
     * Return the layer name for WMTS only
     */   
    public function getLayer(): ?string
    {
        if ($this->getProvider() === 'wms') {
            $datasource = $this->getDatasource();
            return isset($datasource['layers']) ? $datasource['layers'] : null;
        }
        return null;
    }

    /**
     * Return tile format for WMTS only
     */   
    public function getFormat(): ?string
    {
        if ($this->getProvider() === 'wms') {
            $datasource = $this->getDatasource();
            return isset($datasource['format']) ? $datasource['format'] : null;
        }
        return null;
    }

    /**
     * Return style value for WMTS only
     */   
    public function getStyle(): ?string
    {
        if ($this->getProvider() === 'wms') {
            $datasource = $this->getDatasource();
            return isset($datasource['style']) ? $datasource['style'] : null;
        }
        return null;
    }

    /**
     * Return 'Polygon', 'Point', 'MultiLinestring' etc.
     */
    public function getGeometryType(): string
    {
        return (string) $this->maplayer['geometry'];
    }

    /**
     * Set geometry type in projectfile
     */
    public function setGeometryType(string $value): void
    {
        $qgis = new Qgis( $this->project['file'] );
        $maplayer = $qgis->getMaplayerByName( $this->getName() )->getSimpleXMLElement();
        $maplayer['geometry'] = $value;
        $qgis->save();
    }

    /**
     * Extract layer style and put it in a QML file
     */
    public function saveAsQml(string $file): string
    {
        $qml = new Qml;
        $qml->copyLayerStyle( $this->getSimpleXMLElement() );
        return $qml->save($file);
    }    
}