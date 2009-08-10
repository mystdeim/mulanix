<?php
class Test_Mnix_Uri extends Mnix_Uri
{
    protected $_table = 'mnix_test_uri2';
    public function parse($data)
    {
        return $this->_parse($data);
    }
    public function parts($data)
    {
        return $this->_parts($data);
    }
    public function checkParam($string, $regular, $name)
    {
        return $this->_checkParam($string, $regular, $name);
    }
}