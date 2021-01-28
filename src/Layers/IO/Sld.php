<?php
namespace Sigapp\Layers\IO;

use \Sigapp\Settings\WMS;

class Sld extends WMS
{
    public function __construct($layer_name, $project_name)
    {
        parent::__construct($layer_name, $project_name);
    }

    public function getFile()
    {
        $file = PATH_TO_FILES . $this->name . '.sld';
        $content = file_get_contents( $this->getStyles() );
        file_put_contents( $file, $content );
        return $file;
    }
}