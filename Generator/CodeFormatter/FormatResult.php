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
namespace Formagic\Generator\CodeFormatter;

/**
 * Class description
 *
 * @category    Formagic
 * @package     Generator
 * @author      Florian Sonnenburg
 * @copyright   Copyright (c) 2012 Florian Sonnenburg
 * @version     $Id: FormatResult.php 182 2012-11-06 20:56:05Z meweasle $
 */
class FormatResult
{
    /**
     * @var string
     */
    protected $instanceCode;

    /**
     * @var string
     */
    protected $instanceVarName;

    /**
     * @param string $instanceCode
     * @param string $instanceVarName
     */
    public function __construct($instanceCode, $instanceVarName)
    {
        $this->instanceCode = $instanceCode;
        $this->instanceVarName = $instanceVarName;
    }

    /**
     * @return string
     */
    public function getInstanceCode()
    {
        return $this->instanceCode;
    }

    /**
     * @return string
     */
    public function getInstanceVarName()
    {
        return $this->instanceVarName;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->instanceCode;
    }

}
