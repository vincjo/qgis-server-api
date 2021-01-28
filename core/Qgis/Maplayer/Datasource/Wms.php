<?php
namespace Core\Qgis\Maplayer\Datasource;

class Wms
{
    public function __construct(\SimpleXMLElement $maplayer)
    {
        $this->maplayer = $maplayer;
    }

	public function get(): array
	{
		$datasource = (string) ($this->maplayer->datasource);
		foreach ( explode('&', $datasource) as $value ) {
            $kv = explode('=', $value);
            if( isset($kv[1]) ) {
                $values[ $kv[0] ] = $kv[1];
            }
        }
        $values['url'] = urldecode( $values['url'] );
        if ( key_exists('layers', $values) ) {
            $values['url'] = explode('?', $values['url'])[0];
            $values['styles'] = urldecode( $values['styles'] );
        }
        $values['external_wms'] = urldecode($datasource);
		if( !isset($values) ) {
			return $datasource;
		}
		return $values;
	}
}