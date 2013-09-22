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
 * @copyright   Copyright (c) 2007-2011 Florian Sonnenburg
 * @license     http://www.formagic-php.net/license-agreement/   New BSD License
 */

/**
 * Returns rendered HTML form. Tables are used to place form elements.
 *
 * @category    Formagic
 * @package     Renderer
 * @author      Florian Sonnenburg
 * @copyright   Copyright (c) 2007-2011 Florian Sonnenburg
 * @version     $Id: Html.php 173 2012-05-16 13:19:22Z meweasle $
 **/
class Formagic_Renderer_Html implements Formagic_Renderer_Interface
{
    /**
     * Form wrapping template array
     * @var array
     **/
    protected $_formWrapperTemplate =
'
<!-- formagic HTML renderer start
============================= -->
<form action="%ACTION%" method="%METHOD%"%ATTRIBUTES%>
    %HIDDENS%
    %CONTAINER%
</form>
<!-- formagic HTML renderer end
=========================== -->
';

    /**
     * Container wrapping template array
     * @var array
     **/
    protected $_containerWrapperTemplate = array('' =>
    '<table border="0" cellpadding="0" cellspacing="0"%ATTRIBUTES%>
        <!-- form rows -->%ROWS%
    </table>'
    );

    /**
     * Template for rows containing containers
     * @var array
     **/
    protected $_containerRowTemplate = array('' =>
        '
        <tr>
            <td colspan="2">
                <!-- Container -->
                %CONTAINER%</td>
        </tr>'
    );

    /**
     * Container label template array
     * @var array
     */
    protected $_containerLabelTemplate = array('' => '');

    /**
     * Template for rows containing normal items array
     * @var array
     **/
    protected $_itemRowTemplate = array('' =>
        '
        <tr>
            <td>%LABEL%</td>
            <td>%ERROR%
                <!-- Input -->%INPUT%</td>
        </tr>'
    );

    /**
     * Template for displaying the item label array
     * @var array
     **/
    protected $_itemLabelTemplate = array('' =>
        '<!-- Label --><label for="%ID%"%ERRORCLASS%>%LABEL%%MANDATORYMARKER%</label>'
    );

    /**
     * Array of templates for item's error wrapper (list open tag)
     * @var array
     */
    protected $_itemErrorWrapperTemplate = array('' =>
        '<!-- Error --><ul%ERRORCLASS%>%ERRORS%</ul>'
    );

    /**
     * Array of templates for displaying the item error string (list elements)
     * @var array
     **/
    protected $_itemErrorTemplate = array('' =>
        '<li>%ERRORMESSAGE%</li>'
    );

    /**
     * Error class name
     * @var string
     */
    protected $_errorClass = 'formagicError';

    /**
     * HTML string for mandatory fields array
     * @var array
     **/
    protected $_mandatoryMarkerTemplate = array('' =>
        ' <span class="mandatory">*</span>'
    );

    /**
     * Hidden inputs string
     * @var string
     **/
    protected $_hiddenString = '';

    /**
     * Translator object
     * @var Formagic_Translator
     **/
    protected $_translator;

    /**
     * Sets the translator object for this renderer instance
     *
     * @param Formagic_Translator $translator Translator instance
     */
    public function __construct(Formagic_Translator $translator = null)
    {
        if (is_null($translator)) {
            $translator = Formagic::getTranslator();
        }
        $this->_translator = $translator;
    }

    /**
     * Returns current translator instance.
     *
     * @return Formagic_Translator Translator object
     */
    public function getTranslator()
    {
        return $this->_translator;
    }

    /**
     * Sets error CSS class.
     *
     * This css class is per default added to the label and error list
     * tag of items that did not pass validation.
     *
     * @param string $errorClass New error class
     * @return Formagic_Renderer_Html Fluent interface
     */
    public function setErrorClass($errorClass)
    {
        $this->_errorClass = $errorClass;
        return $this;
    }

    /**
     * Sets new template for rows that hold sub-containers
     * (tr/td tags by default).
     *
     * Supported placeholders:
     *  - %CONTAINER%: HTML for the subcontainer, including it's wrapper
     *
     * @see setContainerLabelTemplate()
     * @see setContainerWrapperTemplate()
     * @param string $template Template string
     * @param string|Formagic_Item_Container $container Optional. Defines this
     *      template only for a specific container.
     * @return Formagic_Renderer_Html Fluent interface
     */
    public function setContainerRowTemplate($template, $container = '')
    {
        $this->_setTemplate($this->_containerRowTemplate, $template, $container);
        return $this;
    }

    /**
     * Returns the template for rows that hold sub-containers.
     *
     * @param string|Formagic_Item_Container $container Optional. Returns a
     *      template defined for one specific container.
     * @return string Template string
     */
    public function getContainerRowTemplate($container = '')
    {
        return $this->_getTemplate($this->_containerRowTemplate, $container);
    }

