<?php
require_once(dirname(__FILE__) . '/../../../Formagic/Rule/Abstract.php');

class Formagic_Rule_MockRuleFalse extends Formagic_Rule_Abstract
{
    public function validate($value)
    {
        return false;
    }
}
