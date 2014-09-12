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
 * Tests Formagic XHTML renderer
 *
 * @package     Formagic\Tests
 * @author      Florian Sonnenburg
 **/
class Formagic_XhtmlTest extends PHPUnit_Framework_TestCase
{
    /**
     *
     * @var Formagic_Renderer_Html 
     */
    private $_renderer;
    
    public function setUp()
    {
        $this->_renderer = new Formagic_Renderer_Xhtml();
    }
    
    public function testGetContainerWrapperTemplate()
    {
        $form = new Formagic();
        
        $actual = $this->_renderer->render($form);
        $matcher = array(
            'tag' => 'fieldset',
            'child' => array(
                'tag' => 'dl',
            )
        );
        $this->assertTag($matcher, $actual);
    }
    
    public function testGetContainerRowTemplate()
    {
        $form = new Formagic();
        $container = new Formagic_Item_Container('sub');
        $form->addItem($container);
        
        $actual = $this->_renderer->render($form);
        $matcher = array(
            'tag'        => 'fieldset',
            'child' => array(
                'tag' => 'dl',
                'child' => array(
                    'tag' => 'dd',
                    'child' => array(
                        'tag' => 'fieldset',
                        'attributes' => array('id' => 'sub')
                    )
                )
            )
        );
        $this->assertTag($matcher, $actual);
    }
    
    public function testContainerLabelTemplate()
    {
        $containerLabel = 'testLabel';
        
        $form = new Formagic();
        $container = new Formagic_Item_Container('sub', array(
            'label' => $containerLabel,
        ));
        $form->addItem($container);
        
        $actual = $this->_renderer->render($form);
        $matcher = array(
            'tag'        => 'fieldset',
            'child' => array(
                'tag' => 'dl',
                'child' => array(
                    'tag' => 'dd',
                    'child' => array(
                        'tag' => 'fieldset',
                        'attributes' => array('id' => 'sub'),
                        'child' => array(
                            'tag' => 'legend',
                            'content' => $containerLabel
                        )
                    )
                )
            )
        );
        $this->assertTag($matcher, $actual);
    }
    
    public function testGetItemRowTemplate()
    {
        $expectedLabel = 'myLabel';
        $item = $this->getMock(
            'Formagic_Item_Abstract',
            array('getLabel'),
            array('testItem')
        );
        $item->expects($this->once())
                ->method('getLabel')
                ->will($this->returnValue($expectedLabel));
        
        $form = new Formagic();
        $form->addItem($item);
        
        $actual = $this->_renderer->render($form);
        $matcher = array(
            'tag' => 'dt',
            'child' => array(
                'tag' => 'label',
                'content' => $expectedLabel,
            )
        );
        $this->assertTag($matcher, $actual);
    }
    
    /**
     * Tests that container label is a legend tag
     */
    public function testGetContainerLabelTemplate()
    {
        $template = $this->_renderer->getContainerLabelTemplate();
        $this->assertContains('legend', $template);
    }
}