    /**
     * Sets wrapper template for containers
     * (opening/closing table tag by default).
     *
     * Available placeholders:
     * - %ROWS%: Rendered rows as HTML, including row wrapper
     * - %ATTRIBUTES%: Assembled attributes string as HTML
     * - %LABEL%: Rendered container label as HTML
     *
     * @see setContainerLabelTemplate()
     * @see setContainerRowTemplate()
     * @param string $template Template string
     * @param string|Formagic_Item_Container $container Optional. Defines this
     *      template only for a specific container.
     * @return Formagic_Renderer_Html Fluent interface
     */
    public function setContainerWrapperTemplate($template, $container = '')
    {
        $this->_setTemplate($this->_containerWrapperTemplate, $template, $container);
        return $this;
    }

    /**
     * Returns wrapper template for containers.
     *
     * @param string $container Optional. Returns a template defined for
     *      one specific item with name $name.
     * @return string Template string
     */
    public function getContainerWrapperTemplate($container = '')
    {
        return $this->_getTemplate($this->_containerWrapperTemplate, $container);
    }

    /**
     * Sets container label template (empty by default).
     *
     * Available placeholders:
     * - %LABEL%: Label string
     *
     * @see setContainerWrapperTemplate()
     * @see setContainerRowTemplate()
     * @param string $template Template string
     * @param string|Formagic_Item_Container $container Optional. Defines this
     *      template only for a specific container (name or container object).
     * @return Formagic_Renderer_Html Fluent interface
     */
    public function setContainerLabelTemplate($template, $container = '')
    {
        $this->_setTemplate($this->_containerLabelTemplate, $template, $container);
        return $this;
    }

    /**
     * Returns container label template.
     *
     * @param string|Formagic_Item_Container $container Optional. Returns a
     *      template defined for one specific container.
     * @return string Template string
     */
    public function getContainerLabelTemplate($container = '')
    {
        return $this->_getTemplate($this->_containerLabelTemplate, $container);
    }

    /**
     * Sets form wrapper template (opening/closing form tag by default).
     *
     * Available placeholders:
     * - %ACTION%: Form action string
     * - %METHOD%: Form method string
     * - %ATTRIBUTES%: Assembled tag attributes string
     * - %HIDDENS%: Rendered hidden inputs
     * - %CONTAINER%: Rendered HTML of item holder container
     *
     * @param string $template Template string
     * @return Formagic_Renderer_Html Fluent interface
     */
    public function setFormWrapperTemplate($template)
    {
        $this->_formWrapperTemplate = $template;
        return $this;
    }

    /**
     * Returns form wrapper tag.
     *
     * @return string Template string
     */
    public function getFormWrapperTemplate()
    {
        return $this->_formWrapperTemplate;
    }

    /**
     * Sets template for rows containing input items (tr/td tags by default).
     *
     * Available placeholders:
     * - %LABEL%: Item label string
     * - %ERROR%: Assembled error list (HTML)
     * - %ERRORCLASS%: CSS class attribute with error class (eg. ' class="formagicError"')
     * - %INPUT%: Input HTML
     *
     * @see setItemLabelTemplate()
     * @see setItemErrorTemplate()
     * @param string $template Template string
     * @param string|Formagic_Item_Abstract $item Optional. Defines this
     *      template only for a specific item (name or item object).
     * @return Formagic_Renderer_Html Fluent interface
     */
    public function setItemRowTemplate($template, $item = '')
    {
        $this->_setTemplate($this->_itemRowTemplate, $template, $item);
        return $this;
    }

    /**
     * Returns item row template.
     *
     * @param string|Formagic_Item_Abstract $item Optional. Returns a template
     *      defined for one specific item.
     * @return string Template string
     */
    public function getItemRowTemplate($item = '')
    {
        return $this->_getTemplate($this->_itemRowTemplate, $item);
    }

    /**
     * Sets label template for a single item (label tag by default).
     *
     * Available placeholders:
     * - %LABEL%: Label string defined for item
     * - %ID%: Value of Item's HTML ID attribute
     * - %MANDATORYMARKER%: Marker for items with mandatory rule
     *
     * @see setItemRowTemplate()
     * @param string $template Template string
     * @param string|Formagic_Item_Abstract $item Optional. Defines this
     *      template only for a specific item (name or item object).
     * @return Formagic_Renderer_Html Fluent interface
     */
    public function setItemLabelTemplate($template, $item = '')
    {
        $this->_setTemplate($this->_itemLabelTemplate, $template, $item);
        return $this;
    }

