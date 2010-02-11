<?php
/**
 * Mulanix Framework
 *
 * @version $Id$
 * @author mystdeim <mysteim@gmail.com>
 */
namespace Mnix\Db;

require_once '_files/SelectSub.php';
/**
 *
 */
class SelectTest extends \PHPUnit_Framework_TestCase
{
    protected $_select;
    protected function setUp()
    {
        $this->_select = new \Mnix\Db\SelectSub('test');
    }
    /**
     * @dataProvider providerTable
     */
    public function testTable($table, $column, $expected)
    {
        $actual = $this->_select->table($table, $column)
                                ->queryBuilder();
        $this->assertEquals($expected, $actual);
    }
    public function providerTable()
    {
        return array(
            array(
                'table', '*',
                'SELECT table.* FROM table'),
            array(
                'table', array('column1'),
                'SELECT table.column1 FROM table'),
            array(
                'table',  array('column1', 'column2'),
                'SELECT table.column1, table.column2 FROM table'),
            array(
                'table',  array('column1' => 'alias'),
                'SELECT table.column1 AS "alias" FROM table'),
            array(
                'table',  array('column1' => 'a1', 'column2' => 'a2'),
                'SELECT table.column1 AS "a1", table.column2 AS "a2" FROM table')
        );
    }
    public function testSeveralTable()
    {
        $actual = $this->_select->table('table1', '*')
                                ->table('table2', array('column'))
                                ->table('table3', array('column'=>'alias'))
                                ->queryBuilder();
        $expected = 'SELECT table1.*, table2.column, table3.column AS "alias" FROM table1, table2, table3';
        $this->assertEquals($expected, $actual);
    }
    /**
     * @dataProvider providerJoinLeft
     */
    public function testJoinLeft($tables, $joins, $expected)
    {
        foreach ($tables as $table) {
            $this->_select->table($table['table'], $table['columns']);
        }
        foreach ($joins as $join) {
            $this->_select->joinLeft($join['table'], $join['on'], $join['columns']);
        }
        $actual = $this->_select->queryBuilder();
        $this->assertEquals($expected, $actual);
    }
    public function providerJoinLeft()
    {
        return array(
            array(
                array(
                    array('table' => 'table', 'columns' => '*')),
                array(
                    array('table'=>array('jtable'=>'table'), 'on'=>array('id'=>'fk'), 'columns' => '*')),
                'SELECT table.*, jtable.* FROM table LEFT JOIN jtable ON jtable.id = table.fk'),
            array(
                array(
                    array('table' => 'table', 'columns' => '*'),
                    array('table' => 'table0', 'columns' => array('id', 'name'))),
                array(
                    array('table'=>array('jtable'=>'table0'), 'on'=>array('id'=>'fk'), 'columns' => 'test')),
                'SELECT table.*, table0.id, table0.name, jtable.test FROM table, table0 LEFT JOIN jtable ON jtable.id = table0.fk'),
            array(
                array(
                    array('table' => 'table', 'columns' => '*'),
                    array('table' => 'table0', 'columns' => array('id', 'name'))),
                array(
                    array('table'=>array('jtable'=>'table'), 'on'=>array('id'=>'fk'), 'columns' => array('test'=>'jtable.test'))),
                'SELECT table.*, table0.id, table0.name, jtable.test AS "jtable.test" FROM table LEFT JOIN jtable ON jtable.id = table.fk, table0'),
            array(
                array(
                    array('table' => 'table', 'columns' => '*')),
                array(
                    array('table'=>array('jtable'=>'table'), 'on'=>array('id'=>'fk'), 'columns' => array('test'=>'jtable.test')),
                    array('table'=>array('jtable0'=>'table'), 'on'=>array('id'=>'fk'), 'columns' => array('test'=>'jtable0.test'))),
                'SELECT table.*, jtable.test AS "jtable.test", jtable0.test AS "jtable0.test" FROM table LEFT JOIN jtable ON jtable.id = table.fk LEFT JOIN jtable0 ON jtable0.id = table.fk')
        );
    }
    /**
     * @dataProvider providerOrder
     */
    public function testOrder($column, $desc, $expected)
    {
        $actual = $this->_select->table('table', '*')
                                ->order($column, $desc)
                                ->queryBuilder();
        $this->assertEquals($expected, $actual);
    }
    public function providerOrder()
    {
        return array(
            array('id', false, 'SELECT table.* FROM table ORDER BY id'),
            array('id', true, 'SELECT table.* FROM table ORDER BY id DESC')
        );
    }
}