<?php
/**
 * Formagic
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at
 * http://www.formagic-php.net/license-agreement/
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@formagic-php.net so we can send you a copy immediately.
 *
 * @category    Formagic
 * @package     Generator
 * @author      Florian Sonnenburg
 * @copyright   Copyright (c) 2012 Florian Sonnenburg
 * @license     http://www.formagic-php.net/license-agreement/   New BSD License
 */
namespace Formagic\Generator\CodeFormatter;

use Formagic\Generator\Properties\PropertiesInterface;
use Formagic\Generator\Writer\ResourceRegistry;

/**
 * Formagic translator class
 *
 * @category    Formagic
 * @package     Generator
 * @author      Florian Sonnenburg
 * @copyright   Copyright (c) 2012 Florian Sonnenburg
 * @version     $Id: $
 */
class ItemFormatter implements CodeFormatterInterface
{
    /**
     * @var ResourceRegistry
     */
    protected $resourceRegistry;

    /**
     * @param ResourceRegistry $resourceRegistry
     */
    public function __construct(ResourceRegistry $resourceRegistry)
    {
        $this->resourceRegistry = $resourceRegistry;
    }

    /**
     * @return string
     */
    protected function getInstanceVarName()
    {
        $instanceVarName = 'item_' . uniqid();
        return $instanceVarName;
    }

    /**
     * @param PropertiesInterface $properties
     * @return string
     * @throws InvalidTypeException
     */
    protected function getItemClassName(PropertiesInterface $properties)
    {
        $itemType = $properties->getType();
        if (empty($itemType)) {
            throw new InvalidTypeException();
        }
        $itemClass = $properties->getClassPrefix() . ucfirst($itemType);
        return $itemClass;
    }

    /**
     *
     * @param PropertiesInterface $properties
     *
     * @return FormatResult
     */
    public function format(PropertiesInterface $properties)
    {
        /* @var $properties \Formagic\Generator\Properties\ItemProperties */
        $class = $this->getItemClassName($properties);
        $this->resourceRegistry->addFormagicClass($class);

        $itemInstanceVar = '$' . $this->getInstanceVarName();

        $lines = array();
        $name = $properties->getName();
        $lines[] = "{$itemInstanceVar} = new {$class}('{$name}');";

        foreach($properties->getRuleBag() as $ruleProperties) {
            $lines[] = "{$itemInstanceVar}->addRule('{$ruleProperties->getType()}');";
        }

        foreach($properties->getFilterBag() as $filterProperties) {
            $lines[] = "{$itemInstanceVar}->addFilter('{$filterProperties->getType()}');";
        }

        $output = implode(PHP_EOL, $lines);

        $result = new FormatResult($output, $itemInstanceVar);
        return $result;
    }
}

