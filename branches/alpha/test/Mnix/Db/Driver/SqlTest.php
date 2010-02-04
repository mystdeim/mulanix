<?php
/**
 * Mulanix Framework
 */
require_once '_files/SqlSub.php';
/**
 * Description of SqlTest
 *
 * @author deim
 */
class Mnix_Db_Driver_SqlTest  extends PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $sqlSub = new \Mnix\Db\Driver\SqlSub('test');
        $this->assertEquals('test', $sqlSub->_param);
    }
}