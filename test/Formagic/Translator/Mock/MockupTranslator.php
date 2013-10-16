<?php
class Formagic_Translator_Mock_MockupTranslator
{
    public function _($value)
    {
        return 'UnitTestMockup_' . $value;
    }

    public function myTranslate($value)
    {
        return 'UnitTestMockupMyTranslate' . $value;
    }
}