<?php
/**
 * Mulanix Framework
 *
 * @category Mulanix
 * @package Mnix_Db
 * @subpackage Test
 * @version 2009-07-25
 * @since 2008-10-01
 */
/**
 * Представляет объектно-ориентированный интерфейс создания SELECT-запросов
 *
 * @category Mulanix
 * @package Mnix_Db
 * @subpackage Test
 */
class Test_Mnix_Db_SelectTest extends PHPUnit_Framework_TestCase
{
    public function __construct($name = NULL, array $data = array(), $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
    }
    /**
     * Проверяем From
     * 
     * @dataProvider providerFrom
     */
    public function testFrom($table, $field, $result)
    {
        $db = Mnix_Db::connect();
        $select = new Test_Mnix_Db_Select($db);
        $select->from($table, $field);
        $this->assertEquals($select->build(), $result);
    }
    public function providerFrom()
    {
        return array(
            array('table', null,
                array('sql' => 'SELECT  FROM ?t',
                        'data' => array('table'))),
            array('table', '*',
                array('sql' => 'SELECT ?t.* FROM ?t',
                        'data' => array('table', 'table'))),
            array('table', 'id',
                array('sql' => 'SELECT ?t FROM ?t',
                        'data' => array('table.id', 'table'))),
            array('table', array('id', 'text'),
                array('sql' => 'SELECT ?t, ?t FROM ?t',
                        'data' => array('table.id', 'table.text', 'table'))),
            array('table', array('id' => 'i', 'text' => 'tx'),
                array('sql' => 'SELECT ?t AS ?s, ?t AS ?s FROM ?t',
                        'data' => array('table.id', 'i', 'table.text', 'tx', 'table')))
        );
    }
    /**
     * Сложный From
     */
    public function testFromDf()
    {
        $db = Mnix_Db::connect();
        //--------------------------------------------------------------------------------------------------------------
        $sel1 = new Test_Mnix_Db_Select($db);
        $sel1->from('table1')
               ->from('table2', array('id', 'text'));
        $res1 = array(
                'sql'  => 'SELECT ?t, ?t FROM ?t, ?t',
                'data' => array(
                                'table2.id',
                                'table2.text',
                                'table1',
                                'table2'
                )
        );
        $this->assertEquals($sel1->build(), $res1);
        //--------------------------------------------------------------------------------------------------------------
        $sel2 = new Test_Mnix_Db_Select($db);
        $sel2->from('table1')
               ->from('table2', array('id'))
               ->from('table1', array('id', 'text'));
        $res2 = array(
                'sql'  => 'SELECT ?t, ?t, ?t FROM ?t, ?t',
                'data' => array(
                                'table1.id',
                                'table1.text',
                                'table2.id',
                                'table1',
                                'table2'
                )
        );
        $this->assertEquals($sel2->build(), $res2);
        //--------------------------------------------------------------------------------------------------------------
        $sel5 = new Test_Mnix_Db_Select($db);
        $sel5->from('table1')
               ->where('?t = ?i', array('table1.id', 1))
               ->from('table1', array('id' => 'id', 'text' => 'text'));
        $res5 = array(
                'sql'  => 'SELECT ?t AS ?s, ?t AS ?s FROM ?t WHERE ?t = ?i',
                'data' => array(
                                'table1.id',
                                'id',
                                'table1.text',
                                'text',
                                'table1',
                                'table1.id',
                                1
                )
        );
        $this->assertEquals($sel5->build(), $res5);
    }
    /**
     * Проверяем Where
     * @dataProvider providerWhere
     */
    public function testWhere($table, $condition, $data, $result)
    {
        $db = Mnix_Db::connect();
        $select = new Test_Mnix_Db_Select($db);
        $select->from($table, '*')
            ->where($condition, $data);
        $build = $select->build();
        $this->assertEquals($build, $result);
    }
    public function providerWhere()
    {
        return array(
        array('table', 'field = ?i', 1,
        array('sql' => 'SELECT ?t.* FROM ?t WHERE field = ?i',
        'data' => array('table','table', 1))),
        array('table', '?t = ?i', array('field', 5),
        array('sql' => 'SELECT ?t.* FROM ?t WHERE ?t = ?i',
        'data' => array('table','table', 'field', 5)))
        );
    }
    public function testWhereDf()
    {
        $db = Mnix_Db::connect();
        //1
        $select = new Test_Mnix_Db_Select($db);
        $select->from('mnix_test_table1', '*')
            ->where('?t = 1', 'id')
            ->where('?t = ?s', array('text', 'text11'));
        $result = array(
            'sql' => 'SELECT ?t.* FROM ?t WHERE ?t = 1 ?n ?t = ?s',
            'data' => array('mnix_test_table1', 'mnix_test_table1', 'id', 'AND', 'text', 'text11'));
        $this->assertEquals($select->build(), $result);
        //2
        $select = new Test_Mnix_Db_Select($db);
        $select->from('mnix_test_table1', '*')
            ->where('?t = 1', 'id')
            ->where('?t = 5', 'table2_id')
            ->where('?t = ?s', array('text', 'text11'));
        $result = array(
            'sql' => 'SELECT ?t.* FROM ?t WHERE ?t = 1 ?n ?t = 5 ?n ?t = ?s',
            'data' => array('mnix_test_table1', 'mnix_test_table1', 'id', 'AND','table2_id','AND', 'text', 'text11'));
        $this->assertEquals($select->build(), $result);
    }
    /**
     * Проверяем JoinLeft
     * @dataProvider providerJoinLeft
     */
    public function testJoinLeft($table, $column, $jtable, $on, $jcolumn, $result)
    {
        $db = Mnix_Db::connect();
        $select = new Test_Mnix_Db_Select($db);
        $select->from($table, $column)
                ->joinLeft($jtable, $on, $jcolumn);
        $build = $select->build();
        $this->assertEquals($build, $result);
    }
    public function providerJoinLeft()
    {
        return array(
            array('table', '*', array('jtable' => 'table'), array('id' => 'fk'), null,
                array('sql' => 'SELECT ?t.* FROM ?t LEFT JOIN ?t ON ?t = ?t',
                    'data' => array('table','table', 'jtable', 'table.fk', 'jtable.id'))),
            array('table', '*', array('jtable' => 'table'), array('id' => 'fk'), array('id', 'field1'),
                array('sql' => 'SELECT ?t.*, ?t, ?t FROM ?t LEFT JOIN ?t ON ?t = ?t',
                    'data' => array('table', 'jtable.id', 'jtable.field1', 'table', 'jtable', 'table.fk', 'jtable.id'))),
            array('table', 
                array('id' => 't.id', 'field' => 't.f'), array('jtable' => 'table'), array('id' => 'fk'),
                array('id' => 'j.id', 'field' => 'j.f'),
                array('sql' => 'SELECT ?t AS ?s, ?t AS ?s, ?t AS ?s, ?t AS ?s FROM ?t LEFT JOIN ?t ON ?t = ?t',
                    'data' => 
                    array('table.id', 't.id', 'table.field', 't.f', 'jtable.id', 'j.id', 'jtable.field', 'j.f',
                        'table', 'jtable', 'table.fk', 'jtable.id')))
        );
    }
    /**
     * Проверка Limit
     * @dataProvider providerLimit
     */
    public function testLimit($first, $last, $result)
    {
        $db = Mnix_Db::connect();
        $select = new Test_Mnix_Db_Select($db);
        $select->from('table', '*')
                ->limit($first, $last);
        $this->assertEquals($select->build(), $result);
    }
    public function providerLimit()
    {
        return array(
            array(5, null,
                array('sql' => 'SELECT ?t.* FROM ?t LIMIT 5',
                        'data' => array('table', 'table'))),
            array('11fg', '15f',
                array('sql' => 'SELECT ?t.* FROM ?t LIMIT 11, 15',
                        'data' => array('table', 'table')))
        );
    }
    /**
     * Проверяем Query
     * @dataProvider providerQuery
     */
    public function testQuery($table, $columns, $condition, $data, $result)
    {
        $db = Mnix_Db::connect();
        $select = new Test_Mnix_Db_Select($db);
        $select->from($table, $columns)
                ->where($condition, $data);
        $build = $select->query();
        $this->assertEquals($build[0], $result);
    }
    public function providerQuery()
    {
        return array(
            array('mnix_test_table1', '*', '?t = ?i', array('id', 1),
                array('id'=>1, 'text'=>'text11')),
            array('mnix_test_table1', '*', '?t = ?i', array('mnix_test_table1.id', 2),
                array('id'=>2, 'text'=>'text12'))
        );
    }
}