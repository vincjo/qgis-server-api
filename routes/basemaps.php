<?php
use \Sigapp\Basemaps\BasemapsController;

/**
 * @OA\Get(
 *     path="/basemaps",
 *     tags={"Basemaps"},
 *     summary="Return basemaps's collections : 'displayed', 'stored' and 'overlayed'",
 *     @OA\Response(response="200", description="",)
 * )
 */
$app->get('/basemaps', BasemapsController::class . ":index");

/**
 * @OA\Get(
 *      path="/basemaps/{collection}",
 *      tags={"Basemaps"},
 *      summary="Return basemaps's collection",
 *      @OA\Parameter(name="collection", in="path", description="'displayed', 'stored', 'overlayed'", required=true, @OA\Schema(type="string"),),
 *      @OA\Response(response="200", description="",)
 * )
 */
$app->get('/basemaps/{collection}', BasemapsController::class . ":show"); //->add($isAdmin); 

/**
 * @OA\Put(
 *      path="/basemaps/{id}",
 *      tags={"Basemaps"},
 *      summary="Update a basemap",
 *      @OA\RequestBody(required=true,
 *          @OA\JsonContent(
 *              @OA\property(property="title", type="string", example="Updated basemap",),
 *              @OA\property(property="attributions", type="string", example="HTML link to my provider",),
 *          )
 *      ),
 *      @OA\Response( response="200", description="", )
 * )
 */
$app->put('/basemaps/{id}', BasemapsController::class . ":update"); //->add($isAdmin); 

/**
 * @OA\Put(
 *      path="/basemaps/{id}/collection/{collection}",
 *      tags={"Basemaps"},
 *      summary="Move a basemap from a collection to another one",
 *      @OA\Parameter(name="id", in="path", description="Basemap ID", required=true, @OA\Schema(type="integer"),),
 *      @OA\Parameter(name="collection", in="path", description="'displayed', 'stored', 'overlayed'", required=true, @OA\Schema(type="string"),),
 *      @OA\Response( response="200", description="", )
 * )
 */
$app->put('/basemaps/{id}/collection/{collection}', BasemapsController::class . ":collection"); //->add($isAdmin); 

/**
 * @OA\Put(
 *      path="/basemaps/reorder/from-array",
 *      tags={"Basemaps"},
 *      summary="Redefines basemaps's order within a collection",
 *      @OA\RequestBody(required=true,
 *          @OA\JsonContent( type="array", @OA\Items(type="integer")
 *      )),
 *      @OA\Response( response="200", description="", )
 * )
 */
$app->put('/basemaps/reorder/from-array', BasemapsController::class . ":reorder"); //->add($isAdmin); 

/**
 * @OA\Put(
 *      path="/basemaps/{id}/main",
 *      tags={"Basemaps"},
 *      summary="Set the default basemap",
 *      @OA\Parameter(name="id", in="path", description="Basemap ID", required=true, @OA\Schema(type="integer")),
 *      @OA\Response( response="200", description="", )
 * )
 */
$app->put('/basemaps/{id}/main', BasemapsController::class . ":main"); //->add($isAdmin); 

/**
 * @OA\Put(
 *      path="/basemaps/{id}/preview",
 *      tags={"Basemaps"},
 *      summary="Create basemap's preview from a base64 PNG",
 *      @OA\Parameter(name="id", in="path", description="Basemap ID", required=true, @OA\Schema(type="integer"),),
 *      @OA\RequestBody(required=true, @OA\MediaType(
 *          mediaType="text/plain", @OA\Schema(type="string", description="Base64 PNG created from browser",)
 *      )),
 *      @OA\Response( response="200", description="", )
 * )
 */
$app->put('/basemaps/{id}/preview', BasemapsController::class . ":preview"); //->add($isAdmin); 

/**
 * @OA\Put(
 *      path="/basemaps/{id}/overlays",
 *      tags={"Basemaps"},
 *      summary="Add an overlay over the basemap",
 *      @OA\Parameter(name="id", in="path", description="Basemap ID", required=true, @OA\Schema(type="integer"),),
 *      @OA\RequestBody(required=true,
 *          @OA\JsonContent(
 *              @OA\property(property="title", type="string", example="Place names",),
 *              @OA\property(property="icon", type="string", example="location",),
 *              @OA\property(property="basemap_id", format="int64", type="integer", example="6"),
 *          )
 *      ),
 *      @OA\Response( response="200", description="Return overlay", )
 * )
 */
$app->put('/basemaps/{id}/overlays', BasemapsController::class . ":overlays"); //->add($isAdmin); 

/**
 * @OA\Delete(
 *      path="/basemaps/{id}/overlays/{index}",
 *      tags={"Basemaps"},
 *      summary="Delete an overlay",
 *      @OA\Parameter(name="id", in="path", description="Basemap ID", required=true, @OA\Schema(type="integer")),
 *      @OA\Parameter(name="index", in="path", description="Overlay index", required=true, @OA\Schema(type="integer")),
 *      @OA\Response( response="200", description="", )
 * )
 */
$app->delete('/basemaps/{id}/overlays/{index}', BasemapsController::class . ":destroyOverlay"); //->add($isAdmin); 

/**
 * @OA\Delete(
 *      path="/basemaps/{id}",
 *      tags={"Basemaps"},
 *      summary="Delete a basemap",
 *      @OA\Parameter(name="id", in="path", description="Basemap ID", required=true, @OA\Schema(type="integer")),
 *      @OA\Response( response="200", description="", )
 * )
 */
$app->delete('/basemaps/{id}', BasemapsController::class . ":destroy"); //->add($isAdmin); 