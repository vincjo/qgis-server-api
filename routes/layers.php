<?php
use \Sigapp\Layers\LayersController;

/**
 * @OA\Get(
 *      path="/layers/{id}",
 *      tags={"Layers"},
 *      summary="Return a layer",
 *      @OA\Parameter(name="id", in="path", description="Layer ID", required=true, @OA\Schema(type="integer"),),
 *      @OA\Response(response="200", description="",)
 * )
 */
$app->get('/layers/{id}', LayersController::class . ":index");

/**
 * @OA\Put(
 *      path="/layers/{id}",
 *      tags={"Layers"},
 *      summary="Update a layer",
 *      @OA\Parameter(name="id", in="path", description="Layer ID", required=true, @OA\Schema(type="integer"),),
 *      @OA\RequestBody(required=true,
 *          @OA\JsonContent(
 *              @OA\property(property="title", type="string", example="Updated layer",),
 *              @OA\property(property="abstract", type="string", example="All about my layer",),
 *              @OA\property(property="source", type="string", example="Created by me last year",),
 *              @OA\property(property="role_id", format="int64", type="integer", example="4"),
 *          )
 *      ),
 *      @OA\Response( response="200", description="", )
 * )
 */
$app->put('/layers/{id}', LayersController::class . ":update"); //->add($isAdmin); 

/**
 * @OA\Delete(
 *      path="/layers/{id}",
 *      tags={"Layers"},
 *      summary="Delete a layer",
 *      @OA\Parameter(name="id", in="path", description="Layer ID", required=true, @OA\Schema(type="integer")),
 *      @OA\Response( response="200", description="", )
 * )
 */
$app->delete('/layers/{id}', LayersController::class . ":destroy"); //->add($isAdmin); 

/**
 * @OA\Get(
 *      path="/layers/{id}/datatable",
 *      tags={"Layers"},
 *      summary="Return attribute table",
 *      @OA\Parameter(name="id", in="path", description="Layer ID", required=true, @OA\Schema(type="integer"),),
 *      @OA\Response(response="200", description="",)
 * )
 */
$app->get('/layers/{id}/datatable', LayersController::class . ":datatable");

/**
 * @OA\Get(
 *      path="/layers/{id}/datatable/{xmin}/{ymin}/{xmax}/{ymax}/{srid}",
 *      tags={"Layers"},
 *      summary="Returns layer's entities contained in the defined extent as an array",
 *      @OA\Parameter(name="id", in="path", description="Layer ID", required=true, @OA\Schema(type="integer"),),
 *      @OA\Parameter(name="xmin", in="path", description="Bounding box xmin", required=true, @OA\Schema(type="number"),),
 *      @OA\Parameter(name="ymin", in="path", description="Bounding box ymin", required=true, @OA\Schema(type="number"),),
 *      @OA\Parameter(name="xmax", in="path", description="Bounding box xmax", required=true, @OA\Schema(type="number"),),
 *      @OA\Parameter(name="ymax", in="path", description="Bounding box ymax", required=true, @OA\Schema(type="number"),),
 *      @OA\Parameter(name="srid", in="path", description="Bounding box EPSG", required=true, @OA\Schema(type="integer"),),
 *      @OA\Response(response="200", description="",)
 * )
 */
$app->get('/layers/{id}/datatable/{xmin}/{ymin}/{xmax}/{ymax}/{srid}', LayersController::class . ":datatable");

/**
 * @OA\Get(
 *      path="/layers/{id}/extent",
 *      tags={"Layers"},
 *      summary="Return layer's extent with EPSG:3857",
 *      @OA\Parameter(name="id", in="path", description="Layer ID", required=true, @OA\Schema(type="integer"),),
 *      @OA\Response(response="200", description="",)
 * )
 */
$app->get('/layers/{id}/extent', LayersController::class . ":extent");

