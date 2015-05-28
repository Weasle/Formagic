<?php
/**
 * Declares the ValueBag class.
 *
 * @category    PokerStrategy
 * @author      Florian Sonnenburg
 * @copyright   2015 HCE GmbH
 */

/**
 * Implementation of class Formagic_Item_Value_ValueBag
 *
 */
class Formagic_Item_Value_ValueBag extends ArrayObject
{
    /**
     * @var boolean
     */
    private $submitValueBag;

    /**
     * @param array $data
     * @param boolean $submitValueBag
     */
    public function __construct(array $data, $submitValueBag = false)
    {
        parent::__construct($data);

        $this->submitValueBag = $submitValueBag;
    }

    /**
     * @return boolean
     */
    public function isSubmitValueBag()
    {
        return $this->submitValueBag;
    }

    /**
     * @param boolean $submitValueBag
     */
    public function setSubmitValueBag($submitValueBag)
    {
        $this->submitValueBag = $submitValueBag;
    }


}
