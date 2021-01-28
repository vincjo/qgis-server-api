<?php
namespace Sigapp\Datasources;

use \Sigapp\Layers\LayersModel;

class DatasourcesEntity
{
    public function __construct(array $datasource)
    {
        $this->datasource = $datasource;
    }

    public function create()
    {
        $datasource = DatasourcesModel::create($this->datasource);
        return $datasource->id;
    }

    public function createIfNotRecorded()
    {
        $isRecorded = DatasourcesModel::where([
            [ 'provider', '=', $this->datasource['provider'] ],
            [ 'dbname',   '=', $this->datasource['dbname']   ],
            [ 'host',     '=', $this->datasource['host']     ],
            [ 'port',     '=', $this->datasource['port']     ],
            [ 'user',     '=', $this->datasource['user']     ],
        ])->first();
        if ($isRecorded === null){
            return $this->create();
        }
        return $isRecorded->id;
    }
    
    public function connectionTest() {
        if ($this->datasource['provider'] === 'postgres') {
            try{
                new \PDO( 
                    'pgsql:host='.$this->datasource['host'].';port='.$this->datasource['port'].';dbname='.$this->datasource['dbname'].';', 
                    $this->datasource['user'], 
                    $this->datasource['password'],
                    [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]
                );
                $result =  ['outcome' => true];
            } 
            catch (\PDOException $ex) {
                $result = ['outcome' => false, 'message' => $ex->getMessage() ];
            }
        } 
        else {
            $result = ['outcome' => false, 'message' => "Database driver '{$this->datasource['provider']}' is not supported" ];
        }
        return $result;     
    }

    public function updateLayersDatasource()
    {
        $layers = LayersModel::where('datasource_id', $this->datassource['id'])->get();
        foreach ($layers as $layer) {
            $qgis = new \Core\Qgis\Qgis( $layer['map'] );
            $maplayer = $qgis->getMaplayerByname( $layer['name'] );
            $maplayer->setDatasource( $this->datasource, $layer['sql'], $layer['geomcolumn'] );
        }
    }
}