<?php
namespace Sigapp\Layers\IO;

class GeoJson
{
	/**
	 * @param array $result Associative array containing rows from sql query (PDO::FETCH_OBJ). 
	 * Rows has a key named "geojson" with ST_AsGeoJSON(geom) as value
	 */
	public function __construct($result)
	{
		$this->result = $result;
	}

	public function toGeoJson() 
	{
		if (is_array($this->result) ) {
			return json_decode( $this->toGeoJsonFromArray() );
		} else {
			return json_decode( $this->toGeoJsonFromObject() );
		}
	}

	public function toGeoJsonFromArray()
	{
		$output    = '';
		$rowOutput = '';
		foreach($this->result as $row) {
			$geom = is_array($row) ? $row['geojson'] : $row->geojson;
			$rowOutput = (strlen($rowOutput) > 0 ? ',' : '') . '{"type": "Feature", "geometry": ' . $geom . ', "properties": {';
			$props = '';
			$id    = '';
			foreach ($row as $key => $val) {
				if ($key != "geojson") {
					$props .= (strlen($props) > 0 ? ',' : '') . '"' . $key . '":"' . $this->escapeJsonString($val) . '"';
				}
				if ($key == "id") {
					$id .= ',"id":"' . $this->escapeJsonString($val) . '"';
				}
			}
			$rowOutput .= $props . '}';
			$rowOutput .= $id;
			$rowOutput .= '}';
			$output .= $rowOutput;
		}
		return '{ "type": "FeatureCollection", "features": [ ' . $output . ' ]}';
	}

	public function toGeoJsonFromObject()
	{
		$output    = '';
		$rowOutput = '';
		$row = $this->result;
		$geom = is_array($row) ? $row['geojson'] : $row->geojson;
		$rowOutput = (strlen($rowOutput) > 0 ? ',' : '') . '{"type": "Feature", "geometry": ' . $geom . ', "properties": {';
		$props = '';
		$id    = '';
		foreach ($row as $key => $val) {
			if ($key != "geojson") {
				$props .= (strlen($props) > 0 ? ',' : '') . '"' . $key . '":"' . $this->escapeJsonString($val) . '"';
			}
			if ($key == "id") {
				$id .= ',"id":"' . $this->escapeJsonString($val) . '"';
			}
		}
		$rowOutput .= $props . '}';
		$rowOutput .= $id;
		$rowOutput .= '}';
		$output .= $rowOutput;
		return $output;
	}

	private function escapeJsonString($value) 
	{
		$escapers = array("\\", "/", "\"", "\n", "\r", "\t", "\x08", "\x0c");
		$replacements = array("\\\\", "\\/", "\\\"", "\\n", "\\r", "\\t", "\\f", "\\b");
		return str_replace($escapers, $replacements, $value);
	}
}