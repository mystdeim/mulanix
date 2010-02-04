<?php
/**
 * Mulanix Framework
 */
require_once '_files/MySqlSub.php';

require_once 'PHPUnit/Extensions/Database/TestCase.php';
require_once 'PHPUnit/Framework.php';
/**
 * Mulanix Framework
 *
 * @author deim
 */
class Mnix_Db_Driver_MySqlTest extends PHPUnit_Extensions_Database_TestCase
{
    /**
     * @var array
     */
    protected $_param  = array(
            'login' => 'test',
            'pass'  => 'test',
            'base'  => 'test',
            'host'  => 'localhost'
        );
    /**
     * @var object(Mnix\Db\Driver\MySql)
     */
    protected $_MySql;
    public function __construct()
    {
         //$this->connection = new PDO('sqlite::memory:');
        $this->connection = new PDO('sqlite::memory:');
        $db = new PDO("mysql:dbname=test;host=127.0.0.1", "test", "test");
        var_dump($db);
        $this->connection->query("
            CREATE TABLE person (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name VARCHAR(255),
        surname VARCHAR(255)
    );
        ");

    }

    protected function getConnection() {

    }
    protected function getDataSet() {

    }

    protected function setUp()
    {
        $this->_MySql = new \Mnix\Db\Driver\MySqlSub($this->_param);
    }
    protected function tearDown()
    {
        unset($this->_MySql);
    }

    public function testConstruct()
    {
        $this->assertEquals($this->_param, $this->_MySql->_param);
    }
    public function testSetCon()
    {
        $this->assertNull($this->_MySql->_con);
        try {
            $this->_MySql->_setCon();
            $this->assertEquals('mysqli', get_class($this->_MySql->_con));
        } catch(Exception $e) {
             $this->markTestSkipped('Невозможно подключится к базе используя: mysql://test:test@localhost/test.');
        }
    }
    public function testQuery()
    {
        
    }
}