<?php
namespace Sigapp\Gis;

use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Http\Response;

class GisFoldersController
{
    public function index(Request $request, Response $response)
    {
        $folders = ( new GisEntity )->getPaths();
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withJson($folders);
    }

    public function store(Request $request, Response $response)
    {
        $data = $request->getParsedBody();
        $folder = GisFoldersModel::create($data);
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withJson($folder);
    }

    public function show(Request $request, Response $response, $args)
    {
        if ( intval($args['id']) === 0) {
            $folder = ( new GisEntity )->getTempdir();
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withJson($folder);           
        }
        $folder = GisFoldersModel::find($args['id']);
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withJson($folder);
    }

    public function update(Request $request, Response $response, $args)
    {
        $data = $request->getParsedBody();
        $folder = GisFoldersModel::find( $args['id'] )->update($data);
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withJson($folder);
    }

    public function reorder(Request $request, Response $response, $args)
    {
        $data = $request->getParsedBody();
        foreach ($data as $sort => $id) {
            $folder = GisFoldersModel::find($id);
            $folder->sort = $sort + 1;
            $folder->save();
        }
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withJson( true );
    }

    public function move(Request $request, Response $response, $args)
    {
        $folder = GisFoldersModel::find( $args['id'] );
        $folder->parent_id = $args['parent_id'];
        $folder->save();
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withJson( $folder );
    }

    public function destroy(Request $request, Response $response, $args)
    {
        $gis = new GisEntity;
        $gis->deleteFolder( $args['id'] );
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withJson( $gis->getTempDir() );
    }

}