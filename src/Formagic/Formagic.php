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
 * Formagic main class
 *
 * Highly extensible form-generator with various rendering options, form
 * validation and sanitation support.
 *
 * You can use the following options when creating a formagic instance:
 * <dl>
 *  <dt>attributes</dt><dd>Custom attributes of HTML form tag</dd>
 *  <dt>action</dt><dd>Form target action</dd>
 *  <dt>method</dt><dd>Either "post" or "get"</dd>
 *  <dt>name</dt><dd>Form name</dd>
 *  <dt>renderer</dt><dd>Name of the renderer to be used. Defaults to "Html".
 *  See {@link #setRenderer setRenderer()} for details.</dd>
 *  <dt>trackSubmission</dt><dd>Enable or disable detailed submission tracking.
 *  If enabled, Formagic will be able to track the submission status of all
 *  Formagic forms on a HTML page (having trackSubmission enabled). If disabled,
 *  the method {@link #isSubmitted isSubmitted()} will always return TRUE if any
 *  form on a page is submitted.<br />
 *  Defaults to TRUE. Disable only if submission tracking feature causes
 *  problems.</dd>
 *  <dt>translator</dt><dd>Translator implementation. See {@link #setTranslator
 *  setTranslator()} for details.</dd>
 * </dl>
 *
 * @package     Formagic
 * @author      Florian Sonnenburg
 * @since       0.1.0 First time introduced
 **/
class Formagic
{
    /**
     * Form submission method ("post" or "get")
     * @var string
     */
    protected $_method = 'post';

    /**
     * Array of submitted values
     * @var array
     */
    protected $_submitValues = array();

    /**
     * Submit methods supported by Formagic
     * @var array
     */
    protected $_supportedMethods = array('get', 'post');

    /**
     * Type of renderer used to display form
     * @see Formagic::setRenderer()
     * @var Formagic_Renderer_Interface
     */
    private $_renderer;

    /**
     * Enables or disables submission tracking
     * @var boolean
     */
    private $_trackSubmission = true;

    /**
     * Container item holding all items added to Formagic object
     * @var Formagic_Item_Container
     */
    private $_itemHolder;

    /**
     * Form name
     * @var string
     */
    private $_name = 'formagic';

    /**
     * Action for form tag
     * @var string
     **/
    private $_formAction;

    /**
     * Track submission item
     * @var Formagic_Item_Hidden
     */
    private $_submissionItem;

    /**
     * Formagic_Translator object
     * @var Formagic_Translator_Interface
     **/
    protected static $_translator;

    /**
     * Formagic version
     **/
    const VERSION           = '1.5.3-pl1';

    /**
     * Formagic API version
     **/
    const API_VERSION       = '2.5';

    /**
     * Constructor.
     *
     * @param array $options Array of options for the form.
     * @throws Formagic_Exception
     **/
    public function __construct(array $options = null)
    {
        if (isset($options['name'])) {
            $this->setName($options['name']);
            unset($options['name']);
        }

        // set item container of main Formagic object
        $this->_itemHolder = $this->createItem('Container', $this->_name);

        // process options
        if ($options) {
            $this->_setOptions($options);
        }

        switch($this->_method) {
            case 'get':
                $this->_submitValues =& $_GET;
                break;
            case 'post':
                $this->_submitValues =& $_POST;
                break;
        }

        $this->_init();
    }

    /**
     * Allows subclass initialization.
     *
     * @return void
     */
    public function _init()
    {
    }

    /**
     * Returns result for string casting of Formagic object.
     *
     * @return string The rendering result.
     **/
    public function __toString()
    {
        $str = $this->render();
        return $str;
    }

    /**
     * Proxies method calls to underlying container $this->_itemHolder.
     *
     * @param string $method Method name string.
     * @param array $args Array of method parameters.
     *
     * @throws Formagic_Exception if called method is not found in item holder
     *
     * @return mixed The container method result.
     */
    public function __call($method, $args)
    {
        if (!method_exists($this->_itemHolder, $method)) {
            throw new Formagic_Exception("Method $method does not exist in "
                . "Formagic or Item Holder");
        }
        return call_user_func_array(array($this->_itemHolder, $method), $args);
    }

    /**
     * Member overloading: Tries to return a form item with member's name.
     *
     * @param string $itemName Name of requested member.
     * @return Formagic_Item_Abstract Item object
     * @throws Formagic_Exception
     */
    public function __get($itemName)
    {
        return $this->_itemHolder->getItem($itemName);
    }

    /**
     * Will throw an Formagic_Exception, because member overloading is not allowed.
     *
     * @param string $key  Member name
     * @param mixed $value Member value
     * @return void
     * @throws Formagic_Exception
     */
    public function __set($key, $value)
    {
        throw new Formagic_Exception("Member '$key' cannot be overloaded.");
    }

    /**
     * Sets Formagic options.
     *
     * @param array $options Array of Formagic options
     * @throws Formagic_Exception If an option is not supported.
     * @return void
     */
    protected function _setOptions(array $options)
    {
        foreach($options as $name => $value) {
            switch($name) {
                case 'attributes':
                    $this->_itemHolder->setAttributes($value);
                    break;
                // set custom action
                case 'action':
                    $this->setFormAction($value);
                    break;
                // set custom renderer
                case 'renderer':
                    $this->setRenderer($value);
                    break;
                case 'translator':
                    $this->setTranslator($value);
                    break;
                // set submission method
                case 'method':
                    $this->setMethod($value);
                    break;
                // add hidden input for submission tracking if enabled
                case 'trackSubmission':
                    $this->setTrackSubmission($value);
                    break;
                // set default rules
                case 'rules':
                    if (!is_array($value)) {
                        $value = array($value);
                    }
                    foreach ($value as $rule) {
                        $this->_itemHolder->addRule($rule);
                    }
                    break;
                // set default filters
                case 'filters':
                    if (!is_array($value)) {
                        $this->_itemHolder->addFilter($value);
                    } else {
                        foreach($value as $filter => $args) {
                            if (is_numeric($filter)) {
                                $filter = $args;
                                $args = null;
                            }
                            $this->_itemHolder->addFilter($filter, $args);
                        }
                    }
                    break;
                default:
                    throw new Formagic_Exception("Option $value not supported");
            } // switch
        }
    }

    /**
     * Defines renderer for current Formagic object
     *
     * @param string|Formagic_Renderer_Interface $renderer Formagic_Renderer
     *      object or string with name of renderer class
     *
     * @throws Formagic_Exception if renderer is invalid
     *
     * @return Formagic Fluent interface
     */
    public function setRenderer($renderer)
    {
        if ($renderer instanceOf Formagic_Renderer_Interface) {
            $this->_renderer = $renderer;
            return $this;
        }

        if (!is_string($renderer)) {
            $message = 'Renderer name has to be a string or Formagic_Renderer_Interface.';
            throw new Formagic_Exception($message);
        }
        $class = 'Formagic_Renderer_' . $renderer;

        if (!class_exists($class)) {
            throw new Formagic_Exception('Could not load renderer class ' . $class);
        }
        $renderer = new $class();
        if (!($renderer instanceOf Formagic_Renderer_Interface)) {
            throw new Formagic_Exception('Renderer class is no instance of Formagic_Renderer_Interface');
        }

        $this->_renderer = $renderer;

        return $this;
    }

    /**
     * Returns the current renderer object.
     *
     * @return Formagic_Renderer_Interface The renderer object
     */
    public function getRenderer()
    {
        return $this->_renderer;
    }

    /**
     * Sets form name and thus name of trackSubmission item.
     *
     * @param string $name
     *
     * @return Formagic Fluent interface
     */
    public function setName($name)
    {
        $this->_name = $name;
        if ($this->_trackSubmission) {
            $this->_getTrackSubmissionItem()->addAttribute('name', 'fm_ts__' . $name);
        }
        return $this;
    }

    /**
     * Returns current form name
     *
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * Sets the form's submit method.
     *
     * @param string $method The new form submit method
     *
     * @throws Formagic_Exception if method is not supported
     *
     * @return Formagic Fluent interface
     */
    public function setMethod($method)
    {
        $method = strtolower($method);
        if (!in_array($method, $this->_supportedMethods)) {
            throw new Formagic_Exception();
        }
        $this->_method = strtolower($method);
        return $this;
    }

    /**
     * Returns the form method.
     *
     * @return string The form submit method.
     */
    public function getMethod()
    {
        return $this->_method;
    }

    /**
     * Enables or disables submission tracking.
     *
     * @param boolean $flag Track submission status
     *
     * @return Formagic Fluent interface
     */
    public function setTrackSubmission($flag)
    {
        $this->_trackSubmission = (bool)$flag;
        return $this;
    }

    /**
     * Returns submission tracking status.
     *
     * @return boolean Submission tracking status.
     */
    public function getTrackSubmission()
    {
        return $this->_trackSubmission;
    }

    /**
     * Sets translator object and method if present.
     *
     * $translator has to be either an array with two members, the first one
     * being the translator object and the second one the name of the
     * translator method, or a translator object. In the latter case the
     * translator method is assumed '$translatorObject->_($string)'.
     *
     * @param array|object|Formagic_Translator_Interface $translatorDefinition One of the following:
     *  <ul><li> Array with translator object and method</li>
     *  <li> any translator object</li>
     *  <li> Formagic_Translator object</li></ul>
     * @return Formagic Fluent interface
     */
    public static function setTranslator($translatorDefinition = null)
    {
        $method = '_';
        if ($translatorDefinition instanceOf Formagic_Translator_Interface) {
            self::$_translator = $translatorDefinition;
            return;

        } elseif (is_array($translatorDefinition)) {
            $object = $translatorDefinition[0];
            if (isset($translatorDefinition[1])) {
                $method = $translatorDefinition[1];
            }
        } elseif (is_object($translatorDefinition)) {
            $object = $translatorDefinition;
        } else {
            $object = new Formagic_Translator();
        }

        self::$_translator = new Formagic_Translator();
        self::$_translator->setCallback($object, $method);
    }

    /**
     * Returns translator object.
     *
     * If no translator is specified, an empty translator object is returned.
     *
     * @return Formagic_Translator_Interface The translator object
     */
    public static function getTranslator()
    {
        if (!self::$_translator) {
            self::setTranslator();
        }
        return self::$_translator;
    }

    /**
     * Returns form information string.
     *
     * @return string The form information string.
     */
    public function getInfo()
    {
        $numItems = $this->_itemHolder->count();
        $submitted = (int)$this->isSubmitted();
        $str = "<strong>Formagic Info</strong><br />\n"
               . "Items added: '$numItems'<br />\n"
               . "Is submitted: '$submitted'<br />\n"
               . "<br />\n";
        return $str;
    }

    /**
     * Creates and returns FormagicItem object
     *
     * Tries to load correct object class and creates new object. Returns object
     * if successful, throws Formagic_Exception if not
     *
     * @param string $type Item type string
     * @param string $name Item name
     * @param array $args Item options array
     *
     * @throws Formagic_Exception if item class not found
     *
     * @return Formagic_Item_Abstract New item instance
     */
    public static function createItem($type, $name, array $args = array())
    {
        $class = 'Formagic_Item_' . ucFirst($type);
        if (!class_exists($class)) {
            throw new Formagic_Exception('Class ' . $class . ' could not be found');
        }
        $obj = new $class($name, $args);
        return $obj;
    }

    /**
     * Returns root container.
     *
     * @return Formagic_Item_Container Item holder item
     */
    public function getItemHolder()
    {
        return $this->_itemHolder;
    }

    /**
     * Sets the form action URL.
     *
     * @param string $value Form action URL
     *
     * @throws Formagic_Exception if action value is not a string
     *
     * @return Formagic Fluent interface
     */
    public function setFormAction($value)
    {
        if (is_string($value)) {
            $this->_formAction = $value;
        } else {
            throw new Formagic_Exception('Action has to be string value');
        }
        return $this;
    }

    /**
     * Returns current form action
     *
     * @return string Form action string
     */
    public function getFormAction()
    {
        if (!$this->_formAction) {
            $this->_formAction = $_SERVER['PHP_SELF'];
        }
        return $this->_formAction;
    }

    /**
     * Checks if form is submitted and all rules apply
     *
     * Iterates through all items added to the form. If any rule is violated,
     * iteration is stopped. Returns true if no rules are violated.
     * The result of validate() is cached.
     *
     * @todo Think of a better solution for setValues() in line 611
     * @return boolean Validation result
     */
    public function validate()
    {
        // Assume that validation is negative if form is not submitted
        if (!$this->isSubmitted()) {
            return false;
        }

        // populate with submit values
        $this->setValues($this->_submitValues);
        return $this->_itemHolder->validate();
    }

    /**
     * Returns output generated by renderer
     *
     * Loads renderer class and calls renderer::fetch()
     *
     * @return string Renderer result
     */
    public function render()
    {
        if (!($this->_renderer instanceOf Formagic_Renderer_Interface)) {
            $this->setRenderer('Html');
        }

        // handle trackSubmission
        if ($this->_trackSubmission) {
            $tsItem = $this->_getTrackSubmissionItem();
            $this->_itemHolder->addItem($tsItem);
        }

        return $this->_renderer->render($this);
    }

    /**
     * Sets default values.
     *
     * @param array $values Form values
     * @return Formagic Fluent interface
     */
    public function setValues(array $values)
    {
        $this->_itemHolder->setValue($values);
        return $this;
    }

    /**
     * Returns array with values from all added items.
     *
     * @return array Array of form item values
     */
    public function getValues()
    {
        return $this->_itemHolder->getValue();
    }

    /**
     * Returns unvalidated and unfiltered submit values.
     *
     * @return array Values array
     */
    public function getRaw()
    {
        return $this->_submitValues;
    }

    /**
     * Checks if HTML form is submitted
     *
     * Check result is true for following rules:
     * - if submission tracking is enabled in Formagic options and the
     * submission variable is present
     * - if submission tracking is disabled, but either GET or POST values
     * (dependent on chosen submission method) are present
     *
     * @return boolean Submission status
     */
    public function isSubmitted()
    {
        // Return value of trackSubmission variable if tracking enabled
        if ($this->_trackSubmission) {
            $item = $this->_getTrackSubmissionItem();
            $name = $item->getAttribute('name');
            return !empty($this->_submitValues[$name]);
        }

        // If tracking disabled, guess submission status from $_POST/$_GET
        if (count($this->_submitValues)) {
            return true;
        }
        return false;
    }

    /**
     * Returns the track submission item.
     *
     * @return Formagic_Item_Hidden Track submission item
     */
    private function _getTrackSubmissionItem()
    {
        if(null === $this->_submissionItem)
        {
            $this->_submissionItem = $this->createItem('hidden',
                'fm_ts__' . $this->_name,
                array(
                    'value' => 1,
                    'ignore' => true,
                    'fixed' => true
                )
            );
        }
        return $this->_submissionItem;
    }
}
