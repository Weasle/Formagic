<?php
class Formagic_Translator_Mock_MockupSubclass extends Formagic_Translator
{
    public function _($value)
    {
        return 'UnitTestMockup' . $value;
    }
}
