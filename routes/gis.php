<?php
use \Sigapp\Gis\{GisController, GisFoldersController};

/**
 * @OA\Get(
 *      path="/gis",
 *      tags={"Gis"},
 *      summary="Return GIS tree - folders and layers",
 *      @OA\Response(response="200", description="",)
 * )
 */
$app->get('/gis', GisController::class . ":index");

/**
 * @OA\Get(
 *      path="/gis/search/{value}",
 *      tags={"Gis"},
 *      summary="Return layers matching with the search value",
 *      @OA\Parameter(name="value", in="path", description="searched text", required=true, @OA\Schema(type="string")),
 *      @OA\Response(response="200", description="",)
 * )
 */
$app->get('/gis/search/{value}', GisController::class . ":search");

/**
 * @OA\Get(
 *      path="/gis/folders",
 *      tags={"Gis"},
 *      summary="Returns all folder paths",
 *      @OA\Response(response="200", description="",)
 * )
 */
$app->get('/gis/folders', GisFoldersController::class . ":index");

/**
 * @OA\Post(
 *      path="/gis/folders",
 *      tags={"Gis"},
 *      summary="Create a new folder",
 *      @OA\RequestBody(required=true,
 *          @OA\JsonContent(
 *              @OA\property(property="title", type="string", example="My new folder",),
 *              @OA\property(property="role_id", format="int64", type="integer", example="4"),
 *              @OA\property(property="parent_id", format="int64", type="integer", example="0"),
 *          )
 *      ),
 *      @OA\Response(response="200", description="",)
 * )
 */
$app->post('/gis/folders', GisFoldersController::class . ":store"); //->add($isAdmin); 

/**
 * @OA\Get(
 *      path="/gis/folders/{id}",
 *      tags={"Gis"},
 *      summary="Return a folder",
 *      @OA\Parameter(name="id", in="path", description="Folder ID", required=true, @OA\Schema(type="integer"),),
 *      @OA\Response(response="200", description="",)
 * )
 */
$app->get('/gis/folders/{id}', GisFoldersController::class . ":show"); //->add($isAdmin); 

/**
 * @OA\Put(
 *      path="/gis/folders/{id}",
 *      tags={"Gis"},
 *      summary="Update a folder",
 *      @OA\Parameter(name="id", in="path", description="Folder ID", required=true, @OA\Schema(type="integer"),),
 *      @OA\RequestBody(required=true,
 *          @OA\JsonContent(
 *              @OA\property(property="title", type="string", example="Updated folder",),
 *              @OA\property(property="role_id", format="int64", type="integer", example="4"),
 *              @OA\property(property="parent_id", format="int64", type="integer", example="0"),
 *          )
 *      ),
 *      @OA\Response( response="200", description="", )
 * )
 */
$app->put('/gis/folders/{id}', GisFoldersController::class . ":update"); //->add($isAdmin); 

/**
 * @OA\Put(
 *      path="/gis/folders/reorder/from-array",
 *      tags={"Gis"},
 *      summary="Reorder folders",
 *      @OA\RequestBody(required=true,
 *          @OA\JsonContent( type="array", @OA\Items(type="integer")
 *      )),
 *      @OA\Response( response="200", description="", )
 * )
 */
$app->put('/gis/folders/reorder/from-array', GisFoldersController::class . ":reorder"); //->add($isAdmin); 

/**
 * @OA\Put(
 *      path="/gis/folders/{id}/folder/{parent_id}",
 *      tags={"Gis"},
 *      summary="Move folder into another one",
 *      @OA\Parameter(name="id", in="path", description="Folder ID", required=true, @OA\Schema(type="integer"),),
 *      @OA\Parameter(name="parent_id", in="path", description="Folder ID of the receiver", required=true, @OA\Schema(type="integer"),),
 *      @OA\Response( response="200", description="", )
 * )
 */
$app->put('/gis/folders/{id}/folder/{parent_id}', GisFoldersController::class . ":move"); //->add($isAdmin); 

/**
 * @OA\Delete(
 *      path="/gis/folders/{id}",
 *      tags={"Gis"},
 *      summary="Delete a folder",
 *      @OA\Parameter(name="id", in="path", description="Folder ID", required=true, @OA\Schema(type="integer")),
 *      @OA\Response( response="200", description="", )
 * )
 */
$app->delete('/gis/folders/{id}', GisFoldersController::class . ":destroy"); //->add($isAdmin); 