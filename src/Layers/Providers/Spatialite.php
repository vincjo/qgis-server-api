<?php
namespace Sigapp\Layers\Providers;

use Spatialite\SPL;
use \Sigapp\Layers\{ LayersModel, LayersEntityInterface };

class Spatialite implements LayersEntityInterface
{
	protected $tablename;
    protected $displayfield;
    protected $geomcolumn;
    protected $srid;
    protected $extent;
    protected $sql;
    protected $datatable;
	protected $db;
	
	public function __construct(LayersModel $layer)
	{
        $this->tablename    = $layer->tablename;
        $this->displayfield = $layer->displayfield;
        $this->geomcolumn   = $layer->geomcolumn;
        $this->srid         = $layer->srid;
        $this->extent       = $layer->extent;
        $this->sql          = $layer->sql;
        $this->datatable    = $layer->datatable;
        $this->db   		= new SPL($layer->datasource['dbname']);
	}

	public function getDatatable(string $filter = '')
	{
		$datatable = $this->db->query("
			SELECT " . $this->datatable . "
			FROM " . $this->tablename . $this->getFilter($filter) . "
			LIMIT 2000
		")->fetchAll(SPL::FETCH_ASSOC);
		if(!isset($datatable)){
			return false;
		}
		return $datatable;
	}

	public function getDatatableFromExtent(array $extent, string $filter = '')
	{
		$datatable = $this->db->query("
			SELECT " . $this->datatable ."
			FROM " . $this->tablename . " 
			" . $this->whereIntersects($extent) . "
			" . $this->getFilter($filter, true) . "
			LIMIT 2000
		")->fetchAll(SPL::FETCH_ASSOC);
		if ( !isset($datatable) ) {
			return false;
		}
		return $datatable;
	}

	public function getExtent(string $filter = '')
	{
        return $this->db->query("
            WITH extent AS(
                SELECT Extent(ST_Transform(SetSRID(" . $this->geomcolumn . ", " . $this->srid . "), 3857)) AS geom
				FROM " . $this->tablename . $this->getFilter($filter) . "
            )
            SELECT
                ST_minX(geom) AS xmin,
                ST_MinY(geom) AS ymin,
                ST_MaxX(geom) AS xmax,
				ST_MaxY(geom) AS ymax,
				3857 AS srid
            FROM extent
        ")->fetch(SPL::FETCH_ASSOC);
	}

	public function getFeatureInfo(string $identifier)
	{
		return $this->db->query("
			SELECT " . $this->datatable . ", 
			AsGeoJson(ST_Transform(SetSRID(" . $this->geomcolumn . ", " . $this->srid . "), 4171), 5, 0) AS geojson
			FROM " . $this->tablename . "
			WHERE " . $this->displayfield . " = '" . trim($identifier) . "';
		")->fetch(SPL::FETCH_OBJ);
	}	

	public function getFeatureInfoFromCoordinates(float $x, float $y, float $zoom, string $filter = '')
	{
		$result = $this->db->query("
			WITH pointer AS (
				SELECT ST_Transform(SetSRID(MakePoint(" . $x . ", " . $y . "), 3857), " . $this->srid . ") AS p_geom
			)
			SELECT DISTINCT " . $this->displayfield . " as displayfield, 
			MIN(ST_Distance(p_geom, " . $this->geomcolumn . "))
			FROM " . $this->tablename . ", pointer
			WHERE ST_Distance(p_geom, " . $this->geomcolumn . ") < (3000000 / POW($zoom, 4))
			" . $this->getFilter($filter, true) . "
			GROUP BY " . $this->displayfield . ", " . $this->geomcolumn . "
			LIMIT 1
		")->fetch(SPL::FETCH_ASSOC);
		if ($result) {
			return $this->getFeatureInfo( $result['displayfield'] );
		}
		return false;		
	}

	public function getEntityExtent(string $identifier)
	{
		return $this->db->query("
			SELECT
			ST_MinX(ST_Transform(SetSRID(" . $this->geomcolumn . ", " . $this->srid . "), 3857)) AS xmin,
			ST_MinY(ST_Transform(SetSRID(" . $this->geomcolumn . ", " . $this->srid . "), 3857)) AS ymin,
			ST_MaxX(ST_Transform(SetSRID(" . $this->geomcolumn . ", " . $this->srid . "), 3857)) AS xmax,
			ST_MaxY(ST_Transform(SetSRID(" . $this->geomcolumn . ", " . $this->srid . "), 3857)) AS ymax,
			3857 AS srid
			FROM " . $this->tablename . "
			WHERE " . $this->displayfield . " = '" . trim($identifier) . "';
		")->fetch(SPL::FETCH_ASSOC);
	}

	public function getDistinctValues(string $attribute)
	{
        $result = $this->db->query("
            SELECT DISTINCT $attribute AS value
            FROM " . $this->tablename . "
            ORDER BY $attribute
            LIMIT 150
        ")->fetchAll(SPL::FETCH_OBJ);
		foreach ($result as $row) {
			$values[] =  ( is_numeric($row->value) ) ? $row->value : "'" . $row->value . "'" ;
		}
		return $values;
	}

	public function whereIntersects(array $extent)
	{
		$polygon = "MakePolygon( 
			GeomFromText( 
				'LINESTRING(
					" . $extent['xmin'] . " " . $extent['ymin']  . ", 
					" . $extent['xmin'] . " " . $extent['ymax']  . ", 
					" . $extent['xmax'] . " " . $extent['ymax']  . ", 
					" . $extent['xmax'] . " " . $extent['ymin']  . ", 
					" . $extent['xmin'] . " " . $extent['ymin']  . "
				)' 
			) 
		)";
		$polygonTransformed = "Transform( SetSRID( $polygon, " . $extent['srid'] . " ), " . $this->srid . ")";
		return " WHERE Intersects($polygonTransformed, " . $this->geomcolumn . ")"; 
	}

	public function getFilter(string $filter, bool $precondiction = false)
	{
		$filter = new Filter($this->sql, $filter, $precondiction);
		return $filter->get();
	}
}