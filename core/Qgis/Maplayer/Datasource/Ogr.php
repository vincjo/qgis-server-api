<?php
namespace Core\Qgis\Maplayer\Datasource;

class Ogr
{
    public function __construct(\SimpleXMLElement $maplayer)
    {
        $this->maplayer = $maplayer;
    }

	public function get(): array
	{
        $datasource = (string) $this->maplayer->datasource;
        if ( strpos($datasource, '|') !== false ) {
            $split = explode( '|', $datasource);
            $values['file'] = $split[0];
            $values['extension'] = pathinfo( $split[0], PATHINFO_EXTENSION );
            $values['tablename'] = str_replace('layername=', '', $split[1]);
        }
        else {
            $values['file'] = $datasource;
            $values['extension'] = pathinfo($datasource, PATHINFO_EXTENSION);
            $values['tablename'] = pathinfo($datasource, PATHINFO_FILENAME);
        }
        $values['geomcolumn'] = 'geom';
        $values['encoding'] = (string) $this->maplayer->provider['encoding'];
        return $values;
    }
    
	public function set(array $datasource): void
	{
        $this->maplayer->datasource = $datasource['file'];
		$this->maplayer->datasource .= '|layername=';
		$this->maplayer->datasource .= $datasource['tablename'];
		$this->maplayer->provider = 'ogr';
	}
}