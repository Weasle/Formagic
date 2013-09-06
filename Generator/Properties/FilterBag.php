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

/**
 * Formagic generator filter bag
 *
 * @category    Formagic
 * @package     Generator
 * @author      Florian Sonnenburg
 * @copyright   Copyright (c) 2007-2012 Florian Sonnenburg
 * @version     $Id: FilterBag.php 181 2012-11-02 20:31:51Z meweasle $
 **/
class FilterBag implements \IteratorAggregate
{
    /**
     * List of filters
     * @var array
     */
    protected $_filters = array();

    /**
     * Adds a new filter to the filter list.
     *
     * @param PropertiesInterface $filter
     * @return FilterBag Fluent interface
     */
    public function addFilter(PropertiesInterface $filter)
    {
        $filterName = get_class($filter);
        $this->_filters[$filterName] = $filter;
        return $this;
    }

    /**
     * Sets new filter list.
     *
     * @param array $filters Array of filters
     * @return FilterBag Fluent interface
     */
    public function setFilters(array $filters)
    {
        $this->_filters = $filters;
        return $this;
    }

    /**
     * Returns currently set filter list.
     *
     * @return array Array of filters
     */
    public function getFilters()
    {
        return $this->_filters;
    }

    /**
     * {@inheritDoc}
     */
    public function getIterator()
    {
        $arrayIterator = new \ArrayIterator($this->_filters);

        return $arrayIterator;
    }
}
