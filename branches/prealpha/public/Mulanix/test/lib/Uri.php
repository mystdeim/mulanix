<?php
class Test_Mnix_Uri extends Mnix_Uri
{
    public static function parse($data)
    {
        return self::_parse($data);
    }
    public static function parts($data)
    {
        return self::_parts($data);
    }
}