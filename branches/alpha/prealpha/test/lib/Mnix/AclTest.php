<?php
/**
 * Mulanix Framework
 *
 * @category Mulanix
 * @package Mnix_Test_Acl
 * @version $Id$
 */
/**
 * @category Mulanix
 * @package Mnix_Test_Acl
 */
class Test_Mnix_AclTest extends PHPUnit_Framework_TestCase
{
    public function __construct($name = NULL, array $data = array(), $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
    }
    public function testOne()
    {
        $acl = new Mnix_Acl();

        $table1 = new Test_Mnix_ORM_Table1(1);
        $acl->role($table1);
        
        $table2 = new Test_Mnix_ORM_Table2(1);
        $acl->resource($table2);

        $this->assertEquals($acl->isAllowed('view'), true);

        $this->assertEquals($acl->isAllowed('edit'), false);
    }
}