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
 * Uses a valid PHP callback fot the value transformation.
 *
 * Takes two constructor arguments: One the callback itself, two the arguments
 * for the callback.
 *
 * @package     Formagic\Filter
 * @author      Florian Sonnenburg
 * @since       2009 First time introduced
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
    private $_arguments;

    /**
     * Constructor
     *
     * @param mixed $callback A valid PHP callback
     * @param array $arguments Array of arguments for the callback
     */
    public function __construct($callback, array $arguments = array())
    {
        $this->_callback = $callback;
        $this->_arguments = $arguments;
    }

    /**
     * Filters the value using the PHP callback.
     *
     * @param mixed $value The value to be filtered
     * @return string The filtered value
     */
    public function filter($value)
    {
        if (empty($this->_arguments)) {
            // add value to arguments if none passed on instantiation
            $arguments = array($value);
        } else {
            // replace placeholder %VALUE% with actual value if arguments are passed
            $arguments = $this->_arguments;
            foreach ($arguments as $key => $arg) {
                if ($arg == '%VALUE%') {
                    $arguments[$key] = $value;
                }
            }
        }
        $res = call_user_func_array($this->_callback, $arguments);
        return $res;
    }
}
