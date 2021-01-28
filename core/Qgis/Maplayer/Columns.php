<?php
namespace Core\Qgis\Maplayer;

use Core\Qgis\AbstractMaplayer;

class Columns extends AbstractMaplayer
{
	protected $maplayer;

    public function __construct(\SimpleXMLElement $maplayer)
    {
        $this->maplayer = $maplayer;
    }

	public function get(): array
	{
		$columns = [];
		$displayfield = $this->getDisplayfield();
		foreach ($this->maplayer->attributetableconfig->columns->column as $attribute) {
			$attributeName = (string) $attribute['name'];
			if ( strlen($attributeName) > 0 ) {
				$columns[] = [
					'name' 			=> $attributeName,
					'alias' 		=> $this->getAlias($attributeName),
					'displayfield'	=> ( $attributeName === $displayfield ) ? true : false,
					'excluded' 		=> $this->isExcluded($attributeName),
					'unit'	 		=> '',
					'numeric' 		=> false,
				];
			}
		}
		if ( empty($columns) ) {
			foreach ($this->maplayer->defaults->default as $default) {
				$attributeName = (string) $default['field'];
				$columns[] = [
					'name' 			=> $attributeName,
					'alias' 		=> $this->getAlias($attributeName),
					'displayfield'	=> ( $attributeName === $displayfield ) ? true : false,
					'excluded' 		=> $this->isExcluded($attributeName),
					'unit'	 		=> '',
					'numeric' 		=> false,
				];
			}
		}
		return $columns;
	}

	public function set(array $columns): void
	{
		$this->updateColumns($columns);
		$this->updateAliases($columns);
		$this->updateExcludedAttributes($columns);
	}
	
	private function getAlias($attributeName): string
	{
		if ( $this->maplayer->aliases->alias ) {
			foreach ( $this->maplayer->aliases->alias as $alias ) {
				if ( (string) $alias['field'] === $attributeName ) {
					if ( (string) $alias['name'] !== '' ) {
						return (string) $alias['name'];
					}
				}
			}
		}
		return $attributeName;
	}

	private function isExcluded($attributeName): string
	{
        if ( $this->maplayer->excludeAttributesWMS->attribute ) {
			foreach ( $this->maplayer->excludeAttributesWMS->attribute as $excluded ) {
				if ( (string) $excluded === $attributeName ) {
					return true;
				}
            }
        }
        return false;
	}

	private function updateColumns(array $columns): void
	{
		$dom = dom_import_simplexml( $this->maplayer->attributetableconfig->columns );
		$dom->parentNode->removeChild($dom);
		$this->maplayer->attributetableconfig->addChild('columns');
		foreach ($columns as $column) {
			if ($column['displayfiled'] === true) {
				$this->setDisplayfield( $column['name'] );
			}
			$child = $this->maplayer->attributetableconfig->columns->addChild('column');
			$child->addAttribute('type', 'field');
			$child->addAttribute('hidden', '0');
			$child->addAttribute('width', '-1');
			$child->addAttribute('name', $column['name']);
		}
		$child = $this->maplayer->attributetableconfig->columns->addChild('column');
		$child->addAttribute('type', 'actions');
		$child->addAttribute('hidden', '0');
		$child->addAttribute('width', '-1');
	}

	private function updateAliases(array $columns): void
	{
		$dom = dom_import_simplexml( $this->maplayer->aliases );
		$dom->parentNode->removeChild($dom);
		$this->maplayer->addChild('aliases');
		$index = 0;
		foreach ($columns as $column) {
			$child = $this->maplayer->aliases->addChild('alias');
			$child->addAttribute('field', $column['name']);
			$child->addAttribute('index', $index);
			$child->addAttribute('name', ($column['name'] === $column['alias']) ? '' : $column['alias']);
			$index++;
		}
	}

	private function updateExcludedAttributes(array $columns): void
	{
		$dom = dom_import_simplexml( $this->maplayer->excludeAttributesWMS );
		$dom->parentNode->removeChild($dom);
		$dom = dom_import_simplexml( $this->maplayer->excludeAttributesWFS );
		$dom->parentNode->removeChild($dom);
		$this->maplayer->addChild('excludeAttributesWMS');
		$this->maplayer->addChild('excludeAttributesWFS');
		foreach ($columns as $column) {
			if ($column['excluded']) {
				$this->maplayer->excludeAttributesWMS->addChild('attribute', $column['name']);
				$this->maplayer->excludeAttributesWFS->addChild('attribute', $column['name']);
			}
		}
	}
}
