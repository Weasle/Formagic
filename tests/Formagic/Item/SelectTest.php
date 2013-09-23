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
 * Tests Formagic select input items's public interface
 *
 * @category    Formagic
 * @package     Tests
 * @author      Florian Sonnenburg
 * @copyright   Copyright (c) 2011
 * @version     $Id: SelectTest.php 173 2012-05-16 13:19:22Z meweasle $
 **/
class Formagic_Item_Select_Test extends PHPUnit_Framework_TestCase
{
    /**
     * Test setData() result
     */
    public function testSetData()
    {
        $input = new Formagic_Item_Select('test');
        $res = $input->setData(array());
        $this->assertSame($input, $res);
    }

    /**
     * Test setMultiple() result
     */
    public function testSetMultiple()
    {
        $input = new Formagic_Item_Select('test');
        $res = $input->setMultiple(true);
        $this->assertSame($input, $res);
    }

    /**
     * Test HTML output of single select field without any options
     * @expectedException Formagic_Exception
     */
    public function testGetSingleHtml()
    {
        $input = new Formagic_Item_Select('test');
        $html = $input->getHtml();
    }

    /**
     * Test HTML output of single select field by method
     */
    public function testGetHtmlWithOptionsByMethod()
    {
        $myName = 'test';
        $options = array(
            'val1' => 'Label 1',
            'val2' => 'Label 2',
        );
        $input = new Formagic_Item_Select($myName);
        $input->setData($options);

        $html = $input->getHtml();
        $matcher = array(
            'tag' => 'select',
            'attributes' => array(
                'name'  => $myName,
                'id'    => $myName
            ),
            'children' => array(
                'count' => 2
            ),
            'child' => array(
                'tag' => 'option',
                'attributes' => array(
                    'value' => 'val1'
                ),
                'content' => 'Label 1',

                'tag' => 'option',
                'attributes' => array(
                    'value' => 'val2'
                ),
                'content' => 'Label 2',
            )
        );
        $this->assertTag($matcher, $html);
    }

    /**
     * Test that settings options by method equals setting options by option
     */
    public function testSetOptionVariants()
    {
        $myName = 'test';
        $options = array(
            'val1' => 'Label 1',
            'val2' => 'Label 2',
        );
        $input1 = new Formagic_Item_Select($myName);
        $res = $input1->setData($options);

        $input2 = new Formagic_Item_Select($myName, array('data' => $options));

        $this->assertEquals($input1, $input2);
    }

    /**
     * Test that selected option is correctly tagged
     */
    public function testSingleSelected()
    {
        $myName = 'test';
        $options = array(
            'val1' => 'Label 1',
            'val2' => 'Label 2',
        );
        $input = new Formagic_Item_Select($myName);
        $input->setData($options);
        $input->setValue('val1');

        $html = $input->getHtml();
        $matcher = array(
            'tag' => 'select',
            'attributes' => array(
                'id' => $myName,
                'name' => $myName,
            ),
            'children' => array(
                'count' => 2
            ),
            'child' => array(
                'tag' => 'option',
                'attributes' => array(
                    'value' => 'val1',
                    'selected' => 'selected'
                ),
                'content' => 'Label 1',
            )
        );
        $this->assertTag($matcher, $html);
    }

    /**
     * Test readonly output of single select field
     */
    public function testSingleReadonlyOutput()
    {
        $myName = 'test';
        $input = new Formagic_Item_Select($myName, array(
            'data'          => array(
                'val1' => 'Label 1',
                'val2' => 'Label 2',
            ),
            'readonly'      => true,
            'value'         => 'val1',
        ));
        $html = $input->getHtml();

        $matcher = array(
            'tag' => 'span',
            'attributes' => array(
                'id'    => $myName,
            ),
            'children' => array(
                'count' => 1
            ),
            'child' => array(
                'tag' => 'input',
                'attributes' => array(
                    'type'  => 'hidden',
                    'name'  => $myName,
                    'value' => 'val1'
                ),
            )
        );
        $this->assertTag($matcher, $html);
    }

    /**
     * Test that multiple select option is correctly tagged
     */
    public function testMultipleSelected()
    {
        // one option selected
        $myName = 'test';
        $options = array(
            'val1' => 'Label 1',
            'val2' => 'Label 2',
        );
        $input = new Formagic_Item_Select($myName);
        $input->setData($options);
        $input->setValue('val1');
        $input->setMultiple(true);

        $html = $input->getHtml();
        $matcher = array(
            'tag' => 'select',
            'attributes' => array(
                'id' => $myName,
                'name' => $myName . '[]',
                'multiple' => 'multiple',
            ),
            'children' => array(
                'count' => 2
            ),
            'child' => array(
                'tag' => 'option',
                'attributes' => array(
                    'value' => 'val1',
                    'selected' => 'selected'
                ),
                'content' => 'Label 1',

                'tag' => 'option',
                'attributes' => array(
                    'value' => 'val2',
                ),
                'content' => 'Label 2',
            )
        );
        $this->assertTag($matcher, $html);

        // both options selected
        $input->setValue(array('val1', 'val2'));
        $html = $input->getHtml();
        $matcher = array(
            'tag' => 'select',
            'attributes' => array(
                'id' => $myName,
                'name' => $myName . '[]',
                'multiple' => 'multiple',
            ),
            'children' => array(
                'count' => 2
            ),
            'child' => array(
                'tag' => 'option',
                'attributes' => array(
                    'value' => 'val1',
                    'selected' => 'selected'
                ),
                'content' => 'Label 1',

                'tag' => 'option',
                'attributes' => array(
                    'value' => 'val2',
                    'selected' => 'selected'
                ),
                'content' => 'Label 2',
            )
        );
        $this->assertTag($matcher, $html);
    }

