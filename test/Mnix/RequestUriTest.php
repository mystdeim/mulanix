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
        var_dump('getConnection');
        $this->connection = new \PDO('sqlite::memory:');
        $this->connection->query("
            CREATE TABLE mnix_uri (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                parent INTEGER
            );
        ");
        $this->connection->query("
            CREATE TABLE mnix_lang (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                short VARCHAR(2)
            );
        ");
        Db::putDriver('memory', $this->connection);
        $db = Db::connect('memory');
        ActiveRecord::setDb($db);
        return $this->createDefaultDBConnection($this->connection, 'sqlite');
    }
    protected function getDataSet()
    {
        var_dump('getDataSet');
        return $this->createFlatXMLDataSet(__DIR__ . '/_files/RequestUriSub/db.xml');
    }
    /**
     * @dataProvider providerExplode
     */
    public function testExplode($data, $expected)
    {
        //foreach ($this->connection->query('SELECT * FROM mnix_lang') as $row) var_dump($row);
        /*Db::putDriver('memory', $this->connection);
        $db = Db::connect('memory');
        ActiveRecord::setDb($db);*/

        $uri = new RequestUriSub();
        $this->assertEquals($expected, $uri->explode($data));
    }
    public function providerExplode()
    {
        return array(
            array('/'         , array())/*,
            array('/ru'       , array('ru')),
            array('/en///////', array('en')),
            array('/ru/page1/', array('ru', 'page1')),
            array('/?aa=bb', array()),
            array('/ru?aa=bb', array('ru')),
            array('/ru/page1?aa=bb', array('ru', 'page1'))*/
        );
    }
    /**
     * @dataProvider providerGetLang
     */
    public function testGetLang($data, $flag, $lang)
    {
        //ActiveRecord::setDb($this->connection);
        $uri = new RequestUriSub();
        $uri->putQuery($data);
        //$uri->langExists();
        //$this->assertEquals($uri->langExists(), $flag);
        //$langObj = $uri->getLang();
        //if ($flag) $this->assertEquals($lang->short, $langObj->short);
    }
    public function providerGetLang()
    {
        return array(
            array('/'         , false, null),
            array('/fr'       , false, array('fr')),
            array('/ru'       , true , array('ru')),
            array('/en///////', true , array('en')),
            array('/ru/page1/', true , array('ru', 'page1'))
        );
    }
}