<?php
namespace Sigapp\Permalinks;

use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Http\Response;

class PermalinksController
{
    public function store(Request $request, Response $response, $args)
    {
        $data = $request->getParsedBody();
        $permalink = [
            'token' => $args['token'],
            'map' => $data,
            'type' =>  isset( $data['type'] ) ? $data['type'] : 'link',
            'user_id' => USER
        ];
        PermalinksModel::create($permalink);
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withJson($permalink);
    }

    public function show(Request $request, Response $response, $args)
    {
        $permalink = PermalinksModel::where( 'token', $args['token'] )->first();
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withJson( $permalink->map );
    }

    public function destroy(Request $request, Response $response)
    {
        PermalinksModel::where( 'type', 'link' )->destroy();
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withJson( true );
    }

    public function print(Request $request, Response $response, $args)
    {
        $data = $request->getParsedBody();
        $printer = new PermalinksPrinter($data['url'], $data['jwt']);
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withJson( $printer->capture() );
    }

    public function export(Request $request, Response $response, $args)
    {
        $extent = $request->getParsedBody()['extent'];
        $dir = PATH_TO_FILES . uniqid('map_');
        mkdir($dir);
        $qgis = \Core\Qgis\Qgis::createNewProject($dir . '/map.qgs');
        $map = \Sigapp\Permalinks\PermalinksModel::where( 'token', $args['token'] )->first()['map'];
        foreach ($map['layers'] as $layer) {
            if ($layer['visible']) {
                $origin = new \Core\Qgis\Qgis( $layer['map'] );
                $qgis->addMaplayer( $origin->getMaplayerByName( $layer['name'] )->getSimpleXMLElement() );
                $maplayer = $qgis->getMaplayerByName( $layer['name'] );
                $maplayer->setOpacity( $maplayer->getOpacity() * $layer['opacity'] );
                $extract = new \Core\Datastore\Extract($maplayer, $layer['filter'], $extent);
                $extract->process()->updateDatasource();
            }
        }
        $qgis->getMapcanvas()->setExtent($extent);
        $qgis->save();
        $zip = new \PhpZip\ZipFile();
        $zip->addDirRecursive($dir)
            ->saveAsFile($dir . '.zip')
            ->close();
         ( new \Symfony\Component\Filesystem\Filesystem )->remove($dir);
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withJson( API_URL . 'files/' . pathinfo($dir . '.zip', PATHINFO_BASENAME) );
    }
}