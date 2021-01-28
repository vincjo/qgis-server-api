<?php
namespace Core\Datastore;

use Symfony\Component\Finder\Finder;
use Cocur\Slugify\Slugify;

abstract class AbstractDump
{
    protected $maplayer;

    public function getProjectname(): string
    {
        return $this->maplayer->project['name'];
    }

    public function getLayername(): string
    {
        return $this->maplayer->getName();
    }

    public function getTablename(): string
    {
        $slugify = new Slugify(['separator' => '_']);
        return uniqid( $slugify->slugify( $this->maplayer->getTablename() ) . '_');
    }

    public function getTablenameOrigin(): string
    {
        return $this->maplayer->getTablename();
    }

    public function getGeometryType(): string
    {
        return $this->maplayer->getGeometryType();
    }

    public function getKey(): string
    {
        return $this->maplayer->getDisplayfield();
    }

    public function getFile(): ?string
    {
        $search = pathinfo( $this->maplayer->getDatasource()['file'], PATHINFO_BASENAME );
        $finder = new Finder();
        $finder->in( pathinfo( $this->maplayer->project['file'], PATHINFO_DIRNAME ) );
        $finder->files()->name([$search]);
        foreach ($finder as $file) {
            $paths[] = $file->getRealPath();
        }
        if ( !isset($paths) ) {
            return null;
        }
        return $paths[0];        
    }

    public function getProjectfile()
    {
        return $this->maplayer->project['file'];
    }
}