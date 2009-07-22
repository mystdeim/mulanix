<?php
/**
 * Description of Test
 *
 * @author deim
 */
class Test_Mnix_ORM_Table1 extends Test_Mnix_ORM_Prototype
{
    protected $_table = 'mnix_test_table1';
    protected $_has_one = array(
		'table2' => array(
				'class'  => 'Test_Mnix_ORM_Table2',
				'fk' 	 => 'table1_id')
    );
    protected $_has_many = array(
		'tables3' => array(
                'class'  => 'Test_Mnix_ORM_Table3',
				'fk' 	 => 'table1_id'),
        'tables4' => array(
                'class'  => 'Test_Mnix_ORM_Table4',
                'jtable' => 'mnix_test_table124',
                'fk'     => 'table1_id',
                'id'    => 'table4_id'
        )
    );
}