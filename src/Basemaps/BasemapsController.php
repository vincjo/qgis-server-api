<?php
namespace Sigapp\Basemaps;

use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Http\Response;

class BasemapsController
{
    public function index(Request $request, Response $response)
    {
        $basemaps = [
            'displayed' => BasemapsModel::orderBy('sort')
                ->where('collection', 'displayed')
                ->get(),
            'stored'  => BasemapsModel::orderBy('title')
                ->where('collection', 'stored')
                ->get(),
            'overlayed'  => BasemapsModel::orderBy('title')
                ->where('collection', 'overlayed')
                ->get(),
        ];
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withJson($basemaps);
    }

    public function show(Request $request, Response $response, $args)
    {
        $basemaps = BasemapsModel::where( 'collection', $args['collection'] )->get();
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withJson($basemaps);
    }

    public function update(Request $request, Response $response, $args)
    {
        $data = $request->getParsedBody();
        $basemap = BasemapsModel::find( $args['id'] )->update($data);
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withJson($basemap);
    }

    public function collection(Request $request, Response $response, $args)
    {
        $basemap = BasemapsModel::find($args['id']);
        if (in_array($args['collection'], ['displayed', 'stored', 'overlayed']) ) {
            $basemap->collection = $args['collection'];
            $basemap->sort = 99999;
            if ($args['collection'] === 'overlay') {
                $basemap->overlays = null;
            }
            $basemap->save();
        }
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withJson($basemap);
    }

    public function reorder(Request $request, Response $response, $args)
    {
        $data = $request->getParsedBody();
        foreach ($data as $sort => $id) {
            $basemap = BasemapsModel::find($id);
            $basemap->sort = $sort + 1;
            $basemap->save();
        }
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withJson( true );
    }

    public function main(Request $request, Response $response, $args)
    {
        BasemapsModel::query()->update(['main' => false]);
        $basemap = BasemapsModel::find( $args['id'] );
        $basemap->main = true;
        $basemap->save();
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withJson($basemap);
    }

    // public function preview(Request $request, Response $response, $args)
    // {
    //     $extent = $request->getParsedBody();
    //     $preview = new BasemapsThumbnails( $args['id'] );
    //     $preview->create($extent);
    //     $basemap = BasemapsModel::find( $args['id'] );
    //     return $response
    //         ->withHeader('Content-Type', 'application/json')
    //         ->withJson( $basemap );
    // }

    public function preview(Request $request, Response $response, $args)
    {
        $base64 = $request->getBody();
        $data = base64_decode( preg_replace('#^data:image/\w+;base64,#i', '', $base64) );
        $img = "basemap_preview_{$args['id']}.png";
        file_put_contents( PATH_TO_IMAGES . $img, $data );
        $basemap = BasemapsModel::find( $args['id'] );
        $basemap->preview_url = '/images/' . $img . '?uid=' . uniqid();
        $basemap->save();
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withJson( $basemap );
    }

    public function overlays(Request $request, Response $response, $args)
    {
        $data = $request->getParsedBody();
        $basemap = BasemapsModel::find( $args['id'] );
        $overlay = BasemapsModel::where( 'id', $data['basemap_id'] )->get(['title', 'service', 'url', 'layer', 'format', 'style']);
        $values = (object) array_merge( $overlay->toArray()[0] , $data);
        if ( $basemap->overlays === null ) {
            $basemap->overlays = [ $values ];
        }
        else {
            $update = $basemap->overlays;
            array_push($update, $values);
            $basemap->overlays = $update;
        }
        $basemap->save();
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withJson( $basemap );
    }

    public function destroyOverlay(Request $request, Response $response, $args)
    {
        $basemap = BasemapsModel::find( $args['id'] );
        $update = $basemap->overlays;
        unset( $update[ $args['index'] ] );
        if ( count($update) === 0) {
            $update = null;
        }
        $basemap->overlays = $update;
        $basemap->save();
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withJson( $basemap );
    }

    public function destroy(Request $request, Response $response, $args)
    {
        $basemap = BasemapsModel::destroy( $args['id'] );
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withJson($basemap);
    }
}