    /**
     * Returns label template for an item.
     *
     * @param string|Formagic_Item_Abstract $item Optional. Returns a template
     *      defined for one specific item.
     * @return string Template string
     */
    public function getItemLabelTemplate($item = '')
    {
        return $this->_getTemplate($this->_itemLabelTemplate, $item);
    }

    /**
     * Sets a new template for a single error message (HTML LI tag by default)
     *
     * Available placeholders:
     * - %ERRORMESSAGE%: Message string returned by violated rule
     *
     * @param string $template Template string
     * @param string|Formagic_Item_Abstract $item Optional. Defines this
     *      template only for a specific item (name or item object).
     * @return Formagic_Renderer_Html Fluent interface
     */
    public function setItemErrorTemplate($template, $item = '')
    {
        $this->_setTemplate($this->_itemErrorTemplate, $template, $item);
        return $this;
    }

    /**
     * Returns single error message template
     *
     * @param string|Formagic_Item_Abstract $item Optional. Returns a template
     *      defined for one specific item.
     * @return string Template string
     */
    public function getItemErrorTemplate($item = '')
    {
        return $this->_getTemplate($this->_itemErrorTemplate, $item);
    }

    /**
     * Sets error wrapper template (opening and closing list tags by default).
     *
     * Supported placeholders:
     * - %ERRORS%: Rendered list of item errors
     *
     * @param string $template Template string
     * @param string|Formagic_Item_Abstract $item Optional. Defines this
     *      template only for a specific item (name or item object).
     * @return Formagic_Renderer_Html Fluent interface
     */
    public function setItemErrorWrapperTemplate($template, $item = '')
    {
        $this->_setTemplate($this->_itemErrorWrapperTemplate, $template, $item);
        return $this;
    }

    /**
     * Returns error wrapper template for item errors.
     *
     * @param string|Formagic_Item_Abstract $item Optional. Returns a template
     *      defined for one specific item.
     * @return string Template string
     */
    public function getItemErrorWrapperTemplate($item = '')
    {
        return $this->_getTemplate($this->_itemErrorWrapperTemplate, $item);
    }

    /**
     * Sets marker string for items that are marked mandatory
     * (asterisk by default).
     *
     * @param string $template Template string
     * @param string|Formagic_Item_Abstract $item Optional. Item this template
     * is to be defined for.
     * @return Formagic_Renderer_Html Fluent interface
     */
    public function setMandatoryMarkerTemplate($template, $item = '')
    {
        $this->_setTemplate($this->_mandatoryMarkerTemplate, $template, $item);
        return $this;
    }

    /**
     * Returns marker string for items marked as mandatory.
     *
     * @param string|Formagic_Item_Abstract $item Optional. Returns a template
     *      defined for one specific item.
     * @return string Template string
     */
    public function getMandatoryMarkerTemplate($item = '')
    {
        return $this->_getTemplate($this->_mandatoryMarkerTemplate, $item);
    }

    /**
     * Returns form HTML string
     *
     * @param Formagic $form Formagic object to be rendered.
     * @return string The rendered HTML string
     */
    public function render(Formagic $form)
    {
        // init hidden input rendering
        $this->_hiddenString = '';

        $attributeStr = $form->getAttributeStr();

        // prototype a root level item holder container without attributes
        $itemHolderClone = clone $form->getItemHolder();
        $itemHolderClone
            ->setRequiredAttributes(array())
            ->setAttributes(array());

        $content = $this->_renderContainer($itemHolderClone);
        $str = str_replace(
            array(
                '%ACTION%',
                '%METHOD%',
                '%ATTRIBUTES%',
                '%HIDDENS%',
                '%CONTAINER%'
            ),
            array(
                $form->getFormAction(),
                $form->getMethod(),
                $attributeStr,
                $this->_hiddenString,
                $content
            ),
            $this->getFormWrapperTemplate());
        return $str;
    }

    /**
     * Adds hidden fields to form HTML string.
     *
     * @param Formagic_Item_Hidden $item Hidden item to be rendered.
     */
    protected function _addHiddenItem(Formagic_Item_Hidden $item)
    {
        $tpl = '<input type="hidden" value="%s"%s />'. "\n    ";
        $this->_hiddenString .= sprintf(
            $tpl,
            htmlspecialchars($item->getValue()),
            $item->getAttributeStr()
        );
    }

