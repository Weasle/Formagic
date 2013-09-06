<?php
Formagic::loadClass('Formagic_Filter_Interface');

class Formagic_Filter_MockFilter2 implements Formagic_Filter_Interface
{
    public function filter($value) {
        return $value;
    }
}