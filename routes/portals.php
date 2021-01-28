<?php
use \Sigapp\Portals\PortalsController;

/**
 * @OA\Get(
 *      path="/portals",
 *      tags={"Portals"},
 *      summary="Return portals and maps",
 *      @OA\Response(response="200",description="",)
 * )
 */    
$app->get('/portals', PortalsController::class . ":index");

/**
 * @OA\Post(
 *      path="/portals",
 *      tags={"Portals"},
 *      summary="Create a new portal",
 *      @OA\RequestBody(required=true,
 *          @OA\JsonContent(
 *              @OA\property(property="title", type="string", example="My new portal",),
 *              @OA\property(property="role_id", format="int64", type="integer", example="4"),
 *          )
 *      ),
 *      @OA\Response( response="200", description="", )
 * )
 */
$app->post('/portals', PortalsController::class . ":store"); //->add($isAdmin);

/**
 * @OA\Get(
 *      path="/portals/{id}",
 *      tags={"Portals"},
 *      summary="Return a portal with its maps",
 *      @OA\Parameter(name="id", in="path", description="Portal ID", required=true,@OA\Schema(type="integer"),),
 *      @OA\Response(
 *          response="200",
 *          description="",
 *      )
 * )
 */ 
$app->get('/portals/{id}', PortalsController::class . ":show"); //->add($isAdmin);

/**
 * @OA\Put(
 *      path="/portals/{id}",
 *      tags={"Portals"},
 *      summary="Update portal",
 *      @OA\Parameter(name="id", in="path", description="Portal ID", required=true, @OA\Schema(type="integer")),
 *      @OA\RequestBody(required=true,
 *          @OA\JsonContent(
 *              @OA\property(property="title", type="string", example="new title",),
 *              @OA\property(property="role_id", format="int64", type="integer", example="4"),
 *          )
 *      ),
 *      @OA\Response( response="200", description="", )
 * )
 */
$app->put('/portals/{id}', PortalsController::class . ":update"); //->add($isAdmin);

/**
 * @OA\Put(
 *      path="/portals/reorder/from-array",
 *      tags={"Portals"},
 *      summary="Reorder portals",
 *      @OA\Parameter(name="id", in="path", description="Portal ID", required=true, @OA\Schema(type="integer")),
 *      @OA\Parameter(name="from", in="path", description="initial order", required=true, @OA\Schema(type="integer")),
 *      @OA\Parameter(name="to", in="path", description="target order", required=true, @OA\Schema(type="integer")),
 *      @OA\Response( response="200", description="", )
 * )
 */
$app->put('/portals/reorder/from-array', PortalsController::class . ":reorder"); //->add($isAdmin);

/**
 * @OA\Put(
 *      path="/portals/{id}/owner",
 *      tags={"Portals"},
 *      summary="Define the user portal",
 *      @OA\Parameter(name="id", in="path", description="Portal ID", required=true, @OA\Schema(type="integer")),
 *      @OA\Response( response="200", description="", )
 * )
 */
$app->put('/portals/{id}/owner', PortalsController::class . ":owner"); //->add($isAdmin);

/**
 * @OA\Delete(
 *      path="/portals/{id}",
 *      tags={"Portals"},
 *      summary="Delete a portal",
 *      @OA\Parameter(name="id", in="path", description="Portal ID", required=true, @OA\Schema(type="integer")),
 *      @OA\Response( response="200", description="", )
 * )
 */
$app->delete('/portals/{id}', PortalsController::class . ":destroy"); //->add($isAdmin);