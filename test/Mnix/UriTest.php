<?php
/**
 * Mulanix Framework
 */
namespace Mnix;

require_once '_files/UriSub.php';

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
            array('', array('/')),
            array('/ru', array('/', 'ru'))
        );
    }
    /**
     * @dataProvider providerParse
     */
    public function testParse($data, $expected)
    {
        UriSub::setDefaultLang('ru');
        $uri = new UriSub();
        $actual = $uri->parse($data);
        $this->assertEquals($expected, $actual->id);
        //$this->assertEquals($expected, $actual->lang);
    }
    public function providerParse()
    {
        return array(
            array('', 1),
            array('/', 1),
            array('////////', 1),
            array('/ru', 1),
            array('/en/', 1)/*,
            array('/help', 2),
            array('/en/help', 2)*/
        );
    }
}