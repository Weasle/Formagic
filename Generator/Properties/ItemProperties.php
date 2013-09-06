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
 * @copyright   Copyright (c) 2007-2012 Florian Sonnenburg
 * @license     http://www.formagic-php.net/license-agreement/   New BSD License
 */
namespace Formagic\Generator\Properties;

use Formagic\Generator\Properties\RuleBag;
use Formagic\Generator\Properties\FilterBag;

/**
 * Formagic generator item properties class
 *
 * @category    Formagic
 * @package     Generator
 * @author      Florian Sonnenburg
 * @copyright   Copyright (c) 2007-2012 Florian Sonnenburg
 * @version     $Id: ItemProperties.php 182 2012-11-06 20:56:05Z meweasle $
 **/
class ItemProperties implements PropertiesInterface
{
    /**
     * @var FilterBag
     */
    protected $filterBag;

    /**
     * @var RuleBag
     */
    protected $ruleBag;

    /**
     * @var array
     */
    protected $properties;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $classPrefix = 'Formagic_Item_';

    public function __construct()
    {
        $this->ruleBag = new RuleBag();
        $this->filterBag = new FilterBag();
    }

    /**
     * @param FilterBag $filterBag
     * @return ItemProperties Fluent interface
     */
    public function setFilterBag(FilterBag $filterBag)
    {
        $this->filterBag = $filterBag;
        return $this;
    }

    /**
     * @return FilterBag
     */
    public function getFilterBag()
    {
        return $this->filterBag;
    }

    /**
     * @param array $properties
     * @return ItemProperties Fluent interface
     */
    public function setProperties(array $properties)
    {
        $this->properties = $properties;
        return $this;
    }

    /**
     * @return array
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * @param RuleBag $ruleBag
     * @return ItemProperties Fluent interface
     */
    public function setRuleBag(RuleBag $ruleBag)
    {
        $this->ruleBag = $ruleBag;
        return $this;
    }

    /**
     * @return RuleBag
     */
    public function getRuleBag()
    {
        return $this->ruleBag;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $classPrefix
     */
    public function setClassPrefix($classPrefix)
    {
        $this->classPrefix = $classPrefix;
    }

    /**
     * @return string
     */
    public function getClassPrefix()
    {
        return $this->classPrefix;
    }


}