/**
 * @OA\Put(
 *      path="/layers/{id}/extent",
 *      tags={"Layers"},
 *      summary="Update layer's extent",
 *      @OA\Parameter(name="id", in="path", description="Layer ID", required=true, @OA\Schema(type="integer"),),
 *      @OA\Response(response="200", description="",)
 * )
 */
$app->put('/layers/{id}/extent', LayersController::class . ":updateExtent"); //->add($isAdmin); 

/**
 * @OA\Get(
 *      path="/layers/{id}/extent/{identifier}",
 *      tags={"Layers"},
 *      summary="Return entity's extent with EPSG:3857",
 *      @OA\Parameter(name="id", in="path", description="Layer ID", required=true, @OA\Schema(type="integer"),),
 *      @OA\Parameter(name="identifier", in="path", description="Displayfield value of the layer entity", required=true, @OA\Schema(type="string"),),
 *      @OA\Response(response="200", description="",)
 * )
 */
$app->get('/layers/{id}/extent/{identifier}', LayersController::class . ":extent");

/**
 * @OA\Get(
 *      path="/layers/{id}/columns",
 *      tags={"Layers"},
 *      summary="Return name, alias, and settings of each column",
 *      @OA\Parameter(name="id", in="path", description="Layer ID", required=true, @OA\Schema(type="integer"),),
 *      @OA\Response(response="200", description="",)
 * )
 */
$app->get('/layers/{id}/columns', LayersController::class . ":columns");

/**
 * @OA\Put(
 *      path="/layers/{id}/columns",
 *      tags={"Layers"},
 *      summary="Update layer's columns",
 *      @OA\Parameter(name="id", in="path", description="Layer ID", required=true, @OA\Schema(type="integer"),),
 *      @OA\RequestBody(required=true,
 *          @OA\JsonContent( type="array", @OA\Items(
 *              @OA\property(property="name", type="string", example="length",),
 *              @OA\property(property="alias", type="string", example="Length",),
 *              @OA\property(property="excluded", type="boolean", example="false",),
 *              @OA\property(property="unit", type="string", example="km",),
 *              @OA\property(property="numeric", type="boolean", example="true"),
 *              @OA\property(property="displayfield", type="boolean", example="false"),
 *          )
 *      )),
 *      @OA\Response( response="200", description="", )
 * )
 */
$app->put('/layers/{id}/columns', LayersController::class . ":updateColumns"); //->add($isAdmin); 

/**
 * @OA\Get(
 *      path="/layers/{id}/values/{attribute}",
 *      tags={"Layers"},
 *      summary="Return distinct values of an attribute",
 *      @OA\Parameter(name="id", in="path", description="Layer ID", required=true, @OA\Schema(type="integer"),),
 *      @OA\Parameter(name="attribute", in="path", description="Attribute name", required=true, @OA\Schema(type="string"),),
 *      @OA\Response(response="200", description="",)
 * )
 */
$app->get('/layers/{id}/values/{attribute}', LayersController::class . ":values");

/**
 * @OA\Post(
 *      path="/layers/{id}/export/{format}",
 *      tags={"Layers"},
 *      summary="Return a file in the defined format",
 *      @OA\Parameter(name="id", in="path", description="Layer ID", required=true, @OA\Schema(type="integer"),),
 *      @OA\Parameter(name="format", in="path", description="File format ('xlsx', 'geojson' or 'shp')", required=true, @OA\Schema(type="string"),),
 *      @OA\RequestBody(required=false,
 *          @OA\JsonContent(
 *              @OA\property(property="extent", type="object", 
 *                  @OA\property(property="xmin", type="number", example="418759.225490893",),
 *                  @OA\property(property="ymin", type="number", example="5814618.81106328",),
 *                  @OA\property(property="xmax", type="number", example="583827.679562357",),
 *                  @OA\property(property="ymax", type="number", example="5953894.33199587",),
 *                  @OA\property(property="srid", type="integer", format="int64", example="3857"),
 *              ),
 *          )
 *      ),
 *      @OA\Response(response="200", description="",)
 * )
 */
