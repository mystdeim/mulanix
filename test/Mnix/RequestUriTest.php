<?php
/**
 * Mulanix Framework
 */
namespace Mnix;

require_once '_files/RequestUriSub.php';

/**
 * Mulanix Framework
 *
 * @author deim
 */
class RequestUriTest extends \PHPUnit_Extensions_Database_TestCase
{
    protected function getConnection()
    {
        $this->connection = new \PDO('sqlite::memory:');
        $this->connection->query("
            CREATE TABLE uri (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                parent INTEGER
            );
        ");
        return $this->createDefaultDBConnection($this->connection, 'sqlite');
    }
    protected function getDataSet()
    {
        return $this->createFlatXMLDataSet(__DIR__ . '/_files/RequestUriSub/db.xml');
    }
    /**
     * @dataProvider providerExplode
     */
    public function testExplode($data, $expected)
    {
        $uri = new RequestUriSub();
        $this->assertEquals($expected, $uri->explode($data));
    }
    public function providerExplode()
    {
        return array(
            array('/'         , array()),
            array('/ru'       , array('ru')),
            array('/en///////', array('en')),
            array('/ru/page1/', array('ru', 'page1'))
        );
    }
    /**
     * @dataProvider providerGetLang
     */
    public function testGetLang($data, $flag, $lang)
    {
        $uri = new RequestUriSub();
        $uri->putQuery($data);
        //$uri->langExists();
        //$this->assertEquals($uri->langExists(), $flag);
        $langObj = $uri->getLang();
        //if ($flag) $this->assertEquals($lang->short, $langObj->short);
    }
    public function providerGetLang()
    {
        return array(
            array('/'       , false, null)/*,
            array('/ru'       , array('ru')),
            array('/en///////', array('en')),
            array('/ru/page1/', array('ru', 'page1'))*/
        );
    }
}