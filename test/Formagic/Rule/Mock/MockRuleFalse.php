<?php
class Formagic_Rule_Mock_MockRuleFalse extends Formagic_Rule_Abstract
{
    public function validate($value)
    {
        return false;
    }
}