    /**
     * Returns HTML for all items of a container (recursively)
     *
     * @param Formagic_Item_Container $container Container to be rendererd
     * @return string HTML string
     */
    protected function _renderContainer(Formagic_Item_Container $container)
    {
        $rows = '';
        $this->_errorClassHtml = '';
        foreach ($container->getItems() as $item) {

            // skip disabled inputs
            if ($item->isDisabled()) {
                continue;
            }

            // Handle containers recursively
            if ($item instanceOf Formagic_Item_Container) {
                // render fake input string from container items
                $subContainerRows = $this->_renderContainer($item);

                // build row the sub-container will go into
                $rows .= str_replace(
                    '%CONTAINER%',
                    $subContainerRows,
                    $this->getContainerRowTemplate($item)
                );
                continue;
            }

            // Catch hiddens and continue to next input
            if ($item->isHidden()) {
                $this->_addHiddenItem($item);
                continue;
            }

            // Error message and class
            list ($errorString, $errorClass) = $this->_getErrorProperties($item);

            // Render label
            $itemLabel = $this->_getItemLabel($item);

            // Render item row string
            $rows .= str_replace(
                array('%LABEL%', '%ERROR%', '%ERRORCLASS%', '%INPUT%'),
                array($itemLabel, $errorString, $errorClass, $item->getHtml()),
                $this->getItemRowTemplate($item));
        }

        // build container content including wrapping HTML
        $containerLabel = $this->_getContainerLabel($container);
        $res = str_replace(
            array('%ROWS%', '%ATTRIBUTES%', '%LABEL%'),
            array($rows, $container->getAttributeStr(), $containerLabel),
            $this->getContainerWrapperTemplate($container)
        );

        return $res;
    }

    /**
     * Sets a template string for an item to a template pool.
     *
     * @param array $templateArray Template pool new template is to be added to
     * @param string $template New template string
     * @param string|Formagic_Item_Abstract $item
     */
    protected function _setTemplate(&$templateArray, $template, $item)
    {
        if ($item instanceOf Formagic_Item_Abstract) {
            $itemName = $item->getName();
        } else {
            $itemName = (string)$item;
        }

        $templateArray[$itemName] = $template;
    }

    /**
     * Returns a template for a specific item from a template array.
     *
     * @param array $templateArray Pool of templates to choose from.
     * @param string|Formagic_Item_Abstract $item
     * @return string Template string
     */
    private function _getTemplate($templateArray, $item)
    {
        if ($item instanceOf Formagic_Item_Abstract) {
            $itemName = $item->getName();
        } else {
            $itemName = (string)$item;
        }

        // no specific template found -> use default one
        if (!isset($templateArray[$itemName])) {
            $itemName = '';
        }

        return $templateArray[$itemName];
    }

    /**
     * Returns the rendered error list and HTML class attribute.
     *
     * Returns empty array if item validated ok.
     *
     * @param Formagic_Item_Abstract $item
     * @return array Error list and class attribute. Example:
     * <code>
     * array(
     *      '<ul>
     *          <li>Please enter a value.</li>
     *       </ul>',
     *      ' class="formagicError"')
     * </code>
     */
    protected function _getErrorProperties(Formagic_Item_Abstract $item)
    {
        // skip all if no errors occured
        $rules = $item->getViolatedRules();
        if (!count($rules)) {
            return array('', '');
        }

        $itemName = $item->getName();

        // assemble error message string
        $errors = '';
        $errorTemplate = $this->getItemErrorTemplate($itemName);
        foreach ($rules as $rule) {
            $errors .= str_replace(
                '%ERRORMESSAGE%',
                $rule->getMessage(),
                $errorTemplate
            );
        }

        // wrap error message string
        $errorWrapper = $this->getItemErrorWrapperTemplate($itemName);
        $errorString = str_replace(
            array(
                '%ERRORS%',
            ), array(
                $errors,
            ),
            $errorWrapper
        );
        $errorClass = ' class="' . $this->_errorClass . '"';
        return array($errorString, $errorClass);
    }

    /**
     * Returns rendered item label and template.
     *
     * Returns a non-breakin-space HTML entity if no item label is provided.
     *
     * @param Formagic_Item_Abstract $item Input item
     * @return string Item label and template
     */
    protected function _getItemLabel(Formagic_Item_Abstract $item)
    {
        $itemLabel = $item->getLabel();
        if (empty($itemLabel)) {
            return '&nbsp;';
        }
        $itemId = $item->getAttribute('id');
        $marker = $item->hasRule('mandatory')
            ? $this->getMandatoryMarkerTemplate($item)
            : '';
        $label = str_replace(
            array('%LABEL%', '%ID%', '%MANDATORYMARKER%'),
            array($this->_translator->_($itemLabel), $itemId, $marker),
            $this->getItemLabelTemplate($item));

        return $label;
    }

    /**
     * Renders a container label into the container label template.
     *
     * Returns empty string if no container label is defined.
     *
     * @param Formagic_Item_Container $container Container item
     * @return string Container label and template string
     */
    protected function _getContainerLabel(Formagic_Item_Container $container)
    {
        $label = $container->getLabel();
        if (empty($label)) {
            return '';
        }
        $label = str_replace(
            '%LABEL%',
            $this->_translator->_($label),
            $this->getContainerLabelTemplate($container));

        return $label;
    }
}
