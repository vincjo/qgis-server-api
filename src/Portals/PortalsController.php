<?php
namespace Sigapp\Portals;

use \Sigapp\Maps\MapsModel;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Http\Response;

class PortalsController
{
	public function index(Request $request, Response $response)
	{
        $portals = PortalsModel::where('role_id', '>=', ROLE)
            ->with([
                'maps' => function($maps) {
                    $maps
                        ->where('role_id', '>=', ROLE)
                        ->whereNotIn('portal_id', PortalsModel::select('id')->where('owner', true))
                        ->orderBy('sort')
                        ->orWhere('user_id', USER)
                        ->whereIn('portal_id', PortalsModel::select('id')->where('owner', true))
                        ->orderBy('created_at');
                }
            ])  
            ->orderBy('sort')->get();
        return $response
            ->withHeader('Content-type', 'application/json')
            ->withJson($portals);
    }
    
    public function store(Request $request, Response $response)
    {
        $data = $request->getParsedBody();
        $portal = PortalsModel::create($data);
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withJson($portal);
    }

    public function show(Request $request, Response $response, $args)
    {
        $portal = PortalsModel::where( 'id', $args['id'] )
            ->with('maps')
            ->first();
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withJson( $portal );
    }

    public function update(Request $request, Response $response, $args)
    {
        $data = $request->getParsedBody();
        $portal = PortalsModel::find( $args['id'] )->update($data);
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withJson($portal);
    }

    public function reorder(Request $request, Response $response, $args)
    {
        $data = $request->getParsedBody();
        foreach ($data as $sort => $id) {
            $portal = PortalsModel::find($id);
            $portal->sort = $sort + 1;
            $portal->save();
        }
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withJson( true );
    }

    public function owner(Request $request, Response $response, $args)
    {
        PortalsModel::query()->update(['owner' => false]);
        $portal = PortalsModel::find( $args['id'] );
        $portal->owner = true;
        $portal->save();
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withJson($portal);
    }

    public function destroy(Request $request, Response $response, $args)
    {
        MapsModel::where('portal_id', $args['id'])->update(['portal_id' => 0]);
        $portal = PortalsModel::destroy($args['id']);
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withJson($portal);
    }
}