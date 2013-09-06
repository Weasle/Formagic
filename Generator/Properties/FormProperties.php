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
 * @copyright   Copyright (c) 2007-2012 Florian Sonnenburg
 * @license     http://www.formagic-php.net/license-agreement/   New BSD License
 */
namespace Formagic\Generator\Properties;

use Formagic\Generator\Properties\ItemBag;

/**
 * Abstract superclass for Formagic items
 *
 * @category    Formagic
 * @package     Item
 * @author      Florian Sonnenburg
 * @copyright   Copyright (c) 2007-2012 Florian Sonnenburg
 * @version     $Id: $
 **/
class FormProperties implements PropertiesInterface
{
    /**
     * @var array
     */
    protected $baseDirs = array();

    /**
     * Item bag
     * @var ItemBag
     */
    protected $items;

    /**
     * @var array
     */
    protected $attributes;

    /**
     * @var string
     */
    protected $renderer;

    /**
     * @var string
     */
    protected $translator;

    /**
     * @var string
     */
    protected $formId;

    /**
     *
     */
    public function __construct()
    {
    }

    /**
     * @param array $baseDirs
     */
    public function setBaseDirs(array $baseDirs)
    {
        $this->baseDirs = $baseDirs;
    }

    /**
     * @return array
     */
    public function getBaseDirs()
    {
        return $this->baseDirs;
    }

    /**
     * @param string $formId
     */
    public function setFormId($formId)
    {
        $this->formId = $formId;
    }

    /**
     * @return string
     */
    public function getFormId()
    {
        return $this->formId;
    }

    /**
     * @param array $attributes
     */
    public function setAttributes($attributes)
    {
        $this->attributes = $attributes;
    }

    /**
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * @param ItemBag $items
     */
    public function setItems(ItemBag $items)
    {
        $this->items = $items;
    }

    /**
     * @return ItemBag
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param string
     */
    public function setRenderer($renderer)
    {
        $this->renderer = $renderer;
    }

    /**
     * @return string
     */
    public function getRenderer()
    {
        return $this->renderer;
    }

    /**
     * @param string $translator
     */
    public function setTranslator($translator)
    {
        $this->translator = $translator;
    }

    /**
     * @return string
     */
    public function getTranslator()
    {
        return $this->translator;
    }
}
