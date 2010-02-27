<?php
/**
 * Mulanix Framework
 */
namespace Mnix;

require_once '_files/UriSub.php';
/*require_once 'ActiveRecord/_files/CollectionSub.php';
require_once '_files/ActiveRecordSub/Person.php';
require_once '_files/ActiveRecordSub/Car.php';
require_once '_files/ActiveRecordSub/Comp.php';
require_once '_files/ActiveRecordSub/House.php';*/
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
            array('/ru', array('/', 'ru')),
            array('/ru', array('/', 'ru'))
        );
    }
}