$app->post('/layers/{id}/export/{format}', LayersController::class . ":export");

/**
 * @OA\Get(
 *      path="/layers/{id}/featureinfo/{identifier}",
 *      tags={"Layers"},
 *      summary="Return an entity as geojson",
 *      @OA\Parameter(name="id", in="path", description="Layer ID", required=true, @OA\Schema(type="integer"),),
 *      @OA\Parameter(name="identifier", in="path", description="Displayfield value of the layer entity", required=true, @OA\Schema(type="string"),),
 *      @OA\Response(response="200", description="",)
 * )
 */
$app->get('/layers/{id}/featureinfo/{identifier}', LayersController::class . ":featureinfo");

/**
 * @OA\Get(
 *      path="/layers/{id}/featureinfo/{x}/{y}/{zoom}",
 *      tags={"Layers"},
 *      summary="Return an entity as geojson matching with the position on the map",
 *      @OA\Parameter(name="id", in="path", description="Layer ID", required=true, @OA\Schema(type="integer"),),
 *      @OA\Parameter(name="x", in="path", description="X coordinate with EPSG:3857", required=true, @OA\Schema(type="number"),),
 *      @OA\Parameter(name="y", in="path", description="Y coordinate with EPSG:3857", required=true, @OA\Schema(type="number"),),
 *      @OA\Parameter(name="zoom", in="path", description="Zoom level", required=true, @OA\Schema(type="number"),),
 *      @OA\Response(response="200", description="",)
 * )
 */
$app->get('/layers/{id}/featureinfo/{x}/{y}/{zoom}', LayersController::class . ":featureinfo");

/**
 * @OA\Put(
 *      path="/layers/{id}/folder/{folder_id}",
 *      tags={"Layers"},
 *      summary="Move a layer from a folder to another one",
 *      @OA\Parameter(name="id", in="path", description="Layer ID", required=true, @OA\Schema(type="integer"),),
 *      @OA\Parameter(name="folder_id", in="path", description="folder ID", required=true, @OA\Schema(type="integer"),),
 *      @OA\Response( response="200", description="", )
 * )
 */
$app->put('/layers/{id}/folder/{folder_id}', LayersController::class . ":folder"); //->add($isAdmin); 

/**
 * @OA\Put(
 *      path="/layers/{id}/project/{project_name}",
 *      tags={"Layers"},
 *      summary="Move a layer from a Qgis project file to another one",
 *      @OA\Parameter(name="id", in="path", description="Layer ID", required=true, @OA\Schema(type="integer"),),
 *      @OA\Parameter(name="project_name", in="path", description="Qgis project name", required=true, @OA\Schema(type="string"),),
 *      @OA\Response( response="200", description="", )
 * )
 */
$app->put('/layers/{id}/project/{project_name}', LayersController::class . ":project"); //->add($isAdmin); 

/**
 * @OA\Put(
 *      path="/layers/{id}/previews",
 *      tags={"Layers"},
 *      summary="Create layer's preview and symbol",
 *      @OA\Parameter(name="id", in="path", description="Layer ID", required=true, @OA\Schema(type="integer"),),
 *      @OA\Response( response="200", description="", )
 * )
 */
$app->put('/layers/{id}/previews', LayersController::class . ":previews"); //->add($isAdmin); 



/**
 * @OA\Put(
 *      path="/layers/{id}/replace/{layer_id}",
 *      tags={"Layers"},
 *      summary="Update a layer from another layer, keeping the identifier and indexing",
 *      @OA\Parameter(name="id", in="path", description="Layer ID", required=true, @OA\Schema(type="integer"),),
 *      @OA\Parameter(name="layer_id", in="path", description="Replacement layer ID", required=true, @OA\Schema(type="integer"),),
 *      @OA\Response( response="200", description="", )
 * )
 */
$app->put('/layers/{id}/replace/{layer_id}', LayersController::class . ":replace"); //->add($isAdmin); 

