<?php
namespace Core\Datastore;

use Symfony\Component\Process\Process;

class Extract
{
    private $maplayer;
    private $filter;
    private $extent;
    private $format;

    public function __construct(\Core\Qgis\Maplayer $maplayer, ?string $filter, array $extent = [], string $format = 'gpkg')
    {
        $this->maplayer = $maplayer;
        $this->dir      = pathinfo( $maplayer->project['file'], PATHINFO_DIRNAME);
        $this->filter   = $filter;
        $this->extent   = $extent;
        $this->format   = $format;
    }

    public function process(): Extract
    {
        $process = new Process([
            'ogr2ogr',
            '-f',  $this->getDriver(),
            $this->dir . '/data.' . $this->format, $this->getDatasource(),
            '-sql', $this->getSqlQuery(),
            '-nlt',  $this->maplayer->getGeometryType(),
            '-lco', 'GEOMETRY_NAME=geom',
            '-lco', 'PRECISION=NO',
            '-lco', 'LAUNDER=NO',
            '-lco', 'FID=' . $this->maplayer->getDisplayfield(),
            '-nln', $this->getTablename(),
            '-dim',  'XY',
            '-append'
        ]);
        $process->run();
		if (!$process->isSuccessful()) {
            $error = "Exctraction issue. " . $process->getErrorOutput();
            // $error = "Exctraction issue. " . $process->getCommandLine();
            throw new \Exception($error);
        }
        return $this;
    }

    private function getDatasource(): string
    {
        $db = $this->maplayer->getDatasource();
        return "PG:dbname='". $db['dbname'] ."' host='". $db['host'] ."' port='". $db['port'] ."' user='". $db['user'] ."' password='". $db['password'] ."'";
    }

    private function getTablename(): string
    {
        $origin = $this->maplayer->getTablename();
        $destination = strtolower( str_replace('"', '', $origin) );
        if ( strpos($destination, '.') ) {
            return explode('.', $destination)[1];
        }
        return $destination;
    }

    private function getDriver(): string
    {
        $drivers = [
            'gpkg'      => 'GPKG',
            'sqlite'    => 'SQLite'
    
        ];
        return $drivers[$this->format];   
    }

    private function getSqlQuery(): string
    {
        foreach ($this->maplayer->getColumns() as $column) {
			if ( strlen($column['name']) > 0 ) {
				$str[] = '"' . $column['name'] . '"';
            }
		}        
        $query = "SELECT " . implode(', ', $str) . ', ' . $this->maplayer->getGeomcolumn() . " FROM " . $this->maplayer->getTablename();
        if ( !empty($this->extent) ) {
            $query .= $this->whereIntersects($this->extent);
        }
        $query .= $this->getFilter();
        $query = preg_replace('#\n|\t|\r#', ' ', trim(utf8_encode($query)));
        return $query;
    }

    private function whereIntersects(array $extent): string
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
					), {$this->maplayer->getSrid()}
				), "{$this->maplayer->getGeomcolumn()}"
			)
		SQL;
	}

	private function getFilter(): ?string
	{
		$filter = new \Sigapp\Layers\Providers\Filter($this->maplayer->getSql(), $this->filter, true);
		return $filter->get();
    }
    
    public function updateDatasource(): void
    {
        $this->maplayer->setDatasource([
            'projectfile' => $this->maplayer->project['file'],
            'layer_name'  => $this->maplayer->getName(),
            'file'        => './data.gpkg',
            'tablename'   => $this->getTablename(),
            'provider'    => 'ogr',
        ]);
    }
}