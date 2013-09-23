<?php
class Formagic_Rule_Mock_MockRuleTrue extends Formagic_Rule_Abstract
{
    public function validate($value)
    {
        return true;
    }
}
