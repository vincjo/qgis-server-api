<?php
namespace Core\Qgis\Maplayer\Datasource;

use Core\Qgis\AbstractMaplayer;

class Db extends AbstractMaplayer
{
	protected $maplayer; 

    public function __construct(\SimpleXMLElement $maplayer)
    {
		$this->maplayer = $maplayer;
    }

	public function get(): array
	{
		$datasource = (string) htmlspecialchars_decode($this->maplayer->datasource);
		$datasource = preg_replace("#\n|\t|\r#", "", $datasource);
		$values = [];
		if ( preg_match('/sql=(.*)/', $datasource, $matches) ) {
		    $datasource = str_replace($matches[0], '', $datasource);
		    $values['sql'] = $matches[1];
        }
		foreach(explode(' ', $datasource) as $token){
		    $kv = explode('=', $token);
		    if(count($kv) == 2){
		        $values[$kv[0]] = str_replace("'", "", $kv[1]);
			}
			else {
		        if(preg_match('/\(([^\)]+)\)/', $kv[0], $matches)){
		            $values['geomcolumn'] = $matches[1];
		        }
		    }
		}
		if( empty($values) ) {
			return $datasource;
		}
		$values['tablename'] = str_replace('"', '', $values['table']);
		unset($values['table']);
		$values['provider'] = isset($values['provider']) ? $values['provider'] : $this->getProvider();
		$values['file'] = ($values['provider'] === 'spatialite') ? $values['dbname'] : null;
		return $values;
	}

	public function smoothValues(array $values)
	{

	}

	public function set(array $datasource): void
	{
		if (! in_array($datasource['provider'], ['spatialite', 'oracle', 'postgres']) ) {
			throw new \Exception('[LayerParser\Datasource::set] The provider is not a database type : ' . $datasource['provider']);
		}
		// <datasource>dbname='' host= port= user='' password='' key='' srid= type= checkPrimaryKeyUnicity='' table= () sql=</datasource>
		$this->maplayer->datasource = "dbname='" . $datasource['dbname'] . "' ";
		$this->maplayer->datasource .= isset($datasource['host']) ? 'host=' . $datasource['host'] . ' ' : '';
		$this->maplayer->datasource .= isset($datasource['port']) ? 'port=' . $datasource['port'] . ' ' : '';
		$this->maplayer->datasource .= isset($datasource['user']) ? "user='" . $datasource['user'] . "' " : '';
		$this->maplayer->datasource .= isset($datasource['password']) ? "password='" . $datasource['password'] . "' " : '';
		$this->maplayer->datasource .= "key='" . $this->getDisplayfield() . "' ";
		$this->maplayer->datasource .= "srid='" . $this->getSrid() . "' ";
		$this->maplayer->datasource .= 'type=' . $this->getGeometryType() . ' ';
		$this->maplayer->datasource .= "checkPrimaryKeyUnicity='1' ";
		$this->maplayer->datasource .= 'table=' . $datasource['tablename'] . ' ';
		$this->maplayer->datasource .= '(' . $datasource['geomcolumn'] . ') ';
		$this->maplayer->datasource .= 'sql=' . $datasource['sql'];
		$this->maplayer->provider = $datasource['provider'];
	}
}