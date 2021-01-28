<?php
use \Sigapp\Maps\MapsController;

/**
 * @OA\Get(
 *      path="/maps/{id}",
 *      tags={"Maps"},
 *      summary="Return a map",
 *      @OA\Parameter(name="id", in="path", description="Map ID", required=true, @OA\Schema(type="integer"),),
 *      @OA\Response(response="200", description="",)
 * )
 */
$app->get('/maps/{id}', MapsController::class . ":index");

/**
 * @OA\Post(
 *      path="/maps",
 *      tags={"Maps"},
 *      summary="Create a map",
 *      @OA\RequestBody(required=true,
 *          @OA\JsonContent(
 *              @OA\property(property="title", type="string", example="My map title",),
 *              @OA\property(property="abstract", type="string", example="Description of the map",),
 *              @OA\property(property="role_id", type="integer", example="4",),
 *              @OA\property(property="portal_id", type="integer", example="3",),
 *              @OA\property(property="creator", type="integer", example="34",),
 *              @OA\property(property="extent", type="object", 
 *                  @OA\property(property="xmin", type="number", example="418759.225490893",),
 *                  @OA\property(property="ymin", type="number", example="5814618.81106328",),
 *                  @OA\property(property="xmax", type="number", example="583827.679562357",),
 *                  @OA\property(property="ymax", type="number", example="5953894.33199587",),
 *                  @OA\property(property="srid", type="integer", format="int64", example="3857"),
 *              ),
 *              @OA\property(property="basemap_id", type="integer", example="5",),
 *              @OA\property(property="visible", type="boolean", example="true",),
 *              @OA\property(property="opacity", type="number", example="0.85",),
 *              @OA\property(property="overlays", type="array", @OA\Items(
 *                  @OA\property(property="index", type="integer", example="1",),
 *                  @OA\property(property="checked", type="boolean", example="false",),
 *              )),
 *              @OA\property(property="layers", type="array", @OA\Items(
 *                  @OA\property(property="layer_id", type="integer", example="63",),
 *                  @OA\property(property="opacity", type="number", example="0.57"),
 *                  @OA\property(property="visible", type="boolean", example="true"),
 *                  @OA\property(property="filter", type="string", example="length > '190'"),
 *              )),
 *          ),
 *      ),
 *      @OA\Response(response="200", description="",)
 * )
 */
$app->post('/maps', MapsController::class . ":store");

/**
 * @OA\put(
 *      path="/maps/{id}",
 *      tags={"Maps"},
 *      summary="Update a map",
 *      @OA\Parameter(name="id", in="path", description="Map ID", required=true, @OA\Schema(type="integer"),),
 *      @OA\RequestBody(required=true,
 *          @OA\JsonContent(
 *              @OA\property(property="title", type="string", example="My map title",),
 *              @OA\property(property="abstract", type="string", example="Description of the map",),
 *              @OA\property(property="role_id", type="integer", example="4",),
 *              @OA\property(property="extent", type="object", 
 *                  @OA\property(property="xmin", type="number", example="418759.225490893",),
 *                  @OA\property(property="ymin", type="number", example="5814618.81106328",),
 *                  @OA\property(property="xmax", type="number", example="583827.679562357",),
 *                  @OA\property(property="ymax", type="number", example="5953894.33199587",),
 *                  @OA\property(property="srid", type="integer", format="int64", example="3857"),
 *              ),
 *              @OA\property(property="basemap_id", type="integer", example="5",),
 *              @OA\property(property="visible", type="boolean", example="true",),
 *              @OA\property(property="opacity", type="number", example="0.85",),
 *              @OA\property(property="overlays", type="array", @OA\Items(
 *                  @OA\property(property="index", type="integer", example="1",),
 *                  @OA\property(property="checked", type="boolean", example="false",),
 *              )),
 *          ),
 *      ),
 *      @OA\Response(response="200", description="",)
 * )
 */
$app->put('/maps/{id}', MapsController::class . ":update"); 

/**
 * @OA\put(
 *      path="/maps/{id}/portal/{portal_id}",
 *      tags={"Maps"},
 *      summary="Move a map from a portal to another one",
 *      @OA\Parameter(name="id", in="path", description="Map ID", required=true, @OA\Schema(type="integer"),),
 *      @OA\Parameter(name="portal_id", in="path", description="Portal ID", required=true, @OA\Schema(type="integer"),),
 *      @OA\Response(response="200", description="",)
 * )
 */
