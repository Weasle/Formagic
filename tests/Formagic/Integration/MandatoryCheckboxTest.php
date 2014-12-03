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
 * Tests item behavior
 *
 * @package     Formagic\Tests
 * @author      Florian Sonnenburg
 **/
class MandatoryCheckboxTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {

    }

    public function testCheckboxIsMandatoryWithoutValue()
    {
        $this->executeCheckboxTest(false);
    }

    public function testCheckboxIsMandatoryWithValue()
    {
        $this->executeCheckboxTest(true);
    }

    public function testCheckboxIsMandatoryWithValueAndGetValue()
    {
        $_GET = array('checkboxName' => 'hasValue');
        $formagic = new Formagic(
            array(
                'method' => 'get',
                'trackSubmission' => false
            )
        );
        $checkbox = new Formagic_Item_Checkbox(
            'checkboxName',
            array(
                'value' => 'default',
                'rules' => 'mandatory',
            )
        );

        $formagic->getItemHolder()->addItem($checkbox);

        $isValid = $formagic->validate();
        $this->assertTrue($isValid);
    }

    protected function executeCheckboxTest($withValue)
    {
        $_GET = array('test' => 'fake');
        $formagic = new Formagic(
            array(
                'method' => 'get',
                'trackSubmission' => false
            )
        );
        $checkbox = new Formagic_Item_Checkbox(
            'checkboxName',
            array(
                'rules' => 'mandatory',
            )
        );

        if ($withValue) {
            $checkbox->setValue('valueIfSubmitted');
        }
        $formagic->getItemHolder()->addItem($checkbox);

        $isValid = $formagic->validate();
        $this->assertFalse($isValid, 'Submitting form should not be valid');

        $violatedRules = $formagic->getItemHolder()->getItem('checkboxName')->getViolatedRules();
        $this->assertCount(1, $violatedRules, 'Number of violated rules does not match');
        $mandatoryRule = $violatedRules[0];
        $this->assertInstanceOf('Formagic_Rule_Mandatory', $mandatoryRule);
    }
}
