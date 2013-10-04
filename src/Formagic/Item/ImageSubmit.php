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
 * @package     Item
 * @author      Florian Sonnenburg
 * @copyright   Copyright (c) 2007-2013 Florian Sonnenburg
 * @license     http://www.formagic-php.net/license-agreement/   New BSD License
 */

/**
 * Formagic image submit button item
 *
 * @category    Formagic
 * @package     Item
 * @author      Florian Sonnenburg
 * @copyright   Copyright (c) 2009 Florian Sonnenburg
 */
class Formagic_Item_ImageSubmit extends Formagic_Item_Submit
{
    /**
     * Image source
     * @var string
     */
    private $_src = '';

    /**
     * Click coordiantes if triggered
     * @var array
     */
    private $_clickCoordinates = array();

   /**
     * Subclass initialization.
     *
     * @param array $additionalArgs Ignored for this item.
     * @return void
     */
    protected function _init($additionalArgs)
    {
        // can't be edited
        $this->_isReadonly = true;
        if (isset($additionalArgs['source'])) {
            $this->setSource($additionalArgs['source']);
        }
    }

    /**
     * Set image source.
     *
     * @param string $source Fully qualified image file resource (URI)
     * @return Formagic_Item_ImageSubmit $this object
     */
    public function setSource($source)
    {
        $this->_src = $source;
        return $this;
    }

    /**
     * HTML string representation of submit button
     *
     * @throws Formagic_Exception if image source not given
     *
     * @return string
     */
    public function getHtml()
    {
        if (!$this->_src) {
            throw new Formagic_Exception('Image submit needs an image source.');
        }
        $attributes = $this->getAttributes();
        $attributes['src'] = $this->_src;
        $attributes['type'] = 'image';
        $str = '<input' . $this->_buildAttributeStr($attributes) . ' />';
        return $str;
    }

    /**
     * Sets click coordinates.
     *
     * @param array $coordinates
     * @return Formagic_Item_ImageSubmit Fluent interface
     */
    public function setClickCoordinates(array $coordinates)
    {
        $this->_clickCoordinates = $coordinates;
        return $this;
    }

    /**
     * Returns click coordinates if triggered.
     *
     * @return array
     */
    public function getClickCoordinates()
    {
        return $this->_clickCoordinates;
    }

    /**
     * Returns label for this item.
     *
     * @return string The label string.
     */
    public function getLabel()
    {
        return $this->_label;
    }
}