    /**
     * Test readonly output of multiple select field
     */
    public function testMultiReadonlyOutput()
    {
        // one option selected
        $myName = 'test';

        $value1 = 'v1';
        $label1 = 'Label 1';

        $value2 = 'v2';
        $label2 = 'Label 2';

        $input = new Formagic_Item_Select($myName, array(
            'data'          => array(
                $value1 => $label1,
                $value2 => $label2,
            ),
            'readonly'      => true,
            'value'         => $value1,
            'multiple'      => true
        ));
        $html = $input->getHtml();

        $matcher = array(
            'tag' => 'span',
            'attributes' => array(
                'id'    => $myName,
            ),
            'content' => '[' . $label1 . ']',
            'children' => array(
                'count' => 1
            ),
            'child' => array(
                'tag' => 'input',
                'attributes' => array(
                    'type'  => 'hidden',
                    'name'  => $myName . '[]',
                    'value' => $value1
                ),
            ),
            'content' => $label1
        );
        $this->assertTag($matcher, $html);

        // both options selected
        $input->setValue(array($value1, $value2));
        $html = $input->getHtml();

        $matcher = array(
            'tag' => 'span',
            'attributes' => array(
                'id'    => $myName,
            ),
            'content' => '[' . $label1 . ', ' . $label2 . ']',
            'children' => array(
                'count' => 2
            ),
            'child' => array(
                'tag' => 'input',
                'attributes' => array(
                    'type'  => 'hidden',
                    'name'  => $myName . '[]',
                    'value' => $value1
                ),

                'tag' => 'input',
                'attributes' => array(
                    'type'  => 'hidden',
                    'name'  => $myName . '[]',
                    'value' => $value2
                ),
            )
        );
        $this->assertTag($matcher, $html);
    }

    /**
     * Test optgroup feature
     */
    public function testOptGroup()
    {
        $optgroupName = 'o1';
        $optgroupData = array(
            'v1' => 'l1',
            'v2' => 'l2'
        );
        $data = array(
            $optgroupName => $optgroupData
        );
        $input = new Formagic_Item_Select('test');
        $input->setData($data);
        $actual  = $input->getHtml();
        $matcher = array(
            'tag' => 'select',
            'attributes' => array(
                'id' => 'test',
                'name' => 'test',
            ),
            'children' => array(
                'count' => 1
            ),
            'child' => array(
                'tag' => 'optgroup',
                'attributes' => array(
                    'label' => $optgroupName,
                ),
                'children' => array(
                    'count' => 2
                ),
                'child' => array(
                    'tag' => 'option',
                    'attributes' => array(
                        'value' => 'v1',
                        'selected' => 'selected'
                    ),
                    'content' => 'l1',

                    'tag' => 'option',
                    'attributes' => array(
                        'value' => 'v2',
                    ),
                    'content' => 'l2',
                )
            )
        );
        $this->assertTag($matcher, $actual);
    }

    /**
     * Test XSS protection
     */
    public function testXssProtection()
    {
        $xss = "<script type=\"text/javascript\">alert('xss')</script>";
        $input = new Formagic_Item_Select('test', array(
            'data' => array(
                $xss => 'Label'
            ),
        ));
        $actual  = $input->getHtml();
        $this->assertNotContains($xss, $actual);
    }
    
    
    /**
     * Tests that item can be validates if it has multiple values in form of
     * an array
     */
    public function testValidateWithArrayValue()
    {
        $item = new Formagic_Item_Select('test');
        $item->setMultiple(true);

        // one rule, validates true
        $validationResult = $item
            ->setValue(array(
                't' => 'trueValue',
                'f' => 'falseValue'
            ))
            ->addRule('Mock_MockRule')
            ->validate();
        $this->assertInternalType('boolean', $validationResult);
        $this->assertTrue($validationResult);

        // one rule, validates false
        $validationResult = $item
            ->setValue(array(
                '0' => '0', 
                '1' => ''
            ))
            ->addRule('Mock_MockRule')
            ->validate();
        $this->assertFalse($validationResult);
    }
    
    /**
     * Tests that item value for multiselect (array) is filtered
     */
    public function testFilterWithArrayValue()
    {
        $input = array(
            'key1' => 'value1',
            'key2' => 'value2',
            'key3' => array(
                'key3_1' => 'value3_1',
                'key3_2' => 'value3_2',
            )
        );
        $expected = array(
            'key1' => Formagic_Filter_Mock_MockFilter::FILTERED_VALUE,
            'key2' => Formagic_Filter_Mock_MockFilter::FILTERED_VALUE,
            'key3' => array(
                'key3_1' => Formagic_Filter_Mock_MockFilter::FILTERED_VALUE,
                'key3_2' => Formagic_Filter_Mock_MockFilter::FILTERED_VALUE,
            )
        );
        
        $item = new Formagic_Item_Select('test');
        $item->setMultiple(true);

        // one rule, validates true
        $actual = $item
            ->setValue($input)
            ->addFilter('Mock_MockFilter')
            ->getValue();
        $this->assertInternalType('array', $actual);
        $this->assertEquals($expected, $actual);
    }
}
