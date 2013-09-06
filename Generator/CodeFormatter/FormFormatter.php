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
 * @version     $Id: FormFormatter.php 183 2012-11-17 13:34:27Z meweasle $
 */
class FormFormatter implements CodeFormatterInterface
{
    /**
     * @var ItemFormatter
     */
    protected $itemFormatter;

    protected $resourceRegistry;

    /**
     * @param CodeFormatterInterface $itemFormatter
     * @param ResourceRegistry $resourceRegistry
     */
    public function __construct(CodeFormatterInterface $itemFormatter, ResourceRegistry $resourceRegistry)
    {
        $this->itemFormatter = $itemFormatter;
        $this->resourceRegistry = $resourceRegistry;
    }

    /**
     * @return string
     */
    protected function getInstanceVarName()
    {
        $instanceVarName = 'formagicForm';
        return $instanceVarName;
    }

    /**
     *
     * @param PropertiesInterface $properties
     * @return FormatResult
     */
    public function format(PropertiesInterface $properties)
    {
        $this->resourceRegistry->addResource('Formagic.php');

        $formInstanceVar = '$' . $this->getInstanceVarName();

        $lines = array();
        $lines[] = $formInstanceVar . ' = new Formagic();';

        $baseDirs = $properties->getBaseDirs();
        foreach($baseDirs as $baseDir) {
            $lines[] = "{$formInstanceVar}->addBaseDir('{$baseDir}');";
        }

        $renderer = $properties->getRenderer();
        if (!empty($renderer)) {
            $lines[] = "{$formInstanceVar}->setRenderer('{$renderer}');";
        }

        $translator = $properties->getTranslator();
        if (!empty($translator)) {
            $lines[] = "{$formInstanceVar}->setTranslator('{$translator}');";
        }

        $items = $properties->getItems();
        if (!empty($items)) {

            /* @var $itemProperty \Formagic\Generator\Properties\ItemProperties */
            foreach($items as $itemProperty) {
                $itemFormatResult = $this->itemFormatter->format($itemProperty);

                $lines[] = $itemFormatResult->getInstanceCode();

                $itemInstanceVar = $itemFormatResult->getInstanceVarName();
                $lines[] = "{$formInstanceVar}->addItem({$itemInstanceVar});";
            }
        }

        $output = implode(PHP_EOL, $lines);
        $result = new FormatResult($output, $formInstanceVar);

        return $result;
    }
}

