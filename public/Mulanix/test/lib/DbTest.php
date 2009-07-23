<?php
class Test_Mnix_DbTest extends PHPUnit_Framework_TestCase
{
    public function __construct($name = NULL, array $data = array(), $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
    }
    /**
     * Проверяем параметры
     */
    public function testParam()
    {
        $db = Test_Mnix_Db::connect('DB0');
        $param = $db->getParam();
        $this->assertEquals($param['type'], 'MySql');
        $this->assertEquals($param['host'], 'localhost');
        $this->assertEquals($param['login'], 'testuser');
        $this->assertEquals($param['pass'], '12345');
        $this->assertEquals($param['base'], 'cmf1');
    }
    /**
     * Проверяем не множится ли коннект
     */
    public function testConnect()
    {
        $db = Test_Mnix_Db::connect('DB0');
        $db = Test_Mnix_Db::connect();
        $db = Test_Mnix_Db::connect();
        $db = Test_Mnix_Db::connect();
        $db = Test_Mnix_Db::connect();

        $this->assertEquals(count(Test_Mnix_Db::getInstance()), 1);
    }
    /**
     * Проверяем экранирование
     * @dataProvider providerShielding
     */
    public function testShielding($value, $mode, $result)
    {
        $db = Test_Mnix_Db::connect();
        $this->assertEquals($db->shielding($value, $mode), $result);
    }
    public function providerShielding()
    {
        return array(
            array('123', 'i', 123),
            array('123.4', 'i', 123),
            array('abcdef', 's', "'abcdef'"),
            array('1223.3', 'f', 1223.3),
            array('dghf123', 'n', 'dghf123'),
            array('123d', 'i', 123),
            array('d123', 'i', 0),
            array('table', 't', '`table`'),
            array('table.field', 't', '`table`.`field`')
        );
    }
    /**
     * Проверяем плэйсхолдер
     * @dataProvider providerPlaceHolder
     */
    public function testPlaceHolder($condition, $data, $result)
    {
        $db = Test_Mnix_Db::connect();
        $this->assertEquals($db->placeHolder($condition, $data), $result);
    }
    public function providerPlaceHolder()
    {
        return array(
            array('?s ?i ?f ?t', array('abcd', '123', '123.45', 'table'),
                "'abcd' 123 123.45 `table`"),
            array('SELECT * FROM ?t WHERE (?t = ?i AND ?t > ?f) OR ?t = ?s', array('table', 'field', 2, 'field', 90.5, 'field2', 'abcd'),
                "SELECT * FROM `table` WHERE (`field` = 2 AND `field` > 90.5) OR `field2` = 'abcd'"),
        );
    }
    /**
     * Проверяем извлечение
     * @dataProvider providerQuery
     */
    public function testQuery($sql, $data, $result)
    {
        $db = Mnix_Db::connect();
        $res = $db->query($sql, $data);
        $this->assertEquals($res[0], $result);
    }
    public function providerQuery()
    {
        return array(
            array('SELECT * FROM mnix_test_table1 WHERE id = ?i', 1,
                array('id'=>1, 'text'=>'text11')),
            array('SELECT * FROM mnix_test_table1 WHERE id = ?i AND text = ?s', array(2, 'text12'),
                array('id'=>2, 'text'=>'text12'))
        );
    }
}