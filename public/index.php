<?php
require __DIR__ . "/../config/config.php";
require __DIR__ . "/../config/middlewares.php";


// require "./test.php";

/**
 * @OA\Info(title="QGIS Server API", version="")
 * @OA\Server(url="https://vincjo.fr/qgis-server-api", description="vincjo.fr")
 * @OA\Server(url="http://localhost/qgis-server-api/", description="localhost")
 */

/**
 * @OA\Tag(name="Qgis Projects")
 */
require __DIR__ . "/../routes/qgis-projects.php";

/** 
 * @OA\Tag(name="Layers")
 */ 
require __DIR__ . "/../routes/layers.php";

/** 
 * @OA\Tag(name="Datasources", description="Registered connections to databases")
 */
require __DIR__ . "/../routes/datasources.php";

/**
 * @OA\Tag(name="Gis", description="Set of thematic directories to organize the layers imported into the application")
 */ 
require __DIR__ . "/../routes/gis.php";

/** 
 * @OA\Tag(name="Basemaps")
 */ 
require __DIR__ . "/../routes/basemaps.php";

/** 
 * @OA\Tag(name="Maps", description="Set of layers and a basemap")
 */
require __DIR__ . "/../routes/maps.php";

/**
 * @OA\Tag(name="Permalinks", description="Permanent link to a map defined by a unique token")
 */ 
require __DIR__ . "/../routes/permalinks.php";

/**
 * @OA\Tag(name="Portals", description="A collection of maps")
 */
require __DIR__ . "/../routes/portals.php";

/**
 * @OA\Tag(name="Settings", description="General settings")
 */ 
require __DIR__ . "/../routes/settings.php";

$app->run();