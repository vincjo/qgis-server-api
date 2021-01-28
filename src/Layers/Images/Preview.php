<?php
namespace Sigapp\Layers\Images;

use \Sigapp\Layers\LayersEntity;
use \Sigapp\Settings\WMSCapture;

class Preview
{
	public function __construct(LayersEntity $entity) {
		$this->layer = $entity->layer;
		$this->file = 'layer_preview_' . $this->layer->id . '.png';
	}

	public function create()
	{
		return $this
			->capture()
			->save();
	}

	public function capture()
	{
		$capture = new WMSCapture($this->layer->name, $this->layer->project_name);
		$capture->setOptions([
			'extent' => $this->layer->extent,
			'width'	 => 400,
			'height' => 300,		
			'expand' => 0.09	
		]);
		rename($capture->get(), PATH_TO_IMAGES . $this->file);
		return $this;
	}

	public function save()
	{
		$this->layer->preview_url = API_URL . 'images/' . $this->file . '?uid=' . uniqid();
		$this->layer->save();
		return $this->layer->preview_url;
	}

}