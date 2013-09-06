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
namespace Formagic\Generator\Writer;

/**
 * Class description
 *
 * @category    Formagic
 * @package     Generator
 * @author      Florian Sonnenburg
 * @copyright   Copyright (c) 2012 Florian Sonnenburg
 * @version     $Id: $
 */
class ResourceRegistry
{
    /**
     * @var array
     */
    protected $resources = array();

    /**
     * @var array
     */
    protected $formagicClassNames = array();

    /**
     * @param $className
     */
    public function addFormagicClass($className)
    {
        $this->formagicClassNames[] = $className;
    }

    /**
     * @return array
     */
    public function getFormagicClasses()
    {
        return $this->formagicClassNames;
    }

    /**
     * @param string $resource
     */
    public function addResource($resource)
    {
        $this->resources[] = $resource;
    }

    /**
     * @return array
     */
    public function getResources()
    {
        return $this->resources;
    }
}
