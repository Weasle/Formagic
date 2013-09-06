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
 * @package     Filter
 * @author      Florian Sonnenburg
 * @copyright   Copyright (c) 2009 Florian Sonnenburg
 * @license     http://www.formagic-php.net/license-agreement/   New BSD License
 */

/**
 * Filter class interface
 */
require_once('Interface.php');

/**
 * Uses a valid PHP callback fot the value transformation.
 *
 * Takes two constructor arguments: One the callback itself, two the arguments
 * for the callback.
 *
 * @category    Formagic
 * @package     Filter
 * @author      Florian Sonnenburg
 * @copyright   Copyright (c) 2009 Florian Sonnenburg
 * @version     $Id: Callback.php 173 2012-05-16 13:19:22Z meweasle $
 **/
class Formagic_Filter_Callback implements Formagic_Filter_Interface
{
    /**
     * A valid PHP callback
     * @var mixed
     */
    private $_callback;

    /**
     * Array of arguments for the callback
     * @var Array
     */
    private $_arguments = array();

    /**
     * Constructor
     *
     * @param mixed $callback A valid PHP callback
     * @param array $arguments Array of arguments for the callback
     */
    public function __construct($callback, array $arguments = null)
    {
        $this->_callback = $callback;
        if ($arguments) {
            $this->_arguments = $arguments;
        }
    }

    /**
     * Filters the value using the PHP callback.
     * 
     * @param mixed $value The value to be filtered
     * @return string The filtered value
     */
    public function filter($value)
    {
        // add value to arguments if none passed on instantiation
        if (!count($this->_arguments)) {
            $this->_arguments = array($value);
        // replace placeholder %VALUE% with actual value if arguments are passed
        } else {
            foreach ($this->_arguments as $key => $arg) {
                if ($arg == '%VALUE%') {
                    $this->_arguments[$key] = $value;
                }
            }
        }
        $res = call_user_func_array($this->_callback, $this->_arguments);
        return $res;
    }
}