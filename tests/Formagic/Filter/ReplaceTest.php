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
 * Tests Formagic filter public interface
 *
 * @package     Formagic\Tests
 * @author      Florian Sonnenburg
 **/
class Test_Formagic_Filter_Replace extends PHPUnit_Framework_TestCase
{
    /**
     * Tests if the filter instance implements correct interface
     */
    public function testInterface()
    {
        $filter = new Formagic_Filter_Replace(array());
        $this->assertInstanceOf('Formagic_Filter_Interface', $filter);
    }

    /**
     * Tests replace filter output
     */
    public function testFilter()
    {
        $value = 'big smallville fox';
        $replacements = array(
            'big' => 'small',
            'smallville' => 'bigtown',
            'fox' => 'cat'
        );
        $filter = new Formagic_Filter_Replace($replacements);
        $filteredValue = $filter->filter($value);
        
        $this->assertEquals($filteredValue, 'small bigtown cat');
    }
}
