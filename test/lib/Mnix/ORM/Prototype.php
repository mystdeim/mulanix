<?php
class Test_Mnix_ORM_Prototype extends Mnix_ORM_Prototype
{
    public function load()
    {
        return parent::_load();
    }
    public function getState()
    {
        return array(
            'isLoad' => $this->_isLoad,
            'select' => $this->_select,
            'table' => $this->_table,
            'cortege' => $this->_cortege
        );
    }
}