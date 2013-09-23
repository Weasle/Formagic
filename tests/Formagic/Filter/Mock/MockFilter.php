<?php
/**
 * Class Formagic_Filter_Mock_MockFilter
 */
class Formagic_Filter_Mock_MockFilter implements Formagic_Filter_Interface
{
    const FILTERED_VALUE = 'filtered mock value';

    public function filter($value) {
        return self::FILTERED_VALUE;
    }
}