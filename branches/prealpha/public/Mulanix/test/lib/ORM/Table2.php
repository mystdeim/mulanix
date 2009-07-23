<?php
/**
 * Description of Test2
 *
 * @author deim
 */
class Test_Mnix_ORM_Table2 extends Test_Mnix_ORM_Prototype {
	protected $_table = 'mnix_test_table2';
	protected $_has_one = array(
		'table1' => array(
				'class'  => 'Test_Mnix_ORM_Table1',
				'id' 	 => 'table1_id'));
}