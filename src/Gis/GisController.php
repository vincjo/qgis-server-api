<?php
namespace Sigapp\Gis;

use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Http\Response;

class GisController
{
    public function index(Request $request, Response $response)
    {
        $tree = new GisEntity;
        $gis = [
            'catalog' => $tree->getFolders(),
            'tempdir' => $tree->getTempDir()
        ];
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withJson( $gis );
    }
    
    public function search(Request $request, Response $response, $args)
    {
        $value = strtoupper( urldecode($args['value']) );
        $result = ViewFolderLayerModel::where([
            ['search_field', 'like', '%' . $value . '%'],
            ['title', '!=', null],
        ])->get();
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withJson( $result );
    }
}