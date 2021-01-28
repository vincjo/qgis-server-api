<?php
use \Core\Qgis\Qgis;
use PHPUnit\Framework\TestCase;

class QgisModelTest extends TestCase
{
    protected $qgis;

    protected function setUp(): void
    {
        $dir = __DIR__ . '/templates/';
        $file = $dir . 'testing.qgs';
        copy($dir . 'test-qgs.qgs', $file);
        $this->qgis = new Qgis(__DIR__ . '/templates/testing.qgs');
    }

    public function testGetVersion()
    {
        $this->assertEquals( '3.4.15-Madeira', $this->qgis->getVersion() );
    }

    public function testGetName()
    {
        $this->assertEquals( 'testing', $this->qgis->getName() );
    }

    public function testSetTitle()
    {
        $this->qgis->setTitle('Test Project');
        $this->assertEquals( 'Test Project', $this->qgis->getTitle() );
    }

    public function testGetCanvas()
    {
        $this->assertInstanceOf( \Core\Qgis\Parsers\CanvasParser::class, $this->qgis->getCanvas() );
    }

    public function testGetLayers()
    {
        $this->assertTrue( is_array($this->qgis->getLayers()) );
    }

    public function testGetLayerByName()
    {
        $this->assertInstanceOf( 
            \Core\Qgis\Parsers\LayerParser::class, 
            $this->qgis->getLayerByName('departements_3191b63f_3f52_4b8a_bcce_b01ed170b750') 
        );
    }

    public function testRemoveLayer()
    {
        $this->qgis->removeLayer('departements_3191b63f_3f52_4b8a_bcce_b01ed170b750');
        $this->assertTrue( is_null( $this->qgis->getLayerByName('departements_3191b63f_3f52_4b8a_bcce_b01ed170b750') ) );
    }

    public function testAddLayer()
    {
        $qgis = new Qgis(__DIR__ . '/templates/test-qgs.qgs');
        $layer = $qgis->getMaplayerByName('departements_3191b63f_3f52_4b8a_bcce_b01ed170b750');
        $this->qgis->addLayer( $layer->getSimpleXMLElement() );
        $this->assertInstanceOf( 
            \Core\Qgis\Parsers\LayerParser::class, 
            $this->qgis->getMaplayerByName('departements_3191b63f_3f52_4b8a_bcce_b01ed170b750') 
        );
    }
}