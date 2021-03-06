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
 * Checks if value is same as an associated session value
 *
 * @package     Formagic\Rule
 * @author      Florian Sonnenburg
 * @since       1.2.0 First time introduced
 **/
class Formagic_Rule_SessionValue extends Formagic_Rule_Abstract
{
    /**
     * Default message
     * @var string
     **/
    protected $_message = 'Session validation failed';

    /**
     * Session object
     * @var Formagic_Session_Interface
     */
    protected $_session;

    /**
     * Session key holding value to be checked against
     * @var string
     */
    protected $_sessionKey;

    /**
     * Allows subclass initialization.
     *
     * Supported argument keys:
     * - session: Instance of Formagic_Session_Interface
     * - sessionKey: Session key holding the session value to be checked against
     *
     * @param array $arguments Array of arguments passed to __construct(
     * @throws Formagic_Exception If session object or session key is not valid
     * @return void
     */
    protected function _init(array $arguments)
    {
        if (empty($arguments['session'])
            || !($arguments['session'] instanceOf Formagic_Session_Interface)
        ) {
            throw new Formagic_Exception('Invalid session object');
        }
        $this->_session = $arguments['session'];

        if (empty($arguments['sessionKey'])) {
            throw new Formagic_Exception('Invalid session key');
        }
        $this->_sessionKey = $arguments['sessionKey'];
    }

    /**
     * Sets session key associated with this rule instance.
     *
     * @param string $sessionKey
     * @return Formagic_Rule_SessionValue Fluent interface
     *
     * @codeCoverageIgnore
     */
    public function setSessionKey($sessionKey)
    {
        $this->_sessionKey = $sessionKey;
        return $this;
    }

    /**
     * Returns session key associated with this rule instance.
     *
     * @return string
     *
     * @codeCoverageIgnore
     */
    public function getSessionKey()
    {
        return $this->_sessionKey;
    }

    /**
     * Returns session object the session value is stored in.
     *
     * @param Formagic_Session_Interface $session Session object
     * @return Formagic_Rule_SessionValue Fluent interface
     *
     * @codeCoverageIgnore
     */
    public function setSession(Formagic_Session_Interface $session)
    {
        $this->_session = $session;
        return $this;
    }

    /**
     * Returns session object the session value is stored in.
     *
     * @return string
     *
     * @codeCoverageIgnore
     */
    public function getSession()
    {
        return $this->_session;
    }

    /**
     * Compares session value against given value
     *
     * @param string $value Item value to be checked
     * @return boolean True if the item has a value
     **/
    public function validate($value)
    {
        // validate true if user did not enter a value
        if ($this->_isNoUserValue($value)) {
            return true;
        }

        if (!$this->_session->has($this->_sessionKey)) {
            return false;
        }

        if ($value !== $this->_session->get($this->_sessionKey)) {
            return false;
        }
        return true;
    }

}