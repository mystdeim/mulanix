<?php
/**
 * Description of Tablrej
 *
 * @author deim
 */
class Test_Mnix_ORM_Table3 extends Test_Mnix_ORM_Prototype
{
	protected $_table = 'mnix_test_table3';
    protected $_has_one = array(
		'table1' => array(
				'class'  => 'Test_Mnix_ORM_Table1',
				'id'	 => 'table1_id'),
		'table2' => array(
				'class'  => 'Test_Mnix_ORM_Table2',
				'id'	 => 'table2_id'));
}