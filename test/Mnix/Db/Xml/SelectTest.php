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
    public function testFrom()
    {
        $db = $this->_getXmlDb();
        $select = new Mnix\Db\Xml\SelectSub($db);
        /*$result = $select->table('table1', '*')
                         ->execute();
        $this->assertEquals(array(array('id'=>'1', 'attr1'=>'a1'), array('id'=>'2', 'attr1'=>'a2')), $result);*/

       /* $result = $select->table('table1', 'id')
                         ->execute();
        $this->assertEquals(array(array('id'=>'1'), array('id'=>'2')), $result);*/
        //$result = $db->query('/descendant-or-self::*/attribute::attr1');
        $result = $db->query('/root/table1/item/@[id or attr1]');
        //var_dump($result);
    }
    protected function _getXmlDb()
    {
        $db = new \Mnix\Db\Driver\XmlSub(array('file'=>'system.xml'));
        $db->_file = dirname(__DIR__) . '/Driver/_files/testfile.xml';
        return $db;
    }
}