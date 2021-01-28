<?php
use \Sigapp\Permalinks\PermalinksController;

/**
 * @OA\Post(
 *      path="/permalinks/{token}",
 *      tags={"Permalinks"},
 *      summary="Save a map as a permalink",
 *      @OA\Parameter(name="token", in="path", description="Permlink token", required=true, @OA\Schema(type="string")),
 *      @OA\RequestBody(required=true,
 *          @OA\JsonContent(
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
 *                  @OA\property(property="checked", type="boolean", example="false",),
 *              )),
 *              @OA\property(property="layers", type="array", @OA\Items(
 *                  @OA\property(property="layer_id", type="integer", example="45",),
 *                  @OA\property(property="opacity", type="number", example="0.57"),
 *                  @OA\property(property="visible", type="boolean", example="true"),
 *                  @OA\property(property="filter", type="string", example="length > '190'"),
 *                  @OA\property(property="name", type="string", example="layer_name"),
 *                  @OA\property(property="title", type="string", example="Layer Name"),
 *                  @OA\property(property="alias", type="string", example="Alias Name",),
 *                  @OA\property(property="map", type="string", example="C:\server\www\sigapp\api/maps/example.qgs"),
 *                  @OA\property(property="symbol_url", type="string", example="http://localhost/sigapp.api/images/layer_symbol_45.png"),
 *                  @OA\property(property="symbol_type", type="string", example="categorized"),
 *                  @OA\property(property="preview_url", type="string", example="http://localhost/sigapp.api/images/layer_preview_45.png"),
 *                  @OA\property(property="project_name", type="string", example="example"),
 *              )),
 *          ),
 *      ),
 *      @OA\Response( response="200", description="Return permalink token", )
 * )
 */
$app->post('/permalinks/{token}', PermalinksController::class . ":store");

/**
 * @OA\Get(
 *      path="/permalinks/{token}",
 *      tags={"Permalinks"},
 *      summary="Return a map",
 *      @OA\Parameter(name="token", in="path", description="Permlink token", required=true, @OA\Schema(type="string")),
 *      @OA\Response(response="200",description="",)
 * )
 */    
$app->get('/permalinks/{token}', PermalinksController::class . ":show");

/**
 * @OA\Post(
 *      path="/permalinks/{token}/print",
 *      tags={"Permalinks"},
 *      summary="Print a map",
 *      @OA\Parameter(name="token", in="path", description="Permlink token", required=true, @OA\Schema(type="string")),
 *      @OA\RequestBody(required=true,
 *          @OA\JsonContent(
 *              @OA\property(property="url", type="string", example="http://localhost/sigapp/?printer=true&maptoken=Oek53sb9",),
 *          ),
 *      ),
 *      @OA\Response(response="200",description="",)
 * )
 */    
$app->post('/permalinks/{token}/print', PermalinksController::class . ":print");

/**
 * @OA\Post(
 *      path="/permalinks/{token}/export",
 *      tags={"Permalinks"},
 *      summary="Print a map",
 *      @OA\Parameter(name="token", in="path", description="Permlink token", required=true, @OA\Schema(type="string")),
 *      @OA\RequestBody(required=true,
 *          @OA\JsonContent(
 *              @OA\property(property="extent", type="object", 
 *                  @OA\property(property="xmin", type="number", example="418759.225490893",),
 *                  @OA\property(property="ymin", type="number", example="5814618.81106328",),
 *                  @OA\property(property="xmax", type="number", example="583827.679562357",),
 *                  @OA\property(property="ymax", type="number", example="5953894.33199587",),
 *                  @OA\property(property="srid", type="integer", format="int64", example="3857"),
 *              ),
 *          ),
 *      ),
 *      @OA\Response(response="200",description="",)
 * )
 */    
$app->post('/permalinks/{token}/export', PermalinksController::class . ":export");