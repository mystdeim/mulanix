<?php
/**
 * Mulanix Framework
 *
 * @category Mulanix
 * @version $Id$
 * @author mystdeim <mysteim@gmail.com>
 */

require_once '_files/SelectSub.php';

/**
 * @category Mulanix
 */
class Mnix_Db_Xml_SelectTest extends PHPUnit_Framework_TestCase
{
    /**
     * Тестовый xml-файл
     *
     * @var string
     */
    protected static $_fileXml;
    /**
     * Драйвер базы данных
     *
     * @var object(Mnix\Db\Driver\XmlSub)
     */
    protected $_driver;
    /**
     * Прописываем файлы
     */
    public static function setUpBeforeClass()
    {
        self::$_fileXml = __DIR__ . '/_files/select.xml';
    }
    /**
     * Иницилизируем драйвер базы данных перед каждым тестом
     */
    public function  setUp() {
        $this->_driver = new \Mnix\Db\Driver\XmlSub(array('file'=>'system.xml'));
        $this->_driver->_file = self::$_fileXml;
    }
    public function testConstruct()
    {
        $this->assertEquals('Mnix\Db\Driver\XmlSub', get_class($this->_driver));
    }
    /**
     * Проверяем table
     * 
     * @dataProvider providerFrom
     */
    public function testFrom($table, $column, $result)
    {
        $select = new Mnix\Db\Xml\SelectSub($this->_driver);
        $data = $select->table($table, $column)
                       ->execute();
        $this->assertEquals($result, $data);
    }
    public function providerFrom()
    {
        return array(
            array('table1', '*', array(
                                    array('id'=>'1', 'attr1'=>'a1', 'attr2'=>'b1'),
                                    array('id'=>'2', 'attr1'=>'a2', 'attr2'=>'b2')
                                )
            ),
            array('table1', 'id', array(
                                    array('id'=>'1'),
                                    array('id'=>'2')
                                )
            ),
            array('table1', array('id'=>'i', 'attr1'=>'a'), array(
                                    array('i'=>'1', 'a'=>'a1'),
                                    array('i'=>'2', 'a'=>'a2')
                                )
            )
        );
    }
    /**
     *
     * @dataProvider providerWhere
     */
    /*public function testWhere($condition, $data, $result)
    {
        $select = new Mnix\Db\Xml\SelectSub($this->_driver);
        $data = $select->table('table1', '*')
                       ->where($condition, $data)
                       ->execute();
        $this->assertEquals($result, $data);
    }
    public function providerWhere()
    {
        return array(
            array('?c = ?i', array('id', '1'), array(
                                    array('id'=>'1', 'attr1'=>'a1', 'attr2'=>'b1')
                                )
            )
        );
    }*/
    /**
     * 
     */
    /*public function testJoin()
    {
        $select = new Mnix\Db\Xml\SelectSub($this->_driver);
        $select->table('table1', 'id')
                       ->join(array('table2'    => 'table1'),
                              array('table1_id' => 'id'),
                              array('id' => 'a.id', 'attr22' => 'a.attr22'));

        $result = $select->execute();
        $expectedResult = array(
            0 => array(
                'id'       => 1,
                'a.id'     => 1,
                'a.attr22' => 'c1'
            )
        );


    }*/
}