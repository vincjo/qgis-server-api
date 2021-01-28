<?php
namespace Sigapp\Settings;

use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Http\Response;

class SettingsController
{
    public function index(Request $request, Response $response)
    {
        $settings = SettingsModel::find('application');
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withJson( $settings->value );
    }

    public function update(Request $request, Response $response)
    {
        $data = $request->getParsedBody();
        $settings = SettingsModel::find('application');
        $settings->value = array_merge($settings->value, $data);
        $settings->save();
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withJson( $settings->value );
    }

    public function logo(Request $request, Response $response)
    {
        $uploadedFile = $request->getUploadedFiles()['file'];
        if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
            $uploadedBasename = $uploadedFile->getClientFilename();
            $logo = 'logo-custom.' . pathinfo($uploadedBasename, PATHINFO_EXTENSION);
            $uploadedFile->moveTo(PATH_TO_IMAGES . $logo);
            $settings = SettingsModel::find('application');
            $settings->value = array_merge($settings->value, ['logo' => API_URL . 'images/' . $logo . '?uid=' . uniqid()] );
            $settings->save();
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withJson( $settings->value );
        } else {
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(401)
                ->withJson( $uploadedFile->getError() );
        }
    }

    public function overview(Request $request, Response $response, $args)
    {
        $layer = \Sigapp\Layers\LayersModel::find( $args['layer_id'] );
        $data = [
            'map' => $layer->map,
            'layername' => $layer->name,
            'extent' => $layer->extent,
        ];
        $settings = SettingsModel::find('application');
        $settings->value = array_merge($settings->value, $data);
        $settings->save();
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withJson( $settings->value );
    }
}