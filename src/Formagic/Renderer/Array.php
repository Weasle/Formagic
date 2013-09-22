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
 * @package     Renderer
 * @author      Florian Sonnenburg
 * @copyright   Copyright (c) 2007-2009 Florian Sonnenburg
 * @license     http://www.formagic-php.net/license-agreement/   New BSD License
 */

/**
 * Returns form items in array form to be processed by custom display manager
 *
 * @category    Formagic
 * @package     Renderer
 * @author      Florian Sonnenburg
 * @copyright   Copyright (c) 2007-2009 Florian Sonnenburg
 * @version     $Id: Array.php 173 2012-05-16 13:19:22Z meweasle $
 */
class Formagic_Renderer_Array implements Formagic_Renderer_Interface
{
    /**
     * Result array
     * @var array
     */
    private $_result = array();

    /**
     * True if any item's validation failed. Will be added to the result array.
     * @var boolean
     */
    private $_hasErrors = false;

    /**
     * Internal string cache for hidden Formagic items.
     * @var string
     */
    private $_fmFields = '';

    /**
     * Returns array with all neccessary item information.
     *
     * @param Formagic $form Formagic main object.
     * @return array The array containing all items and errors.
     */
    public function render(Formagic $form)
    {
        $this->_assembleData($form->getItemHolder());
        $this->_result['hasErrors'] = $this->_hasErrors;
        $this->_result['action'] = $form->getFormAction();
        $this->_result['method'] = $form->getMethod();
        $this->_result['formagicFields'] = $this->_fmFields;
        return $this->_result;
    }

    /**
     * Collects item information to be put into the result array.
     *
     * Iterates over $container and descends recursively into nested
     * sub-containers.
     *
     * @param Formagic_Item_Container $container Item container.
     * @return void
     */
    private function _assembleData(Formagic_Item_Container $container)
    {
        $itemRes = array();
        foreach ($container as $key => $item) {
            // skip disabled items
            if ($item->isDisabled()) {
                continue;
            }

            // descend into nested container
            if ($item instanceOf Formagic_Item_Container) {
                $this->_assembleData($item);
                continue;
            }

            $name = $item->getName();
            if (strpos($name, 'fm_') === 0) {
                $this->_fmFields .= $item->getHtml();
                continue;
            }

            // HTML attributes
            $itemRes['attributes'] = $item->getAttributes();

            // common properties
            $itemRes['name'] = $name;

            // value
            $val = $item->getValue();
            $itemRes['value'] = $this->_sanitize($val);
            $unfilteredVal = $item->getUnfilteredValue();
            $itemRes['unfilteredValue'] = $this->_sanitize($unfilteredVal);

            //error status
            $rules = $item->getViolatedRules();
            $itemRes['error'] = array();
            foreach ($rules as $rule) {
                $itemRes['error'][] = $rule->getMessage();
                $this->_hasErrors = true;
            }

            // HTML input string
            $itemRes['html'] = $item->getHtml();

            // Label
            $itemRes['label'] = $item->getLabel();

            // has mandatory rule
            $itemRes['isMandatory'] = $item->hasRule('mandatory');

            // add item properties array to resultset
            $this->_result['items'][$name] = $itemRes;
        }
    }

    /**
     * Sanitizes a userland value.
     *
     * @param mixed $value Value
     * @return mixed Sanitized value
     */
    protected function _sanitize($value)
    {
        if (is_array($value)) {
            foreach($value as $key => $val) {
                $value[$key] = $this->_sanitize($val);
            }
        } else {
            $value = htmlspecialchars($value);
        }
        return $value;
    }
}