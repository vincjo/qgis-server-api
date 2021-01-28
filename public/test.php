<?php


$app->get('/test/dump', function ($request, $response){
    $qgis = new \Core\Qgis\Qgis(PATH_TO_MAPS . 'tmp/Zip test with shapes.qgs');
    $maplayer = $qgis->getMaplayerByName('zae_surface_99d8c5f1_b42f_4de0_81b0_26054788fd4e');
    $dump = new \Core\datastore\Dump( $maplayer );
    $test = $dump->fileExists()->process();
    return $response
        // ->withHeader('Content-Type', 'application/json')
        // ->withJson( $adapter->getLayers()->getLayersByProvider('ogr') );
        ->write(print_r( $dump ));
});



$app->get('/test/file', function ($request, $response){
    $file = new \Core\File\File(PATH_TO_MAPS . 'test.zip');
    $storage = $file->getProjectfile();
    return $response
        // ->withHeader('Content-Type', 'application/json')
        // ->withJson( $adapter->getLayers()->getLayersByProvider('ogr') );
        ->write(print_r( $storage ));
});

$app->get('/test/string', function ($request, $response){
    $string = './departements.geojson|layername=departements';
    $strpos = strpos($string, 'layername');
    return $response
        // ->withHeader('Content-Type', 'application/json')
        // ->withJson( $adapter->getLayers()->getLayersByProvider('ogr') );
        ->write(var_dump( $strpos ));
});

$app->get('/test/ogr', function ($request, $response){
    $ogr = new \Sigapp\Layers\IO\Ogr( 87, 'geojson', '', [] );
    return $response
        // ->withHeader('Content-Type', 'application/json')
        // ->withJson( $adapter->getLayers()->getLayersByProvider('ogr') );
        ->write(print_r( $ogr->process() ));
});

$app->get('/test/io', function ($request, $response){
    $io = new \Sigapp\Layers\LayersIO(101, 'geojson', '', []);
    return $response
        ->withHeader('Content-Type', 'application/json')
        ->withJson( $io->extract() );
        // ->write(print_r( $io->extract() ));
});

$app->get('/test/postgis', function ($request, $response){
    $layer = \Sigapp\Layers\LayersModel::find(13);
    $postgis = new \Sigapp\layers\Providers\Postgis($layer);
    return $response
        // ->withHeader('Content-Type', 'application/json')
        // ->withJson( $adapter->getPerimeter() );
        ->write(print_r( $postgis->getFeatureInfo(3351) ));
});



$app->get('/test/migration', function ($request, $response, $args){

    $qgs = new Core\Qgis\Qgis(PATH_TO_MAPS . 'template.basemaps.qgs');
    $maplayers =  $qgs->getMaplayers();
    return $response
        ->withHeader('Content-Type', 'application/json')
        ->withJson( $maplayers[0]->get() );
        // ->write(print_r( $layer ) );
});


$app->get('/test/export-qgis/{token}',  function ($request, $response, $args){
    $dir = PATH_TO_FILES . 'test_export_qgis/';
    $qgis = Core\Qgis\Qgis::createNewProject($dir . 'export.qgs');
    $map = \Sigapp\Permalinks\PermalinksModel::where( 'token', $args['token'] )->first()['map'];
    foreach ($map['layers'] as $layer) {
        if ($layer['visible']) {
            $origin = new \Core\Qgis\Qgis( $layer['map'] );
            $qgis->addMaplayer( $origin->getMaplayerByName( $layer['name'] )->getSimpleXMLElement() );
            $maplayer = $qgis->getMaplayerByName( $layer['name'] );
            $maplayer->setOpacity( $maplayer->getOpacity() * $layer['opacity'] );
            $extract = new \Core\Datastore\Extract($maplayer, $layer['filter']);
            $extract->process()->updateDatasource();
        }
    }
    ( new Symfony\Component\Filesystem\Filesystem )->remove($dir);
    return $response
        ->withHeader('Content-Type', 'application/json')
        ->write( 'test OK ?' );
});

$app->get('/test/mapcanvas',  function ($request, $response){
    $qgis = new \Core\Qgis\Qgis(PATH_TO_MAPS . 'template.project.qgs');
    
    return $response
        ->withHeader('Content-Type', 'application/json')
        ->write( var_dump($qgis->getMapcanvas()->getSrid()) );
});