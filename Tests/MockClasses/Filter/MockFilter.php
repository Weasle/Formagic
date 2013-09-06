<?php
Formagic::loadClass('Formagic_Filter_Interface');

class Formagic_Filter_MockFilter implements Formagic_Filter_Interface
{
    const FILTERED_VALUE = 'filtered mock value';

    public function filter($value) {
        return self::FILTERED_VALUE;
    }
}