<?php
class Formagic_Item_MockItem extends Formagic_Item_Abstract
{
    /**
     *
     */
    const HTML_OUTPUT = 'myHtmlOutput';
    const HTML_OUTPUT_READONLY = 'myHtmlOutputReadonly';

    /**
     * 
     * @return string
     */
    public function getHtml() 
    {
        if($this->_isReadonly) {
            return self::HTML_OUTPUT_READONLY;
        } else {
            return self::HTML_OUTPUT;
        }
    }
    
    /**
     *
     * @return string 
     */
    public function getParentHtml()
    {
        return parent::getHtml();
    }
}