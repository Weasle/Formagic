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
 * @package     Test
 * @author      Florian Sonnenburg
 * @copyright   Copyright (c) 2007-2011 Florian Sonnenburg
 * @license     http://www.formagic-php.net/license-agreement/   New BSD License
 */

/**
 * Load abstract item
 */
require_once realpath(dirname(__FILE__) . '/../../Formagic/Item/Abstract.php');

/**
 * Abstract 
 *
 * @category    Formagic
 * @package     Tests
 * @author      Florian Sonnenburg
 * @copyright   Copyright (c) 2007-2011 Florian Sonnenburg
 * @version     $Id: TestCase.php 173 2012-05-16 13:19:22Z meweasle $
 **/
abstract class Formagic_Rule_TestCase extends PHPUnit_Framework_TestCase
{
    protected function _getMockItem($value)
    {
        $mock = $this->getMock('Formagic_Item_Abstract', 
            array('getValue'), 
            array('testItem')
        );
        $mock->expects($this->any())
            ->method('getValue')
            ->will($this->returnValue($value));
        return $mock;
    }
}
