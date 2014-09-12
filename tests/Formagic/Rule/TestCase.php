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
 * @author      Florian Sonnenburg
 * @copyright   2007-2014 Florian Sonnenburg
 * @license     http://www.formagic-php.net/license-agreement/   New BSD License
 */

/**
 * Abstract
 *
 * @package     Formagic\Tests
 * @author      Florian Sonnenburg
 **/
abstract class Formagic_Rule_TestCase extends PHPUnit_Framework_TestCase
{
    /**
     * @param mixed $value
     * @return PHPUnit_Framework_MockObject_MockObject
     */
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
