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
 * @package     Session
 * @author      Florian Sonnenburg
 * @copyright   Copyright (c) 2007-2013 Florian Sonnenburg
 * @license     http://www.formagic-php.net/license-agreement/   New BSD License
 */

/**
 * Formagic session interface
 *
 * @category    Formagic
 * @package     Session
 * @author      Florian Sonnenburg
 * @copyright   Copyright (c) 2007-2013 Florian Sonnenburg
 * @version     $Id: Interface.php 173 2012-05-16 13:19:22Z meweasle $, $Revision: 70 $
 **/
interface Formagic_Session_Interface
{
    /**
     * Checks if a value is present in session.
     *
     * This method will also return a positive result if the value is empty
     * (null, empty string false, int 0).
     *
     * @param string $key Variable key
     * @return boolean
     */
    public function has($key);

    /**
     * Returns a value from session storage
     *
     * @param string $key Session key
     *
     * @throws Formagic_Exception_SessionException if value not exists
     *
     * @return mixed Value from session storage
     */
    public function get($key);

    /**
     * Saves a value into session storage
     *
     * @param string $key Session key
     * @param mixed $value Value to be saved into session
     *
     * @return Formagic_Session_Interface Fluent interface
     */
    public function set($key, $value);

    /**
     * Removes a value from session storage.
     *
     * Will silently do nothing if key does not exist.
     *
     * @param string $key Session key
     * @return Formagic_Session_Interface Fluent interface
     */
    public function remove($key);

    /**
     * Clears all Formagic value from the session.
     *
     * @return Formagic_Session_Interface Fluent interface
     */
    public function purge();
}
