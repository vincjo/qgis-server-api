<?php
namespace Core\Qgis;

abstract class AbstractQgis
{
    protected $projectfile;
    private $qgis;

    public function getProjectfile()
    {
        return $this->projectfile;
    }

    protected function getSimpleXMLElement(): void
    {
        $this->qgis = simplexml_load_file( $this->projectfile );
    }
        
    /**
     * Save modifications in the projectfile
     */
    public function save(): void
    {
        $this->qgis->asXml( $this->projectfile );
    }
        
    /**
     * Return QgisModel data
     */
    public function get(): array
    {
        return [
            'file'      => $this->projectfile,
            'name'      => $this->getName(),
            'title'     => $this->getTitle(),
            'version'   => $this->getVersion()
        ];        
    }

    /**
     * Save data from QgisModel
     */
    public function set(array $data): void
    {
        $this->setTitle( $data['title']);
    }

    /**
     * Return projectfile version (ex: 3.4.15-Madeira)
     */
    public function getVersion(): string
    {
        return (string) $this->qgis['version'];
    }
    
    /**
     * Return project filename (ex: c:/projects/map.qgs => map)
     */
    public function getName(): string
    {
        return pathinfo( $this->projectfile, PATHINFO_FILENAME );
    }
    
    /**
     * Return project title from qgis->title node
     */
    public function getTitle(): string
    {
        return (string) $this->qgis->title;
    }
    
    /**
     * Add or replace the title of the project
     */
    public function setTitle(string $value): void
    {
        $this->qgis->title = $value;
        $this->save();
    }
    
    /**
     * Tells QGIS Server to use <layerid> instead of "shortname" which is not necessarily specified
     */
    public function useLayerIdAsName(): void
    {
        if ( empty($this->qgis->properties->WMSUseLayerIDs) ) {
            $this->qgis->properties->addChild('WMSUseLayerIDs');
            $this->qgis->properties->WMSUseLayerIDs->addAttribute('type', 'bool');
        }
        if ( empty($this->qgis->properties->WFSUseLayerIDs) ) {
            $this->qgis->properties->addChild('WFSUseLayerIDs');
            $this->qgis->properties->WFSUseLayerIDs->addAttribute('type', 'bool');
        }
        $this->qgis->properties->WMSUseLayerIDs = 'true';
        $this->qgis->properties->WFSUseLayerIDs = 'true';
        $this->save();
    }
    
    /**
     * Access to <mapcanvas> node
     */
    public function getMapcanvas(): Mapcanvas
    {
        foreach ( $this->qgis->mapcanvas as $mapcanvas ) {
           if ( (string) $mapcanvas->attributes()['name'] === 'theMapCanvas') {
                return new Mapcanvas($mapcanvas);
           }
        }
    }
    
    /**
     * Return Maplayer instances from <maplayer> nodes in array
     */
    public function getMaplayers(): array
    {
        foreach ( $this->qgis->projectlayers->maplayer as $maplayer ) {
            $maplayers[] = new Maplayer($maplayer, $this->get() );
        }
        $maplayers = isset($maplayers) ? $maplayers : [];
        return $maplayers;
    }
    
    public function getMaplayersByProviders(array $providers = ['postgres', 'spatialite']): array
    {
        foreach ($this->getMaplayers() as $maplayer) {
            if ( in_array($maplayer->getProvider(), $providers) ) {
                $maplayers[] = $maplayer;
            }
        }
        return isset($maplayers) ? $maplayers : [];
    }

    /**
     * Find specific layer in <maplayer> nodes and return LayerParser instance
     */
    public function getMaplayerByName(string $name): ?Maplayer
    {
        $maplayers = $this->getMaplayers();
        foreach ( $maplayers as $maplayer ) {
            $names[] = $maplayer->getName();
        }
        $key = array_search($name, $names);
        if ($key === false) {
            return null;
        }
        return $maplayers[$key];
    }
    
    /**
     * Remove a <maplayer> node by name
     */
    public function removeMaplayer(string $name): void
    {
        $maplayer = $this->getMaplayerByName($name)->getSimpleXMLElement();
        $dom = dom_import_simplexml($maplayer);
        $dom->parentNode->removeChild($dom);
        $this->save();
        $this->updateLayerTree();
    }
    
    /**
     * Add a <maplayer> node in the project
     */
    public function addMaplayer(\SimpleXmlElement $maplayer): void
    {
        $maplayer = dom_import_simplexml($maplayer);
        $projectlayers = dom_import_simplexml($this->qgis->projectlayers);
        $projectlayers->appendChild( $projectlayers->ownerDocument->importNode($maplayer, true) );
        $this->save();
        $this->updateLayerTree();
    }

     /**
     * Set <layer-tree-group> according to maplayers
     */   
    public function updateLayerTree(): void
    {
		$dom = dom_import_simplexml( $this->qgis->{'layer-tree-group'} );
		$dom->parentNode->removeChild($dom);
        $treeGroup = $this->qgis->addChild('layer-tree-group');
        foreach ( $this->getMaplayers() as $maplayer ) {
            $layerTree = $treeGroup->addChild('layer-tree-layer');
            $layerTree['id'] = $maplayer->getName();
            $layerTree['name'] = $maplayer->getTitle();
            $layerTree->addChild('customproperties');
        }
        $this->save();
    }
}