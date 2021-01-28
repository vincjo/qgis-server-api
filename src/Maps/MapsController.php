<?php
namespace Sigapp\Maps;

use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Http\Response;

class MapsController
{
    public function index(Request $request, Response $response, $args)
    {
        $map = new MapsEntity( $args['id'] );
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withJson($map);
    }

    public function store(Request $request, Response $response){
        $data = $request->getParsedBody();
        $data['user_id'] = USER;
		$map = ( new MapsManager($data) )->create();
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withJson($map);
    }

    public function update(Request $request, Response $response, $args)
    {
        $data = $request->getParsedBody();
        $map = MapsModel::find( $args['id'] )->update($data);
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withJson($map);
    }

    public function portal(Request $request, Response $response, $args)
    {
        $map = MapsModel::find( $args['id'] );
        $map->portal_id = $args['portal_id'];
        $map->save();
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withJson($map);
    }

    public function reorder(Request $request, Response $response, $args)
    {
        $data = $request->getParsedBody();
        foreach ($data as $sort => $id) {
            $map = MapsModel::find($id);
            $map->timestamps = false;
            $map->sort = $sort + 1;
            $map->save();
            $map->timestamps = true;
        }
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withJson( true );
    }

    public function preview(Request $request, Response $response, $args)
    {
        $data = $request->getParsedBody();
        $preview = ( new MapsPreview($data['url'], $args['id'], $data['jwt']) )->create();
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withJson( $preview );
    }

    public function destroy(Request $request, Response $response, $args)
    {
        MapsManager::deleteById( $args['id'] );
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withJson(true);
    }

    public function views(Request $request, Response $response, $args)
    {
        $map = MapsModel::find( $args['id'] );
        $map->timestamps = false;
        $map->increment('views');
        $map->timestamps = true;
        MapsViewModel::create([
            'map_id' => $map->id,
            'user_id' => USER
        ]);
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withJson( $map->views );
    }
}