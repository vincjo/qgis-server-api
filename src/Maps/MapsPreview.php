<?php
namespace Sigapp\Maps;

use \Imagine\Imagick\Imagine;
use \Imagine\Image\{Box, Point};
use Imagine\Image\ImageInterface;
use \HeadlessChromium\BrowserFactory;
use \HeadlessChromium\Cookies\Cookie;

class MapsPreview
{
    public function __construct(string $url, int $id, string $jwt)
    {
        $this->url = $url;
        $this->file = PATH_TO_IMAGES . "map_preview_{$id}.png";
        $this->id = $id;
        $this->jwt = $jwt;
    }

    public function create()
    {
        return $this
            ->capture()
            ->resize()
            ->save();
    }

	
    public function capture() 
    {
        $browserFactory = new BrowserFactory();
        $browser = $browserFactory->createBrowser([
            'startupTimeout' => 40,
            'connectionDelay' => 1.2,
            'headless' => true,
            'sendSyncDefaultTimeout' => 60000,
            'windowSize' => [800, 600],
        ]);
        $page = $browser->createPage();
        $page->setCookies([
            Cookie::create('jwt', $this->jwt, [
                'domain' => parse_url($this->url, PHP_URL_HOST),
                'expires' => time() + 3600 // expires in 1 day
            ])
        ])->await();
        $page->navigate( $this->url )->waitForNavigation();
        sleep(7);
        $page->screenshot()->saveToFile( $this->file );
        $browser->close(); 
        return $this;
    }

    public function resize()
	{
		$imagine = new Imagine();
		$image = $imagine->open( $this->file );
		$image->resize( new Box(400, 300), ImageInterface::FILTER_LANCZOS );
		$image->save( $this->file );
		return $this;	
    }
    
	public function save()
	{
		$map = MapsModel::find( $this->id );
		$map->preview_url = API_URL . 'images/' . "map_preview_{$this->id}.png?uid=" . uniqid();
        $map->save();
		return $map->preview_url;
	}
}