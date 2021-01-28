<?php
namespace Sigapp\Settings;

use \Imagine\Gd\Imagine;
use \Imagine\Image\Palette\RGB;
use \Imagine\Image\{Box, Point};
use \Core\Geoprocessing\Extent;

class WMSCapture extends WMS
{
    public function __construct($layer_name, $project_name, $external_wms = null)
    {
        parent::__construct($layer_name, $project_name, $external_wms);
        $this->file = PATH_TO_IMAGES . uniqid('tmp') . '.png';
    }

    public function setOptions(array $options) 
    {
        $expand = ( isset($options['expand']) ) ? $options['expand'] : 0;
        $this->options = $this->scale(
            $options['extent'],
            $options['width'],
            $options['height'],
            $expand
        );
        $this->options['dpi'] =  ( isset($options['dpi']) ) ? $options['dpi'] : 72;
        $this->options['opacity'] =  ( isset($options['opacity']) ) ? $options['opacity'] : 255;
        return $this;
    }

    public function get()
    {
        return $this
            ->getDefaultContent()
            ->resize();
    }

	private function getDefaultContent()
	{
		$request = $this->getMap([
            'srid' 		=> $this->options['srid'],
            'width'		=> $this->options['map_width'],
            'height' 	=> $this->options['map_height'],
            'opacity' 	=> $this->options['opacity'],
            'bbox' 		=> $this->options['bbox'],
            'dpi' 		=> $this->options['dpi'],
		]);
		file_put_contents( $this->file, file_get_contents($request) );
		return $this;
	}

	private function resize()
	{
		$imagine = new Imagine();
		$canvas = $imagine->create(
			new Box( $this->options['img_width'], $this->options['img_height'] ),		// size
			( new RGB())->color( '#ffffff', 0 )	// palette->color
		);
		$canvas->paste(
			$imagine->open( $this->file ), 
			new Point( $this->options['shift_x'], $this->options['shift_y'] )
		);
		$canvas->save( $this->file );	
		return $this->file;
    }
    
    private function scale(array $extent, $img_width, $img_height, $expand)
    {
        $srid = $extent['srid'];
        $extent = new Extent($extent);
        $perimeter = $extent->expand($expand)->getPerimeter();
        $ratio = $img_width / $img_height;
        if ($perimeter['ratio'] > $ratio) {
            $map_height = intval($perimeter['length'] * $img_width / $perimeter['width']);
            $map_width = $img_width;
            $shift_x = 0;
            $shift_y = ($img_height - $map_height) / 2;
        } else {
            $map_height = $img_height;
            $map_width = intval($perimeter['width'] * $img_height / $perimeter['length']);
            $shift_x = ($img_width - $map_width) / 2;
            $shift_y = 0;
        }
        return [
            'img_width'     => $img_width,
            'img_height'    => $img_height,
            'map_width'     => $map_width,
            'map_height'    => $map_height,
            'shift_x'       => $shift_x,
            'shift_y'       => $shift_y,
            'bbox'          => $perimeter['bbox'],
            'srid'          => $srid,
        ];
    }

}