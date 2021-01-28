<?php
namespace Sigapp\Permalinks;

use \HeadlessChromium\BrowserFactory;
use \HeadlessChromium\Cookies\Cookie;

class PermalinksPrinter
{
    public function __construct(string $url, string $jwt)
    {
        $this->url = $url;
        $this->filename = uniqid('MAP_' . USER . '_') . '.png';
        $this->jwt = $jwt;
    }

    public function capture() 
    {
        $browserFactory = new BrowserFactory();
        $browser = $browserFactory->createBrowser([
            'startupTimeout' => 30,
            'connectionDelay' => 0.8,
            'headless' => false,
            'sendSyncDefaultTimeout' => 80000,
        ]);
        $page = $browser->createPage();
        $page->setCookies([
            Cookie::create('jwt', $this->jwt, [
                'domain' => parse_url($this->url, PHP_URL_HOST),
                'expires' => time() + 3600 // expires in 1 day
            ])
        ])->await();
        $page->navigate( $this->url )->waitForNavigation();
        sleep(2);
        $width = $page->evaluate('document.querySelector("#map").offsetWidth')->getReturnValue();
        $height = $page->evaluate('document.querySelector("#map").offsetHeight')->getReturnValue();
        $page->setViewport($width, $height)->await();
        sleep(7);
        $page->screenshot()->saveToFile( PATH_TO_FILES . $this->filename );
        $browser->close(); 
        return 'files/' . $this->filename;
    }
}