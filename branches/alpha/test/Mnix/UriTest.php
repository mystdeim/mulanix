<?php
/**
 * Mulanix Framework
 */
namespace Mnix;

require_once '_files/UriSub.php';
require_once '_files/UriSub/LangMok.php';

/**
 * Mulanix Framework
 *
 * @author deim
 */
class UriTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider providerExplode
     */
    public function testExplode($data, $expected)
    {
        $uri = new UriSub();
        $this->assertEquals($expected, $uri->explode($data));
    }
    public function providerExplode()
    {
        return array(
            array(''          , array()),
            array('/ru'       , array('ru')),
            array('/en///////', array('en')),
            array('/ru/page1/', array('ru', 'page1'))
        );
    }
    /**
     * @dataProvider providerGetLang
     */
    public function testGetLang($request, $expected)
    {
        $uri = new UriSub();
        $uri->putUri($request);
        $uri->putLangObj(new UriSub\LangMok);
        $lang = $uri->getLang();
        $this->assertEquals($expected, $lang);
    }
    public function providerGetLang()
    {
        return array(
            array(''  , false),
            array('ru', new UriSub\LangMok('ru')),
            array('en', new UriSub\LangMok('en')),
            array('fr', false)
        );
    }

    /**
     * @dataProvider providerParse
     */
    public function testParse($request, $expected)
    {
        $uri = new UriSub();
        $uri->putUri($request);
        $this->assertEquals($expected, $uri->parse());
    }
    public function providerParse()
    {
        return array(
            array('/'    , true),
            array('/help', true),
            array('/errr', false),
            array('/page', true),
            array('/page1', true),
            array('/pageA', false)
        );
    }
    /**
     * @dataProvider providerParam
     */
    public function testParam($request, $get, $expected)
    {
        $uri = new UriSub();
        $uri->putUri($request);
        $uri->putGet($get);
        $uri->parse();
        $this->assertEquals($expected, $uri->param());
    }
    public function providerParam()
    {
        return array(
            array(''    , '', array()),
            array('help', '', array()),
            array('errr', '', array()),
            array('page', '', array('number'=>null)),
            array('page1', '', array('number'=>1)),
            array('page', array('id'=>10), array('number'=>null)),
            array('help', array('id'=>10), array('id'=>10))
        );
    }
}