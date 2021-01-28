<?php
use \Core\Qgis\Qgis;
use PHPUnit\Framework\TestCase;

class QgisAdapterTest extends TestCase
{
    protected $qgis;

    protected function setUp(): void
    {
        $this->qgis = new Qgis(__DIR__ . '/templates/test-dump/test-qgs.qgs');
    }

    public function testGetQgisProject()
    {
        $this->assertEquals( 'test-qgs', $this->qgis->get()['name'] );
    }

    public function testGetLayersDumpFileExists()
    {
        foreach ( $this->qgis->getLayersDump() as $layer ) {
            $this->assertTrue( file_exists( $layer['file'] ) );
        }
    }

    public function testGetLayersDumpFirstResult()
    {
        $layerDump = $this->qgis->getLayersDump()[0];
        unset($layerDump['file'], $layerDump['projectfile'], $layerDump['tablename']);
        $result = [
            'project_name'      => 'test-qgs',
            'layer_name'        => 'departements_10c88920_cafa_464c_a32a_8f738db8e349',
            'tablename_origin'  => 'departements',
            'geomtype'          => 'POLYGON',
            'key'               => 'code_insee',
        ];
        $this->assertEquals( $result, $layerDump );
    }
}