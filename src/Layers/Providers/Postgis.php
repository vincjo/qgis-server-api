<?php
namespace Sigapp\Layers\Providers;

use \PDO;
use \Sigapp\Layers\{ LayersModel, LayersEntityInterface };

class Postgis implements LayersEntityInterface
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
		$this->db = new PDO('pgsql:
			host=' 		. $layer->datasource['host'] 	. ';
			port=' 		. $layer->datasource['port'] 	. ';
			dbname=' 	. $layer->datasource['dbname'] 	. ';
			user=' 		. $layer->datasource['user'] 	. ';
			password=' 	. $layer->datasource['password']
		);
	}
	public function getDatatable(string $filter = '')
	{
		$datatable = $this->db->query(<<<SQL
			SELECT "{$this->displayfield}" AS "APP_DISPLAYFIELD", {$this->datatable} 
			FROM {$this->tablename} {$this->getFilter($filter)}
			LIMIT 2000
		SQL)->fetchAll(PDO::FETCH_ASSOC);
		if ( !isset($datatable) ) {
			return false;
		}
		return $datatable;
	}

	public function getDatatableFromExtent(array $extent, string $filter = '')
	{
		$datatable = $this->db->query(<<<SQL
			SELECT "{$this->displayfield}" AS "APP_DISPLAYFIELD", {$this->datatable} 
			FROM {$this->tablename} 
			{$this->whereIntersects($extent)}
			{$this->getFilter($filter, true)}
			LIMIT 2000
		SQL)->fetchAll(PDO::FETCH_ASSOC);
		if ( !isset($datatable) ) {
			return false;
		}
		return $datatable;
	}

	public function getExtent(string $filter = '')
	{
		return $this->db->query(<<<SQL
			WITH extent AS(
				SELECT ST_Extent(ST_Transform(ST_SetSRID("{$this->geomcolumn}", {$this->srid}), 3857)) AS geom
				FROM {$this->tablename} {$this->getFilter($filter)}
			)
			SELECT
				ST_XMin(geom) AS xmin,
				ST_YMin(geom) AS ymin,
				ST_XMax(geom) AS xmax,
				ST_YMax(geom) AS ymax,
				3857 AS srid
			FROM extent
		SQL)->fetch(PDO::FETCH_ASSOC);
	}
    
	public function getFeatureInfo(string $identifier)
	{
		$result = $this->db->query(<<<SQL
			SELECT {$this->datatable},
			ST_AsGeoJson(ST_Transform(ST_SetSRID("{$this->geomcolumn}", {$this->srid}), 4171), 5, 0) AS geojson
			FROM {$this->tablename}
			WHERE "{$this->displayfield}" = '{$identifier}';
		SQL)->fetch(PDO::FETCH_OBJ);
		$result->geojson = mb_convert_encoding($result->geojson,  "ISO-8859-1");
		return $result;
	}

	public function getFeatureInfoFromCoordinates(float $x, float $y, float $zoom, string $filter = '')
	{
		$result = $this->db->query(<<<SQL
			WITH pointer AS (
				SELECT ST_Transform(ST_SetSRID(ST_MakePoint({$x}, {$y}), 3857), {$this->srid}) AS p_geom
			)
			SELECT DISTINCT "{$this->displayfield}" AS displayfield,
			MIN(ST_Distance(p_geom, ST_SetSRID("{$this->geomcolumn}", {$this->srid})))
			FROM {$this->tablename}, pointer
			WHERE ST_Distance(p_geom, ST_SetSRID("{$this->geomcolumn}", {$this->srid})) <= (3000000 / POW({$zoom}, 4))
			{$this->getFilter($filter, true)}
			GROUP BY "{$this->displayfield}", "{$this->geomcolumn}"
			ORDER BY min
			LIMIT 1
		SQL)->fetch(PDO::FETCH_ASSOC);
		if ($result) {
			return $this->getFeatureInfo( $result['displayfield'] );
		}
		return false;		
	}

	public function getEntityExtent(string $identifier)
	{
		return $this->db->query(<<<SQL
			SELECT
			ST_XMin(ST_Transform(ST_SetSRID("{$this->geomcolumn}", {$this->srid}), 3857)) AS xmin,
			ST_YMin(ST_Transform(ST_SetSRID("{$this->geomcolumn}", {$this->srid}), 3857)) AS ymin,
			ST_XMax(ST_Transform(ST_SetSRID("{$this->geomcolumn}", {$this->srid}), 3857)) AS xmax,
			ST_YMax(ST_Transform(ST_SetSRID("{$this->geomcolumn}", {$this->srid}), 3857)) AS ymax,
			3857 AS srid
			FROM {$this->tablename}
			WHERE "{$this->displayfield}" = '{$identifier}'
		SQL)->fetch(PDO::FETCH_ASSOC);
	}
	
	public function getDistinctValues(string $attribute)
	{
        $result = $this->db->query(<<<SQL
            SELECT DISTINCT "{$attribute}" AS value
            FROM {$this->tablename}
            ORDER BY "{$attribute}"
            LIMIT 150
        SQL)->fetchAll(PDO::FETCH_OBJ);
		foreach($result as $row){
			$values[] =  (is_numeric($row->value)) ? $row->value : "'" . $row->value . "'" ;
		}
        return $values;
	}	
	
	public function whereIntersects(array $extent)
	{
		return <<<SQL
			WHERE ST_Intersects(
				ST_Transform( 
					ST_SetSRID( 
						ST_MakePolygon( 
							ST_GeomFromText( 
								'LINESTRING(
									{$extent["xmin"]} {$extent["ymin"]}, 
									{$extent["xmin"]} {$extent["ymax"]}, 
									{$extent["xmax"]} {$extent["ymax"]}, 
									{$extent["xmax"]} {$extent["ymin"]}, 
									{$extent["xmin"]} {$extent["ymin"]}
								)' 
							) 
						), {$extent['srid']} 
					), {$this->srid}
				), "{$this->geomcolumn}"
			)
		SQL;
	}

	public function getFilter(string $filter, bool $precondiction = false)
	{
		$filter = new Filter($this->sql, $filter, $precondiction);
		return $filter->get();
	}
	
}