<?php
/**
 * Mulanix Framework
 */
namespace Mnix;

require_once '_files/UriSub.php';

class LangMok
{
    public $short;
    public function __construct($short = null)
    {
        $this->short = $short;
    }
    public function load()
    {
        if ($this->short === 'ru' || $this->short === 'en') return true;
        else return false;
    }
}

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
            array('/ru/page1/', array('ru', 'page1'))/*,
            array('/?a=fg',     array()),
            array('/ru?df=df', array('ru')),
            array('/ru/?sf=f', array('ru'))*/
        );
    }
    /**
     * @dataProvider providerGetLang
     */
    public function testGetLang($request, $expected)
    {
        $uri = new UriSub();
        $uri->putUri($request);
        $uri->putLangObj(new LangMok);
        $lang = $uri->getLang();
        $this->assertEquals($expected, $lang);
    }
    public function providerGetLang()
    {
        return array(
            array(''  , false),
            array('ru', new LangMok('ru')),
            array('en', new LangMok('en')),
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
            array(''    , true),
            array('help', true),
            array('errr', false)
        );
    }
}