<?php
namespace Sigapp\QgisProjects;

use \Core\File\File;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Http\Response;

class QgisProjectsController
{
    public function index(Request $request, Response $response)
    {
        $projects = QgisProjectsModel::orderBy('title')->with('layers')->get();
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withJson($projects);
    }

    public function store(Request $request, Response $response)
    {
        $options = (array) json_decode( $request->getParsedBody()['options'] );
        $uploadedFile = $request->getUploadedFiles()['file'];
        if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
            $file = new File;
            $file->moveUploadedFile($uploadedFile)
                 ->getProjectfile();
            $qgis = new QgisProjectsMigration($file, $options);
            $qgis->store();
            $project = QgisProjectsModel::where('name', $qgis->name)->withCount('layers')->first();
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withJson( $project );
        } else {
            return $response
                ->withStatus(500)
                ->withHeader('Content-Type', 'application/json')
                ->withJson( $uploadedFile->getError() );
        }
    }

    public function destroy(Request $request, Response $response, $args)
    {
        $project = QgisProjectsModel::where('name', $args['name'] );
        \Sigapp\Layers\LayersEntity::deleteByProjectName( $args['name'] );
        unlink(PATH_TO_MAPS . $args['name'] . '.qgs');
        QgisProjectsModel::where('name', $args['name'] )->delete();
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withJson($project);
    }  

    public function previews(Request $request, Response $response, $args)
    {
        $project = QgisProjectsModel::where('name', $args['name'] )->first();
        foreach ($project->layers as $layer) {
            $thumbnails[] = ( new \Sigapp\Layers\LayersEntity($layer['id']) )->updatePreviews();
        }
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withJson($thumbnails);
    }
}