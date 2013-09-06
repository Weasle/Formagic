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
 * @package     Generator
 * @author      Florian Sonnenburg
 * @copyright   Copyright (c) 2007-2012 Florian Sonnenburg
 * @license     http://www.formagic-php.net/license-agreement/   New BSD License
 */

namespace Formagic\Generator\Properties;

/**
 * Formagic generator rule bag
 *
 * @category    Formagic
 * @package     Generator
 * @author      Florian Sonnenburg
 * @copyright   Copyright (c) 2007-2012 Florian Sonnenburg
 * @version     $Id: RuleBag.php 183 2012-11-17 13:34:27Z meweasle $
 **/
class RuleBag implements \IteratorAggregate
{
    /**
     * List of rules
     * @var array
     */
    protected $_rules = array();

    /**
     * Adds a new rule to the rule list.
     *
     * @param RuleProperties $rule
     * @return RuleBag Fluent interface
     */
    public function addRule(RuleProperties $rule)
    {
        $ruleName = $rule->getType();
        $this->_rules[$ruleName] = $rule;
        return $this;
    }

    /**
     * Sets new rule list.
     *
     * @param array $rules Array of rule properties
     * @return RuleProperties Fluent interface
     */
    public function setRules(array $rules)
    {
        $this->_rules = $rules;
        return $this;
    }

    /**
     * Returns currently set rule list.
     *
     * @return array Array of rule properties
     */
    public function getRules()
    {
        return $this->_rules;
    }

    /**
     * {@inheritDoc}
     */
    public function getIterator()
    {
        $arrayIterator = new \ArrayIterator($this->_rules);

        return $arrayIterator;
    }
}
