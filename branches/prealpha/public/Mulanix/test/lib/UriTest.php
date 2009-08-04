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
    /**
     * Проверяем _parse()
     * @dataProvider providerParse
     */
    public function testParse($data, $result)
    {
        //var_dump(Test_Mnix_Uri::parse($data));
        $this->assertEquals(Test_Mnix_Uri::parse($data), $result);
    }
    public function providerParse()
    {
        return array(
            array('', 1),
            array('/', 1),
            array('//////////', 1),
            array('/ru', 1),
            array('/ru///', 1),
            array('/rU', 5),
            array('/ruL', 5),
            array('/faqs', 2),
            array('/ru/faqs', 2),
            array('/en//faqs///', 2),
            array('/faq1', 3),
            array('/en/faq1', 3),
            array('/en/faq8', 3),
            array('/ru/faq8/course1', 3),
            array('/ru/faq8/course', 5),
            array('/en/course2', 5),
            array('/ru/faq8/course1/term5', 3),
            array('/ru/faq8/course1/term', 5),
            array('/ru/faq8/term10', 3),
            array('/ru/faq8/100', 4)
        );
    }
}