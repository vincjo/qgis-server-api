<?php
namespace Sigapp\Settings;

class Requests
{
    public function __construct($service = null)
    {
        $this->service = $service;
    }

    public function getProjectSettings()
    {
        $this->settings = [
            'request'   => 'GetProjectSettings',
            'service'   => 'WMS',
            'version'   => '1.3.0'
        ];
        return $this->setParams()->getRequest();
    }

    public function getCapabilities()
    {
        $this->settings = [
            'request'   => 'GetCapabilities',
            'service'   => 'WFS',
            'version'   => '1.1.0'
        ];
        return $this->setParams()->getRequest();
    }

    public function setParams()
    {
        $this->params = null;
        foreach ($this->settings as $setting => $value){
            $this->params .= '&' . strtoupper($setting) . '=' . $value;
        }
        return $this;
    }

    public function getRequest()
    {
        return QGIS_SERVER_URL . $this->params;
    }
}