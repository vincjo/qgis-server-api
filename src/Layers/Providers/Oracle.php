<?php
namespace Sigapp\Layers\Providers;

use \PDO;
use \Sigapp\Layers\{ LayersModel, LayersEntityInterface };
use \geoPHP;

class Oracle implements LayersEntityInterface
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
		$this->db = new PDO('oci:' . $layer->datasource['host'] . ':' . $layer->datasource['port'] . '/' . $layer->datasource['dbname'], $layer->datasource['user'], $layer->datasource['password']);
	}

	public function getDatatable(string $filter = '')
	{
		return 'Not implented yet';
		$filter = new Filter($this->sql, $filter, true);
		$datatable = $this->db->query(
			$this->datatable . " WHERE rownum < 5000 " . $filter->check()->compile()
		)->fetchAll(PDO::FETCH_ASSOC);
		if(!isset($datatable)){
			return false;
		}
		return $datatable;
	}

	public function getDatatableFromExtent(array $extent, string $filter = '')
	{
		return 'Not implented yet';
	}

	public function getExtent(string $filter = '')
	{
		return 'Not implented yet';
        $filter = new Filter($this->sql, $filter, false);
		$result = $this->db->query("
			WITH extent AS(
				SELECT SDO_TUNE.EXTENT_OF('" . $this->table . "', " . $this->geom . "') AS geom
				FROM DUAL
				" . $filter->check()->compile() . "
			)
			SELECT
                REPLACE(TO_CHAR(SDO_GEOM.SDO_MIN_MBR_ORDINATE(SDO_CS.TRANSFORM(geom, 3785), 1)), ',', '.' ) || ',' ||
                REPLACE(TO_CHAR(SDO_GEOM.SDO_MIN_MBR_ORDINATE(SDO_CS.TRANSFORM(geom, 3785), 2)), ',', '.' ) || ',' ||
                REPLACE(TO_CHAR(SDO_GEOM.SDO_MAX_MBR_ORDINATE(SDO_CS.TRANSFORM(geom, 3785), 1)), ',', '.' ) || ',' ||
                REPLACE(TO_CHAR(SDO_GEOM.SDO_MAX_MBR_ORDINATE(SDO_CS.TRANSFORM(geom, 3785), 2)), ',', '.' )
			AS extent
			FROM extent
		")->fetch(PDO::FETCH_ASSOC);
		return $result["extent"];
	}

	public function getFeatureInfo(string $identifier)
	{
		return 'Not implented yet';
		$result = $this->db->query("
			SELECT DISTINCT
			To_Char(DBMS_LOB.SUBSTR(SDO_UTIL.TO_WKTGEOMETRY(SDO_CS.TRANSFORM(" . $this->geom . ", 4326)), 2000, 1)) ||
			To_Char(DBMS_LOB.SUBSTR(SDO_UTIL.TO_WKTGEOMETRY(SDO_CS.TRANSFORM(" . $this->geom . ", 4326)), 2000))
			AS geojson
			FROM " . $this->table . "
			WHERE \"" . $this->pk . "\" = '" . trim($identifier) . "'
		")->fetch(PDO::FETCH_OBJ)->GEOJSON;
		$json = $this->wkt2json(stream_get_contents($result));
		return $json;
	}

	public function getFeatureInfoFromCoordinates(float $x, float $y, float $zoom, string $filter = '')
	{
		return 'Not implented yet';
		$filter = new Filter($this->sql, $filter, true);
		$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$result = $this->db->query("
			SELECT DISTINCT \"" . $this->pk . "\" AS ID,  TO_CHAR(SDO_UTIL.TO_WKTGEOMETRY(SDO_CS.TRANSFORM(" . $this->geom . ", 3785))) AS WKT,
			SDO_GEOM.SDO_DISTANCE(
				SDO_GEOMETRY('POINT($x $y)', 3785),
				SDO_CS.TRANSFORM(" . $this->geom . ", 3785),
				(3000000 / POWER($zoom, 4))
			) AS DIST
			FROM " . $this->table . "
			WHERE ROWNUM = 1
			" . $filter->check()->compile() . "
			ORDER BY DIST
		")->fetch(PDO::FETCH_OBJ);
		return [
			$result->ID, 
			$this->wkt2json($result->WKT)
		];	
	}

	public function getEntityExtent($identifier)
	{
		return 'Not implented yet';
		$result = $this->db->query("
			SELECT
			REPLACE(TO_CHAR(SDO_GEOM.SDO_MIN_MBR_ORDINATE(SDO_CS.TRANSFORM(" . $this->geom . ", 3785), 1)), ',', '.' )|| ',' ||
			REPLACE(TO_CHAR(SDO_GEOM.SDO_MIN_MBR_ORDINATE(SDO_CS.TRANSFORM(" . $this->geom . ", 3785), 2)), ',', '.' ) || ',' ||
			REPLACE(TO_CHAR(SDO_GEOM.SDO_MAX_MBR_ORDINATE(SDO_CS.TRANSFORM(" . $this->geom . ", 3785), 1)), ',', '.' ) || ',' ||
			REPLACE(TO_CHAR(SDO_GEOM.SDO_MAX_MBR_ORDINATE(SDO_CS.TRANSFORM(" . $this->geom . ", 3785), 2)), ',', '.' )
			AS EXETENT
			FROM " . $this->table . "
			WHERE \"" . $this->pk . "\" = '" . trim($identifier) . "'
		")->fetch(PDO::FETCH_ASSOC);
		$extent = $result["EXETENT"];
		return $extent;
	}
	
	public function getDistinctValues(string $attribute)
	{
		return 'Not implented yet';
        $result = $this->db->query("
            SELECT DISTINCT $attribute AS value
			FROM " . $this->table . "
			WHERE rownum < 150
            ORDER BY $attribute
        ")->fetchAll(PDO::FETCH_OBJ);
		foreach($result as $row){
			$values[] =  (is_numeric($row->value)) ? $row->value : "'" . $row->value . "'" ;
		}
        return $values;
    }	

	private function wkt2json($wkt)
	{
		// $geom = geoPHP::load($wkt, 'wkt');
		// return $geom->out('json');
	}
}