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
 * @copyright   2007-2015 Florian Sonnenburg
 * @license     http://www.formagic-php.net/license-agreement/   New BSD License
 */

/**
 * Represents a set of values for a form or a subset of a form (eg. container values) and can return if values come
 * from a form submit, rather than representing a set of default values.
 *
 * @package     Formagic\Item\Value
 * @author      Florian Sonnenburg
 * @since       1.5.5 First time introduced
 *
 * @codeCoverageIgnore Simple PoPo, not test-worthy
 */
class Formagic_Item_Value_ValueBag extends ArrayObject
{
    /**
     * Indicator about if the given values are submit values
     * @var boolean
     */
    private $submitValueBag;

    /**
     * Constructor
     *
     * @param array $data Data load
     * @param boolean $submitValueBag Set true if value bag represents submit values
     */
    public function __construct(array $data, $submitValueBag = false)
    {
        parent::__construct($data);

        $this->submitValueBag = $submitValueBag;
    }

    /**
     * Returns if value bag represents submit values
     *
     * @return boolean
     */
    public function isSubmitValueBag()
    {
        return $this->submitValueBag;
    }

    /**
     * Sets value bag's submit value representation status.
     *
     * @param boolean $submitValueBag
     */
    public function setSubmitValueBag($submitValueBag)
    {
        $this->submitValueBag = $submitValueBag;
    }


}
