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
 * @copyright   Copyright (c) 2013 Florian Sonnenburg
 **/
class Formagic_Session_Php implements Formagic_Session_Interface
{
    /**
     * Partition of PHP session
     * @var array
     */
    private $_session;

    /**
     * Constructor
     *
     * @param string $namespace Namespace string for this Formagic form
     */
    public function __construct($namespace)
    {
        // @codeCoverageIgnoreStart
        if (!isset($_SESSION)) {
            session_start();
        }
        // @codeCoverageIgnoreEnd
        $this->_session =& $_SESSION[$namespace];

        // initialize namespace if not already present
        if (!is_array($this->_session)) {
            $this->_session = array();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function get($key)
    {
        if (!$this->has($key)) {
            throw new Formagic_Exception_SessionException(
                'Key ' . $key . ' does not exist in session'
            );
        }
        return $this->_session[$key];
    }

    /**
     * {@inheritdoc}
     */
    public function has($key)
    {
        return array_key_exists($key, $this->_session);
    }

    /**
     * {@inheritdoc}
     */
    public function purge()
    {
        unset($this->_session);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function remove($key)
    {
        unset($this->_session[$key]);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function set($key, $value)
    {
        $this->_session[$key] = $value;

        return $this;
    }
}
