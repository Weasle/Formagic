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
 * Tests Formagic radio input items's public interface
 *
 * @package     Formagic\Tests
 * @author      Florian Sonnenburg
 **/
class Formagic_Item_Radio_Test extends PHPUnit_Framework_TestCase
{
    /**
     * @expectedException Formagic_Exception
     */
    public function testWrongOptionException()
    {
        $input = new Formagic_Item_Radio('test', array('n/a' => 'n/a'));
    }

    /**
     * Test setEmpty()-method
     */
    public function testSetEmpty()
    {
        // set by method
        $input = new Formagic_Item_Radio('test');
        $res = $input->setEmpty();
        $this->assertSame($input, $res);

        $res = $input->setEmpty('empty');
        $this->assertSame($input, $res);

        $res = $input->setEmpty(array(0 => '-'));
        $this->assertSame($input, $res);

        $res = $input->setEmpty(true, Formagic_Item_Radio::EMPTY_PREPEND);
        $this->assertSame($input, $res);

        $res = $input->setEmpty(true, Formagic_Item_Radio::EMPTY_APPEND);
        $this->assertSame($input, $res);
    }

    /**
     * Test setting wrong element to setEmpty()
     * @expectedException Formagic_Exception
     */
    public function testSetWrongEmptyElement()
    {
        // set by method
        $input = new Formagic_Item_Radio('test');
        $res = $input->setEmpty(null);
    }
    
    /**
     * Test setting wrong element to setEmpty()
     * @expectedException Formagic_Exception
     */
    public function testSetWrongEmptyElement2()
    {
        // set by method
        $input = new Formagic_Item_Radio('test');
        $res = $input->setEmpty(10);
    }

    /**
     * Test setting wrong element to setEmpty()
     * @expectedException Formagic_Exception
     */
    public function testSetWrongEmptyPosition()
    {
        // set by method
        $input = new Formagic_Item_Radio('test');
        $res = $input->setEmpty('empty', 'last');
    }

    /**
     * Test empty element option
     */
    public function testSetEmptyByOption()
    {
        $type = 'Formagic_Item_Radio';

        $input = new Formagic_Item_Radio('test', array('appendEmpty' => true));
        $this->assertInstanceOf($type, $input);

        $input = new Formagic_Item_Radio('test', array('prependEmpty' => true));
        $this->assertInstanceOf($type, $input);

        $input = new Formagic_Item_Radio('test', array('appendEmpty' => 'empty'));
        $this->assertInstanceOf($type, $input);

        $input = new Formagic_Item_Radio('test', array('prependEmpty' => 'empty'));
        $this->assertInstanceOf($type, $input);

        $input = new Formagic_Item_Radio('test', array('appendEmpty' => array(0 => 'empty')));
        $this->assertInstanceOf($type, $input);

        $input = new Formagic_Item_Radio('test', array('prependEmpty' => array(0 => 'empty')));
        $this->assertInstanceOf($type, $input);
    }

    /**
     * Test invalid empty element option
     * @expectedException Formagic_Exception
     */
    public function testSetInvalidEmptyByOption()
    {
        $input = new Formagic_Item_Radio('test', array('appendEmpty' => 1));
    }

    /**
     * Test HTML output of radio field without any radio options
     */
    public function testGetHtmlWithoutOptionsWithEmpty()
    {
        $myName = 'test';
        $input = new Formagic_Item_Radio($myName);
        $html = $input->getHtml();

        $matcher = array(
            'tag' => 'span',
            'attributes' => array(
                'id' => $myName,
            )
        );
        $this->assertTag($matcher, $html);
    }

    /**
     * Test HTML output of radio field by method
     */
    public function testGetHtmlWithOptionsByMethod()
    {
        // test setting options explicitly
        $myName = 'test';
        $options = array(
            'val1' => 'Label 1',
            'val2' => 'Label 2',
        );
        $input = new Formagic_Item_Radio($myName);
        $res = $input->setData($options);

        // test output of setData()
        $this->assertSame($input, $res);

        $html = $input->getHtml();
        $matcher = array(
            'tag' => 'span',
            'attributes' => array(
                'id' => $myName,
            ),
            'children' => array(
                'count' => 4
            ),
            'child' => array(
                'tag' => 'input',
                'attributes' => array(
                    'type' => 'radio',
                    'id' => $myName . '1',
                    'name' => $myName,
                    'value' => 'val1'
                ),
                'tag' => 'label',
                'attributes' => array(
                    'for' => $myName . '1',
                ),
                'content' => 'Label 1',

                'tag' => 'input',
                'attributes' => array(
                    'type' => 'radio',
                    'id' => $myName . '2',
                    'name' => $myName,
                    'value' => 'val2'
                ),
                'tag' => 'label',
                'attributes' => array(
                    'for' => $myName . '2',
                ),
                'content' => 'Label 2',
            )
        );
        $this->assertTag($matcher, $html);
    }

    /**
     * Test HTML output of radio field with options by option
     */
    public function testGetHtmlWithOptionsByOption()
    {
        // test setting options explicitly
        $myName = 'test';
        $options = array(
            'val1' => 'Label 1',
        );
        $input = new Formagic_Item_Radio($myName, array('data' => $options));

        $html = $input->getHtml();
        $matcher = array(
            'tag' => 'span',
            'attributes' => array(
                'id' => $myName,
            ),
            'children' => array(
                'count' => 2
            ),
            'child' => array(
                'tag' => 'input',
                'attributes' => array(
                    'type' => 'radio',
                    'id' => $myName . '1',
                    'name' => $myName,
                    'value' => 'val1'
                ),
                'tag' => 'label',
                'attributes' => array(
                    'for' => $myName . '1',
                ),
                'content' => 'Label 1'
            )
        );
        $this->assertTag($matcher, $html);
    }