$app->put('/maps/{id}/portal/{portal_id}', MapsController::class . ":portal"); //->add($isAdmin); 

/**
 * @OA\Put(
 *      path="/maps/reorder/from-array",
 *      tags={"Maps"},
 *      summary="Redefine order of maps within a portal",
 *      @OA\RequestBody(required=true,
 *          @OA\JsonContent( type="array", @OA\Items(type="integer")
 *      )),
 *      @OA\Response( response="200", description="", )
 * )
 */
$app->put('/maps/reorder/from-array', MapsController::class . ":reorder"); //->add($isAdmin); 

/**
 * @OA\Put(
 *      path="/maps/{id}/preview",
 *      tags={"Maps"},
 *      summary="Create a map preview",
 *      @OA\Parameter(name="id", in="path", description="Map ID", required=true, @OA\Schema(type="integer"),),
 *      @OA\RequestBody(required=true,
 *          @OA\JsonContent(
 *              @OA\property(property="url", type="string", example="http://localhost:5000/?printer=true&mapid=2",),
 *          ),
 *      ),
 *      @OA\Response(response="200", description="",)
 * )
 */
$app->put('/maps/{id}/preview', MapsController::class . ":preview");

// $app->get('/maps/{id}/layers', MapsLayersController::class . ":index"); ////////////////////////////////////////////
// $app->post('/maps/{id}/layers/{layer_id}', MapsLayersController::class . ":store"); ////////////////////////////////////////////
// $app->put('/maps/{id}/layers/{layer_id}', MapsLayersController::class . ":update"); ////////////////////////////////////////////
// $app->put('/maps/{id}/layers/{layer_id}/reorder', MapsLayersController::class . ":reorder"); ////////////////////////////////////////////////////
// $app->delete('/maps/{id}/layers/{layers_id}', MapsLayersController::class . ":destroy"); ///////////////////////////////////////////////////////////////////

/**
 * @OA\Delete(
 *      path="/maps/{id}",
 *      tags={"Maps"},
 *      summary="Delete a map",
 *      @OA\Parameter(name="id", in="path", description="Map ID", required=true, @OA\Schema(type="integer")),
 *      @OA\Response( response="200", description="", )
 * )
 */
$app->delete('/maps/{id}', MapsController::class . ":destroy"); 

/**
 * @OA\put(
 *      path="/maps/{id}/views",
 *      tags={"Maps"},
 *      summary="Increase the number of map views",
 *      @OA\Parameter(name="id", in="path", description="Map ID", required=true, @OA\Schema(type="integer"),),
 *      @OA\Response(response="200", description="",)
 * )
 */
$app->put('/maps/{id}/views', MapsController::class . ":views");

/**
 * @OA\Post(
 *      path="/maps/export/qgis",
 *      tags={"Maps"},
 *      summary="Export a map to Qgis project",
 *      @OA\RequestBody(required=true,
 *          @OA\JsonContent(
 *              @OA\property(property="title", type="string", example="My map title",),
 *              @OA\property(property="extent", type="object", 
 *                  @OA\property(property="xmin", type="number", example="418759.225490893",),
 *                  @OA\property(property="ymin", type="number", example="5814618.81106328",),
 *                  @OA\property(property="xmax", type="number", example="583827.679562357",),
 *                  @OA\property(property="ymax", type="number", example="5953894.33199587",),
 *                  @OA\property(property="srid", type="integer", format="int64", example="3857"),
 *              ),
 *              @OA\property(property="basemap_id", type="integer", example="5",),
 *              @OA\property(property="visible", type="boolean", example="true",),
 *              @OA\property(property="opacity", type="number", example="0.85",),
 *              @OA\property(property="overlays", type="array", @OA\Items(
 *                  @OA\property(property="index", type="integer", example="1",),
 *                  @OA\property(property="checked", type="boolean", example="false",),
 *              )),
 *              @OA\property(property="layers", type="array", @OA\Items(
 *                  @OA\property(property="layer_id", type="integer", example="63",),
 *                  @OA\property(property="opacity", type="number", example="0.57"),
 *                  @OA\property(property="visible", type="boolean", example="true"),
 *                  @OA\property(property="filter", type="string", example="length > '190'"),
 *              )),
 *          ),
 *      ),
 *      @OA\Response(response="200", description="",)
 * )
 */
$app->post('/maps/export/qgis', MapsController::class . ":export");