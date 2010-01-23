<?php
/**
 * Mulanix Framework
 *
 * @category Mulanix
 * @version $Id$
 * @author mystdeim <mysteim@gmail.com>
 */

require_once '_files/XmlSub.php';

/**
 * @category Mulanix
 */
class Mnix_Db_Driver_XmlTest extends PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $db = new \Mnix\Db\Driver\XmlSub(array('file'=>'system.xml'));
        $db->_file = __DIR__ . '/_files/testfile.xml';
        $db->load();
        $this->assertEquals('DOMDocument', get_class($db->_dom));
    }
    public function testQuery()
    {
        $db = new \Mnix\Db\Driver\XmlSub(array('file'=>'system.xml'));
        $db->_file = __DIR__ . '/_files/testfile.xml';

        $result = $db->query('/root/tag0/item[@id > 3]');
        $this->assertEquals(array(array('id'=>'4', 'attr1'=>'a4'), array('id'=>'5', 'attr1'=>'a5')), $result);

        $result = $db->query('/root/tag0/item[@attr1 = "a5"]');
        $this->assertEquals(array(array('id'=>'5', 'attr1'=>'a5')), $result);

        $result = $db->query('/root/tag0/item[@attr1 = "a5" and @id = 5]');
        $this->assertEquals(array(array('id'=>'5', 'attr1'=>'a5')), $result);

        $result = $db->query('/root/tag0/item[@attr1 = "a5" and @id < 5]');
        $this->assertEquals(array(), $result);

        //$result = $db->query('/root/tag0/');
        //var_dump($result);
    }
}