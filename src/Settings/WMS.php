<?php
namespace Sigapp\Settings;

class WMS extends Requests
{
    public function __construct($layerName, $projectName)
    {
        $this->name = $layerName;
        $this->map = PATH_TO_MAPS . $projectName . '.qgs';
    }

    public function getLegendGraphic()
    {
        $this->settings = [
            'request'           => 'GetLegendGraphic',
            'format'            => 'image/png',
            'boxSpace'          => '0.5',
            'layerSpace'        => '0',
            'layerTitleSpace'   => '0',
            'symbolSpace'       => '1',
            'iconLabelSpace'    => '2',
            'symbolWidth'       => '9',
            'symbolHeight'      => '6',
            'layerTitle'        => 'false',
        ];
        return $this->setParams()->getRequest();
    }    

    public function getMap($params)
    {
        $this->settings = [
            'request'       => 'GetMap',
            'format'        => 'image/png',
            'transparent'   => 'true',
            'crs'           => 'EPSG:' . $params['srid'],
            'width'         => $params['width'],
            'height'        => $params['height'],
            'opacity'       => $params['opacity'],
            'bbox'          => $params['bbox'],
            'dpi'           => $params['dpi']
        ];
        return $this->setParams()->getRequest();
    }

    public function getPrint($params)
    {
        $template = $params['template'];
        $this->settings = [
            'request'             => 'GetPrint',
            'template'            => $template,
            'crs'                 => 'EPSG:' . $params['srid'],
            'format'              => 'svg',
            "$template:EXTENT"    => $params['bbox'],
            "$template:LAYERS"    => urlencode($params['layer']),
            "$template:OPACITIES" =>  250,
            "$template:ROTATION"  => 0
        ];
        return $this->setParams()->getRequest();
    }

    public function getStyles()
    {
        $this->settings = [
			'request'       => 'GetStyles',
			'sld_version'   => '1.1.0'
        ];
        return $this->setParams()->getRequest();
    }

    public function getRequest()
    {
        return QGIS_SERVER_URL . preg_replace('/[\n\r\t\s+]/', '', '
            SERVICE=WMS
            &VERSION=1.3.0
            &MAP=' . urlencode($this->map) . '
            &LAYERS=' . urlencode($this->name) . '
        ') . $this->params;
    }
}