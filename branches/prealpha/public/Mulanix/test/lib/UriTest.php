<?php
class Test_Mnix_UriTest extends PHPUnit_Framework_TestCase
{
    public function __construct($name = NULL, array $data = array(), $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
    }
    /**
     * Проверяем _parts()
     * @dataProvider providerParts
     */
    public function testParts($data, $result)
    {
        $this->assertEquals(Test_Mnix_Uri::parts($data), $result);
    }
    public function providerParts()
    {
        return array(
            array('', array('/')),
            array('/', array('/')),
            array('//////////', array('/')),
            array('/page', array('/', 'page')),
            array('/page/', array('/', 'page')),
            array('/page////////', array('/', 'page')),
            array('/page1/page2', array('/', 'page1', 'page2')),
            array('/page1/page2////page3////', array('/', 'page1', 'page2', 'page3')),
            array('/page1/page2////7/', array('/', 'page1', 'page2', '7'))
        );
    }
}