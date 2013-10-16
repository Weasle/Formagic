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
 * @copyright   Copyright (c) 2007-2013 Florian Sonnenburg
 * @license     http://www.formagic-php.net/license-agreement/   New BSD License
 */

/**
 * Tests Formagic upload items's public interface
 *
 * @category    Formagic
 * @package     Tests
 * @author      Florian Sonnenburg
 * @copyright   Copyright (c) 2011
 **/
class Formagic_Item_Upload_Test extends PHPUnit_Framework_TestCase
{
    /**
     * Test setting value
     */
    public function testSetValue()
    {
        $name = 'anyfile.pdf';
        $testValues = array(
            'name' => $name,
            'type' => 'application/pdf',
            'tmp_name' => '/tmp/phpp0WvoY',
            'error' => 0,
            'size' => 518744
        );
        $_FILES = array(
            $name => $testValues
        );

        $input = new Formagic_Item_Upload($name);
        $input->setValue(null);

        $actual = $input->getValue();
        $this->assertInstanceOf('Formagic_Item_Value_UploadValue', $actual);
    }

    /**
     * Test file input HTML.
     */
    public function testGetHtml()
    {
        $myName = 'test';

        // set source by option
        $input = new Formagic_Item_Upload($myName);
        $html = $input->getHtml();
        $matcher = array(
            'tag' => 'input',
            'attributes' => array(
                'name'  => $myName,
                'id'    => $myName,
                'type'  => 'file',
            )
        );
        $this->assertTag($matcher, $html);
    }

    /**
     * Test file input HTML, simulate an uploaded file
     */
    public function testGetHtmlWithValue()
    {
        $name = 'testFile';
        $expectedValue = array(
            'name'     => 'testName',
            'type'     => 'test',
            'size'     => 1,
            'tmp_name' => 'testName',
            'error'    => 0);
        $_FILES = array(
            $name => $expectedValue
        );

        // set source by option
        $input = new Formagic_Item_Upload($name);
        $input->setValue($expectedValue);
        $html = $input->getHtml();
        $matcher = array(
            'tag' => 'input',
            'attributes' => array(
                'type'  => 'file',
                'name'  => $name,
                'id'    => $name,
            )
        );
        $this->assertTag($matcher, $html);

        // test readonly
        $input->setReadonly(true);
        $html = $input->getHtml();

        $matcher = array(
            'tag' => 'div',
            'content' => $expectedValue,
            'attributes' => array(
                'id'    => $name,
            )
        );
        }
}
