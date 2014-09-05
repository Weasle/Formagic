<?php
class Formagic_Translator_Mock_MockupSubclass extends Formagic_Translator
{
    public function _($string, array $arguments = array())
    {
        return parent::_($string, $arguments);
    }

    public function translate($string, array $arguments = array())
    {
        return 'UnitTestMockup' . $string;
    }
}
