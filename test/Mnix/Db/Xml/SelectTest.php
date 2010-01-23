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
    public function testConstruct()
    {
        $db = $this->_getXmlDb();
        $this->assertEquals('Mnix\Db\Driver\XmlSub', get_class($db));
    }
    /**
     * Проверяем From
     * @dataProvider providerFrom
     */
    public function testFrom($table, $column, $result)
    {
        $db = $this->_getXmlDb();
        $select = new Mnix\Db\Xml\SelectSub($db);
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
            array(array('table1'=>'t1'), 'id', array(
                                    array('id'=>'1'),
                                    array('id'=>'2')
                                )
            ),
            array('table1', array('id'=>'i', 'attr1'=>'a'), array(
                                    array('i'=>'1', 'a'=>'a1'),
                                    array('i'=>'2', 'a'=>'a2')
                                )
            ),
            array(array('table1'=>'t'), array('id'=>'i', 'attr1'=>'a'), array(
                                    array('i'=>'1', 'a'=>'a1'),
                                    array('i'=>'2', 'a'=>'a2')
                                )
            )
        );
    }
    protected function _getXmlDb()
    {
        $db = new \Mnix\Db\Driver\XmlSub(array('file'=>'system.xml'));
        $db->_file = dirname(__DIR__) . '/Driver/_files/testfile.xml';
        return $db;
    }
}