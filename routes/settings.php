<?php
use \Sigapp\Settings\SettingsController;

/**
 * @OA\Get(
 *      path="/settings",
 *      tags={"Settings"},
 *      summary="Return settings",
 *      @OA\Response(response="200", description="",)
 * )
 */ 
$app->get('/settings', SettingsController::class . ":index"); 

/**
 * @OA\Put(
 *      path="/settings",
 *      tags={"Settings"},
 *      summary="Update settings",
 *      @OA\RequestBody(required=true,
 *          @OA\JsonContent(
 *              @OA\property(property="name", type="string", example="Sigapp",),
 *          )
 *      ),
 *      @OA\Response(response="200", description="",)
 * )
 */ 
$app->put('/settings', SettingsController::class . ":update"); //->add($isAdmin);

/**
 * @OA\Post(
 *      path="/settings/logo",
 *      tags={"Settings"},
 *      summary="Update logo",
 *      @OA\RequestBody(required=true,
 *         @OA\MediaType( mediaType="multipart/form-data",
 *             @OA\Schema(
 *                 @OA\Property( property="file", description="Image file .png / .svg / .jpg", property="file", type="string", format="binary", ),
 *                 required={"file"}
 *             )
 *         )
 *      ),
 *      @OA\Response(response="200", description="",)
 * )
 */ 
$app->post('/settings/logo', SettingsController::class . ":logo"); //->add($isAdmin);

/**
 * @OA\Put(
 *      path="/settings/overview/{layer_id}",
 *      tags={"Settings"},
 *      summary="Set overview map and default extent from a reference layer",
 *      @OA\Parameter(name="layer_id", in="path", description="Layer ID", required=true, @OA\Schema(type="integer")),
 *      @OA\Response(response="200", description="",)
 * )
 */ 
$app->put('/settings/overview/{layer_id}', SettingsController::class . ":overview"); //->add($isAdmin);