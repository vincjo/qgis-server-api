<?php
use \Sigapp\Datasources\DatasourcesController;

/**
 * @OA\Get(
 *     path="/datasources",
 *     tags={"Datasources"},
 *     summary="Return datasources",
 *     @OA\Response(response="200", description="",)
 * )
 */
$app->get('/datasources', DatasourcesController::class . ":index"); //->add($isAdmin); 

/**
 * @OA\Post(
 *      path="/datasources",
 *      tags={"Datasources"},
 *      summary="Save a new DB connection",
 *      @OA\RequestBody(required=true,
 *          @OA\JsonContent(
 *              @OA\property(property="provider", type="string", example="postgres",),
 *              @OA\property(property="host", type="string", example="localhost",),
 *              @OA\property(property="port", type="string", example="5432",),
 *              @OA\property(property="dbname", type="string", example="MYDB",),
 *              @OA\property(property="user", type="string", example="postgres",),
 *              @OA\property(property="password", type="string", example="1234",),
 *          )
 *      ),
 *      @OA\Response( response="200", description="", )
 * )
 */
$app->post('/datasources', DatasourcesController::class . ":store"); //->add($isAdmin); 

/**
 * @OA\Post(
 *      path="/datasources/{id}/connection-test",
 *      tags={"Datasources"},
 *      summary="Connection test",
 *      @OA\Parameter(name="id", in="path", description="Datasource ID", required=true, @OA\Schema(type="integer"),),
 *      @OA\RequestBody(required=true,
 *          @OA\JsonContent(
 *              @OA\property(property="user", type="string", example="postgres",),
 *              @OA\property(property="password", type="string", example="1234",),
 *          )
 *      ),
 *      @OA\Response( response="200", description="", )
 * )
 */
$app->post('/datasources/{id}/connection-test', DatasourcesController::class . ":connection"); //->add($isAdmin); 

/**
 * @OA\Put(
 *      path="/datasources/{id}",
 *      tags={"Datasources"},
 *      summary="Save connection settings",
 *      @OA\Parameter(name="id", in="path", description="Datasource ID", required=true, @OA\Schema(type="integer"),),
 *      @OA\RequestBody(required=true,
 *          @OA\JsonContent(
 *              @OA\property(property="user", type="string", example="postgres",),
 *              @OA\property(property="password", type="string", example="1234",),
 *          )
 *      ),
 *      @OA\Response( response="200", description="", )
 * )
 */
$app->put('/datasources/{id}', DatasourcesController::class . ":update"); //->add($isAdmin);

/**
 *  Delete(
 *      path="/datasources/{id}",
 *      tags={"Datasources"},
 *      summary="Delete a datasource setting",
 *      @OA\Parameter(name="id", in="path", description="Datasource ID", required=true, @OA\Schema(type="integer"),),
 *      Response( response="200", description="", )
 * )
 */
$app->delete('/datasources/{id}', DatasourcesController::class . ":destroy"); //->add($isAdmin); 