<?php
use \Sigapp\QgisProjects\QgisProjectsController;


/**
 * @OA\Get(
 *     path="/qgis-projects",
 *     tags={"Qgis Projects"},
 *     summary="Return published QGIS Projects with the layers",
 *     @OA\Response(response="200", description="", )
 * )
 */
$app->get('/qgis-projects', QgisProjectsController::class . ":index"); //->add($isAdmin);

/**
 * @OA\Post(
 *      path="/qgis-projects",
 *      tags={"Qgis Projects"},
 *      summary="Upload and register layers from QGIS project file",
 *      @OA\RequestBody( required=true,
 *         @OA\MediaType( mediaType="multipart/form-data",
 *             @OA\Schema(
 *                 @OA\Property(property="options", description="JSON encoded options", type="string"),
 *                 @OA\Property( property="file", description="QGIS project file .qgs / .qgz / .zip", property="file", type="string", format="binary", ),
 *                 required={"file"}
 *             )
 *         )
 *     ), 
 *      @OA\Response( response="200", description="", )
 * )
 */
$app->post('/qgis-projects', QgisProjectsController::class . ":store"); //->add($isAdmin);

/**
 * @OA\Delete(
 *      path="/qgis-projects/{name}",
 *      tags={"Qgis Projects"},
 *      summary="Delete cascade a project file and its layers",
 *      @OA\Parameter(name="name", in="path", description="Project name", required=true, @OA\Schema(type="string")),
 *      @OA\Response( response="200", description="", )
 * )
 */
$app->delete('/qgis-projects/{name}', QgisProjectsController::class . ":destroy"); //->add($isAdmin);

/**
 * @OA\Post(
 *      path="/qgis-projects/{name}/previews",
 *      tags={"Qgis Projects"},
 *      summary="(Re)generate layers's preview and symbol",
 *      @OA\Parameter(name="name", in="path", description="Project name", required=true, @OA\Schema(type="integer"),),
 *      @OA\Response( response="200", description="", )
 * )
 */
$app->put('/qgis-projects/{name}/previews', QgisProjectsController::class . ":previews"); //->add($isAdmin);