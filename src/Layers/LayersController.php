<?php
namespace Sigapp\Layers;

use Slim\Http\Request;
use Slim\Http\Response;

class LayersController
{
    public function index(Request $request, Response $response, $args)
    {
        $layer = LayersModel::find( $args['id'] );
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withJson($layer);
    }

    public function update(Request $request, Response $response, $args)
    {
        $data = $request->getParsedBody();
        $entity = new LayersEntity( $args['id'] );
        $entity->update($data);
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withJson( $entity->getLayer() );
    }

    public function destroy(Request $request, Response $response, $args)
    {
        $entity = new LayersEntity( $args['id'] );
        $entity->delete();
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withJson(true);
    }    

    public function datatable(Request $request, Response $response, $args)
    {
        $entity = new LayersEntity( $args['id'] );
        if ( isset( $args['srid'] ) ) {
            $extent = [
                'xmin' => $args['xmin'],
                'ymin' => $args['ymin'],
                'xmax' => $args['xmax'],
                'ymax' => $args['ymax'],
                'srid' => $args['srid'],
            ];
            $datatable = $entity->getDatatableFromExtent( $extent, $this->getFilter($request) );
        } else {
            $datatable = $entity->getDatatable( $this->getFilter($request) );
        }
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withJson($datatable);
    }

    public function extent(Request $request, Response $response, $args)
    {
        $entity = new LayersEntity( $args['id'] );
        if( isset( $args['identifier'] ) ) {
            $extent = $entity->getEntityExtent( $args['identifier'] );
        } else {
            $extent = $entity->getExtent( $this->getFilter($request) );
        }
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withJson($extent);
    }

    public function updateExtent(Request $request, Response $response, $args)
    {
        $entity = new LayersEntity( $args['id'] );
        $filter = $this->getFilter($request); 
        $entity->setExtent($filter);
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withJson(true);
    }

    public function columns(Request $request, Response $response, $args)
    {
        $columns = LayersModel::find( $args['id'] )->columns;
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withJson($columns);
    }

    public function updateColumns(Request $request, Response $response, $args)
    {
        $data = $request->getParsedBody();
        ( new LayersEntity( $args['id'] ) )->updateColumns($data);
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withJson($data);
    }  

    public function values(Request $request, Response $response, $args)
	{
        $entity = new LayersEntity( $args['id'] );
        $values = $entity->getDistinctValues( $args['attribute'] );
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withJson($values);
    }

    public function export(Request $request, Response $response, $args)
    {
        $extent = $request->getParsedBody()['extent'];
        $io = new LayersIO( $args['id'], $args['format'], $this->getFilter($request), $extent );
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withJson( $io->extract() );
    }

    public function featureinfo(Request $request, Response $response, $args)
    {
        $entity = new LayersEntity( $args['id'] );
        if ( isset($args['identifier']) ) {
            $featureinfo = $entity->getFeatureInfo( $args['identifier'] );
        } else {
            $featureinfo = $entity->getFeatureInfoFromCoordinates(
                $args['x'], 
                $args['y'], 
                $args['zoom'], 
                $this->getFilter($request)
            );
        }
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withJson($featureinfo);
    }

    public function folder(Request $request, Response $response, $args)
    {
        $layer = LayersModel::find( $args['id'] );
        $layer->folder_id = $args['folder_id'];
        $layer->save();
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withJson($layer);
    }

    public function project(Request $request, Response $response, $args)
    {
        $entity = new LayersEntity( $args['id'] );
        $entity->moveToProjectfile( $args['project_name'] );
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withJson( $entity->getLayer() );
    }
    
    public function previews(Request $request, Response $response, $args)
    {
        $entity = new LayersEntity( $args['id'] );
        $entity->updatePreviews();
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withJson( $entity->getLayer() );
    }

    public function replace(Request $request, Response $response, $args)
    {
        $entity = new LayersEntity($args['id']);
        $replacement = LayersModel::find( $args['layer_id'] );
        $entity->replaceBy($replacement);
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withJson( $entity->getLayer() );
    }

    private function getFilter(Request $request)
    {
        if ( !isset($request->getParams()['filter']) || $request->getParams()['filter'] === 'null') {
            return '';
        }
        return $request->getParams()['filter'];
    }
}