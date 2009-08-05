<?php
class Test_Mnix_UriTest extends PHPUnit_Framework_TestCase
{
    public function __construct($name = NULL, array $data = array(), $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
    }
    /**
     * Проверяем _parts()
     *
     * @dataProvider providerParts
     */
    public function testParts($data, $result)
    {
        $uri = new Test_Mnix_Uri();
        $this->assertEquals($uri->parts($data), $result);
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
     *
     * @dataProvider providerParse
     */
    public function testParse($data, $result)
    {
        $uri = new Test_Mnix_Uri();
        $this->assertEquals($uri->parse($data), $result);
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
            array('/ru/faq8/100', 4),
            array('/ru/faq8/term10/100', 5),
            array('/ru/faq8/100/course1', 5),
            array('/ru/faq8/0', 3),
            array('/ru/faq8/note5', 4),
            array('/ru/faq8/term5/note5', 4)
        );
    }
    /**
     * Проверяем _checkParam()
     *
     * @dataProvider providerCheckParam
     */
    public function testCheckParam($string,$regular, $result)
    {
        $uri = new Test_Mnix_Uri();
        $this->assertEquals($uri->checkParam($string,$regular,''), $result);
    }
    public function providerCheckParam()
    {
        return array(
            array('0','\d+', 0),
            array('dfdf', '\d+', false),
            array('dfdf123', '\d+', 123)
        );
    }
    /**
     * Проверяем как записываются параметры
     *
     * @dataProvider providerCheckParam2
     */
    public function testCheckParam2($request, $result)
    {
        $uri = new Test_Mnix_Uri();
        $uri->parse($request);
        $this->assertEquals($uri->getParam(), $result);
    }
    public function providerCheckParam2()
    {
        return array(
            array('', array('lang'=>'ru')),
            array('fags', array('lang'=>'ru')),
            array('faq8', array('lang'=>'ru','faq'=>8)),
            array('ru/faqs', array('lang'=>'ru')),
            array('en/faq1', array('lang'=>'en','faq'=>1)),
            array('en/faq1/course', array('lang'=>'en','faq'=>1)),
            array('en/faq1/course5', array('lang'=>'en','faq'=>1,'course'=>5)),
            array('en/faq1/course5/term10', array('lang'=>'en','faq'=>1,'course'=>5,'term'=>10)),
            array('en/faq1/term10', array('lang'=>'en','faq'=>1,'term'=>10)),
            array('en/faq1/10', array('lang'=>'en','faq'=>1,'note'=>10)),
            array('en/faq1/term10/note1000', array('lang'=>'en','faq'=>1,'term'=>10,'note'=>1000)),
        );
    }
}