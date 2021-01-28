<?php
use \Core\Qgis\Qgis;
use \Core\Qgis\Parsers\LayerParser;
use PHPUnit\Framework\TestCase;

class LayerParserTest extends TestCase
{
    protected $layer;

    protected function setUp(): void
    {
        $dir = __DIR__ . '/templates/';
        $file = $dir . 'testing.qgs';
        copy($dir . 'test-qgs.qgs', $file);
        $this->qgis = new Qgis(__DIR__ . '/templates/testing.qgs');
        $this->shp          = $this->qgis->getMaplayerByName('departements_3191b63f_3f52_4b8a_bcce_b01ed170b750');
        $this->geojson      = $this->qgis->getMaplayerByName('departements_10c88920_cafa_464c_a32a_8f738db8e349');
        $this->sqlite       = $this->qgis->getMaplayerByName('departements_6d021676_0b12_4ee9_aa5c_f226f1b2075f');
        $this->spatialite   = $this->qgis->getMaplayerByName('departements_674f58d7_93c6_453a_a0d7_4dbfc4874293');
    }

    public function testGetName()
    {
        $this->assertEquals( 'departements_3191b63f_3f52_4b8a_bcce_b01ed170b750', $this->shp->getName() );
    }

    public function testSetTitle()
    {
        $this->geojson->setTitle('GEOJSON Dpt');
        $this->assertEquals( 'GEOJSON Dpt', $this->geojson->getTitle() );
    }

    public function testSetAbstract()
    {
        $this->sqlite->setAbstract('French departments layer from SQLITE file format');
        $this->assertEquals( 'French departments layer from SQLITE file format', $this->sqlite->getAbstract() );
    }

    public function testSetSource()
    {
        $this->spatialite->setSource('OSM - 2014');
        $this->assertEquals( 'OSM - 2014', $this->spatialite->getSource() );
    }

    public function testGetProvider()
    {
        $this->assertEquals( 'ogr', $this->shp->getProvider() );
        $this->assertEquals( 'ogr', $this->geojson->getProvider() );
        $this->assertEquals( 'ogr', $this->sqlite->getProvider() );
        $this->assertEquals( 'spatialite', $this->spatialite->getProvider() );
    }

    public function testGetTablename()
    {
        $this->assertEquals( 'departements', $this->shp->getTablename() );
        $this->assertEquals( 'departements', $this->geojson->getTablename() );
        $this->assertEquals( 'departements', $this->sqlite->getTablename() );
        $this->assertEquals( 'departements', $this->spatialite->getTablename() );
    }

    public function testGetGeomcolumn()
    {
        $this->assertEquals( 'geom', $this->shp->getGeomcolumn() );
        $this->assertEquals( 'geom', $this->geojson->getGeomcolumn() );
        $this->assertEquals( 'geom', $this->sqlite->getGeomcolumn() );
        $this->assertEquals( 'geometry', $this->spatialite->getGeomcolumn() );
    }

    public function testGetFilter()
    {
        $this->assertEquals( '', $this->spatialite->getFilter() );
    }

    public function testGetDisplayfield()
    {
        $this->assertEquals( 'code_insee', $this->shp->getDisplayfield() );
        $this->assertEquals( 'code_insee', $this->geojson->getDisplayfield() );
        $this->assertEquals( 'code_insee', $this->sqlite->getDisplayfield() );
        $this->assertEquals( 'ogc_fid', $this->spatialite->getDisplayfield() );
    }

    public function testSetDisplayfield()
    {
        $this->spatialite->setDisplayfield('code_insee');
        $this->assertEquals( 'code_insee', $this->spatialite->getDisplayfield() );
    }

    public function testGetDatasourceShapefile()
    {
        $datasource = [
            'extension' => 'shp',
            'file' => './departements.shp',
            'tablename' => 'departements',
            'geomcolumn' => 'geom',
            'encoding' => 'System'
        ];
        $this->assertEquals( $datasource, $this->shp->getDatasource() );
    }

    public function testGetDatasourceGeojson()
    {
        $datasource = [
            'extension' => 'geojson',
            'file' => './departements.geojson',
            'tablename' => 'departements',
            'geomcolumn' => 'geom',
            'encoding' => 'UTF-8'
        ];
        $this->assertEquals( $datasource, $this->geojson->getDatasource() );
    }

    public function testGetDatasourceSqliteOgr()
    {
        $datasource = [
            'extension' => 'sqlite',
            'file' => './departements.sqlite',
            'tablename' => 'departements',
            'geomcolumn' => 'geom',
            'encoding' => 'UTF-8'
        ];
        $this->assertEquals( $datasource, $this->sqlite->getDatasource() );
    }


    public function testGetDatasourceSpatialite()
    {
        $datasource = [
            'dbname' => './departements.sqlite',
            'tablename' => 'departements',
            'geomcolumn' => 'geometry',
            'sql' => '',
        ];
        $this->assertEquals( $datasource, $this->spatialite->getDatasource() );
    }

    public function testGetSrid()
    {
        $this->assertEquals( 4326, $this->shp->getSrid() );
    }

    public function testSetExtent()
    {
        $extent = [
            'srid' => 3857,
            'xmin' => -572431,
            'ymin' => 5061617,
            'xmax' => 1064254,
            'ymax' => 6637201,
        ];
        $this->sqlite->setExtent($extent);
        $this->assertEquals( $extent['xmin'], intval($this->sqlite->getExtent()['xmin']) );
    }

    public function testSetOpacity()
    {
        $opacity = 0.5;
        $this->sqlite->setOpacity($opacity);
        $this->assertEquals( $opacity, $this->sqlite->getOpacity() );
    }

    public function testGetColumns()
    {
        $column_1 = [
            'name' => 'code_insee',
            'alias' => 'code_insee',
            'displayfield' => false,
            'excluded' => false,
            'unit' => '',
            'numeric' => false,
        ];
        $this->assertEquals( $column_1, $this->spatialite->getColumns()[1] );
    }

    // public function testSetColumns()
    // {
    //     $columns = $this->spatialite->getColumns();
    //     $columns[1] = [
    //         'name' => 'code_insee',
    //         'alias' => 'CODE INSEE (excluded)',
    //         'excluded' => true,
    //     ];
    //     $this->spatialite->setDisplayfield('code_insee');
    //     $this->qgis->save();
    //     $column_1 = [
    //         'name' => 'code_insee',
    //         'alias' => 'CODE INSEE (excluded)',
    //         'displayfield' => true,
    //         'excluded' => true,
    //         'unit' => '',
    //         'numeric' => false,
    //     ];
    //     $this->assertEquals( $column_1, $this->spatialite->getColumns()[1] );
    // }
}