<?php
namespace Core\Qgis\Maplayer;

class Qml
{
    protected $qml;

    public function __construct()
    {
        $this->qml = simplexml_load_file( PATH_TO_MAPS . 'template.style.qml' );
    }

    public function copyLayerStyle(\SimpleXMLElement $maplayer): Qml
    {
        $this
            ->appendChild($maplayer->flags)
            ->appendChild($maplayer->{'renderer-v2'})
            ->appendChild($maplayer->customproperties)
            ->appendChild($maplayer->blendMode)
            ->appendChild($maplayer->featureBlendMode)
            ->appendChild($maplayer->layerOpacity)
            ->appendChild($maplayer->SingleCategoryDiagramRenderer)
            ->appendChild($maplayer->DiagramLayerSettings)
            ->appendChild($maplayer->geometryOptions)
            ->appendChild($maplayer->fieldConfiguration)
            ->appendChild($maplayer->aliases)
            ->appendChild($maplayer->excludeAttributesWMS)
            ->appendChild($maplayer->excludeAttributesWFS)
            ->appendChild($maplayer->defaults)
            ->appendChild($maplayer->constraints)
            ->appendChild($maplayer->constraintExpressions)
            ->appendChild($maplayer->expressionfields)
            ->appendChild($maplayer->attributeactions)
            ->appendChild($maplayer->attributetableconfig)
            ->appendChild($maplayer->conditionalstyles)
            ->appendChild($maplayer->editform)
            ->appendChild($maplayer->editforminit)
            ->appendChild($maplayer->editforminitfilepath)
            ->appendChild($maplayer->editforminitcode);
        return $this;
    }

    public function save(string $file): string
    {
        $this->qml->asXml( $file );
        return $file;
    }

    private function appendChild(\SimpleXmlElement $xmlNode): Qml
    {
        $child = dom_import_simplexml($xmlNode);
        $parent = dom_import_simplexml($this->qml);
        $parent->appendChild( $parent->ownerDocument->importNode($child, true) );
        return $this;  
    }
}