    /**
     * Test HTML output of radio field, using an empty element
     */
    public function testGetHtmlWithEmptyElement()
    {
        // test setting options explicitly
        $myName = 'test';
        $input = new Formagic_Item_Radio($myName);
        $input->setEmpty(array('emptyValue' => 'EmptyLabel'));

        $html = $input->getHtml();
        $matcher = array(
            'tag' => 'span',
            'attributes' => array(
                'id' => $myName,
            ),
            'children' => array(
                'count' => 2
            ),
            'child' => array(
                'tag' => 'input',
                'attributes' => array(
                    'type' => 'radio',
                    'id' => $myName . '1',
                    'name' => $myName,
                    'value' => 'emptyValue'
                ),
                'tag' => 'label',
                'attributes' => array(
                    'for' => $myName . '1',
                ),
                'content' => 'EmptyLabel'
            )
        );
        $this->assertTag($matcher, $html);
    }

    /**
     * Test HTML output of radio field, using an empty element and check
     * the position
     */
    public function testEmptyElementPositon()
    {
        // prepend by option
        $myName = 'test';
        $input = new Formagic_Item_Radio($myName, array(
            'prependEmpty' => array('emptyValue' => 'EmptyLabel'),
            'data' => array('val1' => 'Label 1')
        ));

        // prepend, value "", label "EmptyLabel"
        $html = $input->getHtml();
        $condition = preg_match('/(?s)value="emptyValue".*EmptyLabel.+Label\s1/', $html);
        $this->assertEquals(1, $condition);

        // test defaults of setEmpty() (prepend, value "", label '---')
        $input->setEmpty();

        $html = $input->getHtml();
        $condition = preg_match('/(?s)value="".*\-\-\-.*Label\s1/', $html);
        $this->assertEquals(1, $condition);

        // overwrite with append by method
        $input->setEmpty(true, Formagic_Item_Radio::EMPTY_APPEND);
        $html = $input->getHtml();

        $condition = preg_match('/(?s)Label\s1.*\-\-\-/', $html);
        $this->assertEquals(1, $condition);

        // test empty element (append, value 0, label 'empty')
        $input->setEmpty(array(0 => 'empty'), Formagic_Item_Radio::EMPTY_APPEND);
        $html = $input->getHtml();

        $condition = preg_match('/(?s)Label\s1.*value="0".*empty/', $html);
        $this->assertEquals(1, $condition);
    }

    /**
     * Test setSeparator() method
     */
    public function testSetSeparator()
    {
        // test method
        $input = new Formagic_Item_Radio('test');
        $res = $input->setSeparator('#');
        $this->assertSame($input, $res);

        $input->setData(array(
            'val1' => 'Label 1',
            'val2' => 'Label 2'
        ));
        
        $html   = $input->getHtml();
        $actual = strpos($html, '#');
        $this->assertNotInternalType('boolean', $actual);
    }

    /**
     * Tests that the closing label tag is kept unchanged when using a separator
     */
    public function testOutputWithSeparator()
    {
        $input = new Formagic_Item_Radio('test');
        $input->setSeparator('<span></span>');

        $input->setData(array(
            'val1' => 'Label 1',
            'val2' => 'Label 2'
        ));

        $html   = $input->getHtml();
        $this->assertRegExp('~</label></span>$~', $html);
    }

    /**
     * Test HTML output of radio field, using an empty element
     */
    public function testCheckedElement()
    {
        // test setting options explicitly
        $myName = 'test';
        $input = new Formagic_Item_Radio($myName);
        $input->setData(array('v1' => 'L1'));
        $input->setValue('v1');

        $html = $input->getHtml();
        $matcher = array(
            'tag' => 'span',
            'child' => array(
                'tag' => 'input',
                'attributes' => array(
                    'type' => 'radio',
                    'id' => $myName . '1',
                    'name' => $myName,
                    'value' => 'v1'
                ),
            )
        );
        $this->assertTag($matcher, $html);
    }

    /**
     * Test readonly output of password field
     */
    public function testGetReadonlyOutput()
    {
        $myName = 'test';
        $input = new Formagic_Item_Radio($myName, array(
            'prependEmpty'  => array('emptyValue' => 'EmptyLabel'),
            'data'          => array('val1' => 'Label 1'),
            'readonly'      => true,
            'value'         => 'val1',
            'separator'     => '#'
        ));
        $html = $input->getHtml();

        $matcher = array(
            'tag' => 'span',
            'attributes' => array(
                'id' => $myName,
            ),
            'children' => array(
                'count' => 1
            ),
            'child' => array(
                'tag' => 'input',
                'attributes' => array(
                    'type' => 'hidden',
                    'name' => $myName,
                    'value' => 'val1'
                ),
            )
        );
        $this->assertTag($matcher, $html);
        $condition = preg_match('/\) EmptyLabel#\(o\)/', $html);
        $this->assertEquals(1, $condition);
    }
}
