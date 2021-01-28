<?php
namespace Sigapp\Datasources;

use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Http\Response;
use \Sigapp\Layers\LayersModel;

class DatasourcesController
{
    public function index(Request $request, Response $response)
    {
        $datasources = DatasourcesModel::orderBy('created_at')->get();
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withJson($datasources);
    }

    public function store(Request $request, Response $response, $args)
    {
        $data = $request->getParsedBody();
        $connectionTest = ( new DatasourcesEntity( $data ) )->connectionTest();
        if ( $connectionTest['outcome'] === false ) {
            return $response
                ->withStatus(412)
                ->withHeader('Content-Type', 'application/json')
                ->withJson( $connectionTest );   
        }
        $datasource = ( new DatasourcesEntity($data) )->create();
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withJson( $datasource );       
    }

    public function connection(Request $request, Response $response, $args)
    {
        $data = $request->getParsedBody();
        $ds = DatasourcesModel::find( $args['id'] )->toArray();
        $ds['user'] = $data['user'];
        $ds['password'] = $data['password'];
        $manager = new DatasourcesEntity($ds);
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withJson( $manager->connectionTest() );
    }

    public function update(Request $request, Response $response, $args)
    {
        $data = $request->getParsedBody();
        $ds = DatasourcesModel::find( $args['id'] );
        $ds->dbname = $data['dbname'];
        $ds->host = $data['host'];
        $ds->port = $data['port'];
        $ds->user = $data['user'];
        $ds->password = $data['password'];
        $connectionTest = ( new DatasourcesEntity( $ds->toArray() ) )->connectionTest();
        if ( $connectionTest['outcome'] === true ) {
            $ds->save();
        }
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withJson( $connectionTest );       
    }

    public function destroy(Request $request, Response $response, $args)
    {
        $layers = LayersModel::where('datasource_id', $args['id'])->get();
        if ( !empty($layers) ) {
            return $response
                ->withStatus(412)
                ->withHeader('Content-Type', 'application/json')
                ->withJson($layers);
        }
        DatasourcesModel::destroy( $args['id'] );
        return $response
            ->withHeader('Content-Type', 'application/json')
            // ->withJson( $removed );
            ->withJson(true);
    }    
}