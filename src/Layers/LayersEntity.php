<?php
namespace Sigapp\Layers;

use \Sigapp\Layers\IO\GeoJson;
use \Sigapp\Layers\Providers\{ Postgis, Spatialite, Oracle };
use \Sigapp\Layers\Images\{ Preview, Symbol };
use \Sigapp\Maps\MapsLayersModel;
use \Core\Qgis\Qgis;
use \Core\Datastore\Datastore;
use Symfony\Component\Filesystem\Filesystem;

class LayersEntity implements LayersEntityInterface
{
	public $layer;

    public function __construct(int $id)
    {
        $this->layer = LayersModel::with('datasource')->where('id', $id)->first();
    }

    public function update(array $data)
    {
		$this->layer->update($data);
        $this->getMaplayer()->set( $data );
    }

	public function getDatatable(string $filter = '')
	{
		return $this->getProvider()->getDatatable($filter);
	}
	
	public function getDatatableFromExtent(array $extent, string $filter = '')
	{
		return $this->getProvider()->getDatatableFromExtent($extent, $filter);
    }

	public function getExtent($filter = '')
	{
		return $this->getProvider()->getExtent($filter);
	}
	
	public function setExtent($filter = '')
	{
		$extent = $this->getExtent( $filter );
        $this->getMaplayer()->setExtent( $extent );
        $this->layer->extent = $extent;
		$this->layer->save();
		return true;
	}
    
	public function getFeatureInfo($identifier)
	{
		$data = $this->getProvider()->getFeatureInfo($identifier);
		return ( new GeoJson($data) )->toGeoJson();
    }
    
	public function getFeatureInfoFromCoordinates(float $x, float $y, float $zoom, string $filter = '')
	{
		$data = $this->getProvider()->getFeatureInfoFromCoordinates($x, $y, $zoom, $filter);
		if ($data) {
			return ( new GeoJson($data) )->toGeoJson();
		}
		return false;
	}

	public function getEntityExtent(string $identifier)
	{
		return $this->getProvider()->getEntityExtent($identifier);
    }

	public function getDistinctValues(string $attribute)
	{
		return $this->getProvider()->getDistinctValues($attribute);
	}

	protected function getProvider()
	{
        $db = $this->layer->datasource;
		if ($db->provider === 'spatialite') {
			return new Spatialite($this->layer);
		} 
		elseif ($db->provider === 'postgres') {
			return new Postgis($this->layer);
		} 
		elseif ($db->provider === 'oracle') {
			return new Oracle($this->layer);
		} 
		else {
			return false;
		}
    }

	public function updateDatatableQuery() 
	{
		foreach($this->layer->columns as $column){
			if ( !$column['excluded'] && strlen($column['name']) > 0 ) {
				$str[] = '"' . $column['name'] . '" AS "' . $column['alias'] . '"';
			}
		}
		$this->layer->datatable = implode(', ', $str);
		$this->layer->save();
	}
	
	public function updateColumns(array $data)
	{
		$this->layer->columns = $data;
		$this->layer->save();
		$this->getMaplayer()->setColumns($data);
        $this->updateDatatableQuery();
	}

	public function updatePreviews()
	{
        return [
            'preview_url' => ( new Preview($this) )->create(),
            'symbol_url' => ( new Symbol($this) )->create(),
        ];
	}

    public function moveToProjectfile(string $project_name): void
    {
        $projectfile = PATH_TO_MAPS . $project_name . '.qgs';
		$origin = $this->getQgis();
        $destination = new Qgis($projectfile);
        $destination->addMaplayer( $origin->getMaplayerByName( $this->layer->name )->getSimpleXMLElement() );
		$origin->removeMaplayer( $this->layer->name );
        $this->layer->map = $projectfile;
        $this->layer->project_name = $project_name;
        $this->layer->save();
	}
	
    public function replaceBy(LayersModel $layer)
    {
		$layer = $layer->toArray();
        $origin = new Qgis($layer['map']);
        $sxe = $origin->getMaplayerByName($layer['name'])->getSimpleXMLElement();
		$destination = $this->getQgis();
        $destination->addMaplayer( $sxe );
        $destination->removeMaplayer( $this->layer['name'] );
		$layer['id'] = $this->layer['id']; 
		$layer['project_name'] = $this->layer['project_name']; 
		$layer['map'] = $this->layer['map'];
		$layer['folder_id'] = $this->layer['folder_id'];
        $this->layer->update( $layer );
    }

    public function delete()
    {
        LayersModel::find( $this->layer['id'] )->delete();
        MapsLayersModel::where( 'layer_id', $this->layer['id'] )->delete();
        Datastore::drop( $this->layer['tablename'] );
        ( new Filesystem )->remove( PATH_TO_IMAGES . 'layer_preview_' .  $this->layer['id'] . '.png' );       
        ( new Filesystem )->remove( PATH_TO_IMAGES . 'layer_symbol_' .  $this->layer['id'] . '.png' );       
    }

    public static function deleteByProjectName($project_name)
    {
        $layers = LayersModel::where('project_name', $project_name)->get();
        foreach ($layers as $layer) {
			( new self($layer->id) )->delete();
        }
    }
	
	protected function getMaplayer()
	{
        return $this->getQgis()->getMaplayerByName( $this->layer['name'] );		
	}

	private function getQgis()
	{
		return new Qgis( $this->layer['map'] );		
	}

	public function getLayer()
	{
		return LayersModel::find( $this->layer->id );
	}
}