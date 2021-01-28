<?php
namespace Core\Geoprocessing;

use \Database\Database;

class Extent
{
    public function __construct(array $extent)
    {
        $this->extent = $extent;
        $this->db = Database::connect();
    }

    public function transform(int $srid)
    {
        return $this->db->query("
            SELECT '" . $srid . "' AS srid,
                ST_X(ST_Transform(ST_SetSRID(ST_MakePoint(" . $this->extent['xmin'] . ", " . $this->extent['ymin'] . "), " . $this->extent['srid'] . "), " . $srid . ")) AS xmin,
                ST_Y(ST_Transform(ST_SetSRID(ST_MakePoint(" . $this->extent['xmin'] . ", " . $this->extent['ymin'] . "), " . $this->extent['srid'] . "), " . $srid . ")) AS ymin,
                ST_X(ST_Transform(ST_SetSRID(ST_MakePoint(" . $this->extent['xmax'] . ", " . $this->extent['ymax'] . "), " . $this->extent['srid'] . "), " . $srid . ")) AS xmax,
                ST_Y(ST_Transform(ST_SetSRID(ST_MakePoint(" . $this->extent['xmax'] . ", " . $this->extent['ymax'] . "), " . $this->extent['srid'] . "), " . $srid . ")) AS ymax
        ")->fetch(\PDO::FETCH_ASSOC);
    }

    public function getPerimeter()
    {
        $width = $this->db->query("
            SELECT ST_Distance(
                ST_Transform(ST_SetSRID(ST_MakePoint(" . $this->extent['xmin'] . ", " . $this->extent['ymin'] . "), " . $this->extent['srid'] . " ), 3857), 
                ST_Transform(ST_SetSRID(ST_MakePoint(" . $this->extent['xmax'] . ", " . $this->extent['ymin'] . "), " . $this->extent['srid'] . " ), 3857)
            ) AS width
        ")->fetch(\PDO::FETCH_ASSOC)['width'];
        $length = $this->db->query("
            SELECT ST_Distance(
                ST_Transform(ST_SetSRID(ST_MakePoint(" . $this->extent['xmin'] . ", " . $this->extent['ymin'] . "), " . $this->extent['srid'] . " ), 3857), 
                ST_Transform(ST_SetSRID(ST_MakePoint(" . $this->extent['xmin'] . ", " . $this->extent['ymax'] . "), " . $this->extent['srid'] . " ), 3857)
            ) AS length
        ")->fetch(\PDO::FETCH_ASSOC)['length'];
        return [
            "width" => floatval($width),
            "length" => floatval($length),
            "ratio" => floatval($width) / floatval($length),
            "bbox" => $this->toString()
        ];
    }

	public function expand(float $paddingInPercent){
        $perimeter = $this->getPerimeter();
        $expand = $paddingInPercent * min([$perimeter['width'], $perimeter['length']]);
        $result = $this->db->query("
            WITH expand AS (
                SELECT ST_Expand(
                    ST_SetSRID(ST_MakePolygon(ST_GeomFromText(
                        'LINESTRING(
                            " . $this->extent['xmin'] . " " . $this->extent['ymin'] . ",
                            " . $this->extent['xmax'] . " " . $this->extent['ymin'] . ",
                            " . $this->extent['xmax'] . " " . $this->extent['ymax'] . ",
                            " . $this->extent['xmax'] . " " . $this->extent['ymax'] . ",
                            " . $this->extent['xmin'] . " " . $this->extent['ymin'] . "
                        )'
                    )), " . $this->extent['srid'] . "
                ), $expand) AS new_polygon
            )
            SELECT
                ST_XMin(new_polygon) AS xmin,
                ST_YMin(new_polygon) AS ymin,
                ST_XMax(new_polygon) AS xmax,
                ST_YMax(new_polygon) AS ymax
            FROM expand;
        ")->fetch(\PDO::FETCH_ASSOC);
        $this->extent = [
            "srid" => $this->extent['srid'],
            "xmin" => $result['xmin'],
            "ymin" => $result['ymin'],
            "xmax" => $result['xmax'],
            "ymax" => $result['ymax'],
        ];
        return $this;
    }    
    
    public function toString()
    {
        return $this->extent['xmin'] . ',' . $this->extent['ymin'] . ',' . $this->extent['xmax'] . ',' . $this->extent['ymax'];
    }
}