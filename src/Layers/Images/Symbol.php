<?php
namespace Sigapp\Layers\Images;

use \Imagine\Imagick\Imagine;
use \Imagine\Image\{Box, Point};
use Imagine\Image\ImageInterface;
use \Sigapp\Layers\LayersEntity;
use \Sigapp\Settings\WMS;

class Symbol extends WMS
{
	public function __construct(LayersEntity $entity){
		$this->layer = $entity->layer;
		parent::__construct($this->layer->name, $this->layer->project_name);
		$this->file = 'layer_symbol_' . $this->layer->id . '.png';
	}

	public function create()
	{
		return $this
			->getDefaultContent()
			->resize()
			->save();
	}

	public function getDefaultContent()
	{
		$request = $this->getLegendGraphic();
		file_put_contents( PATH_TO_IMAGES . $this->file, file_get_contents($request) );
		return $this;
	}

	public function resize()
	{
		$dimensions = getimagesize( PATH_TO_IMAGES . $this->file );
		$width = intval($dimensions[0]);
		$height = intval($dimensions[1]);
		$this->type = ($height < 50) ? 'single' : 'categorized';
		$imagine = new Imagine();
		$image = $imagine->open( PATH_TO_IMAGES . $this->file );
		$image
			->resize( new Box($width, $height) )
			->crop( new Point(0, 11), new Box($width, $height - 11) );
		if ($this->type === 'categorized') {
			$image->resize( new Box($width *0.7, $height *0.7), ImageInterface::FILTER_LANCZOS );
		}	
		$image->save( PATH_TO_IMAGES . $this->file , ['png_compression_level' => 2]);
		return $this;	
	}

	public function save()
	{
		$this->layer->symbol_url = API_URL . 'images/' . $this->file;
		$this->layer->symbol_type = $this->type;
		$this->layer->save();
		return $this->layer->symbol_url;
	}
}