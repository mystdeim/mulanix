<?php
/**
 * Mulanix Framework
 */
require_once 'PHPUnit/Extensions/Database/TestCase.php';
require_once 'PHPUnit/Framework.php';
/**
 * Mulanix Framework
 *
 * @author deim
 */
class DatabaseTestCaseSub extends \PHPUnit_Extensions_Database_TestCase
{
    public function __construct()
    {
        var_dump('!!!!!');
        $this->connection = new \PDO('sqlite::memory:');
        $this->connection->query("
            CREATE TABLE person (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name VARCHAR(255),
                surname VARCHAR(255),
                age INTEGER
            );
        ");
        $this->connection->query("
            CREATE TABLE car (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name VARCHAR(255),
                person_id INTEGER
            );
        ");
        $this->connection->query("
            CREATE TABLE comp (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name VARCHAR(255),
                person_id INTEGER
            );
        ");
        $this->connection->query("
            CREATE TABLE person2house (
                person_id INTEGER,
                house_id INTEGER,
                PRIMARY KEY (person_id, house_id)
            );
        ");
        $this->connection->query("
            CREATE TABLE house (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name VARCHAR(255)
            );
        ");
    }
    protected function getConnection()
    {
        return $this->createDefaultDBConnection($this->connection, 'sqlite');
    }
    protected function getDataSet()
    {
        return $this->createFlatXMLDataSet(__DIR__ . '/testDB.xml');
    }
}