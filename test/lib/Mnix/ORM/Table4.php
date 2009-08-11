<?php
/**
 * Description of Test2
 *
 * @author deim
 */
class Test_Mnix_ORM_Table4 extends Test_Mnix_ORM_Prototype {
	protected $_table = 'mnix_test_table4';
    protected $_has_one = array(
        'table2' => array(
                'class' => 'Test_Mnix_ORM_Table2',
                'fk'    => 'table4_id'));
    protected $_has_many = array(
        'tables3' => array(
                'class' => 'Test_Mnix_ORM_Table3',
                'fk'    => 'table4_id'),
        'tables1' => array(
                'class'  => 'Test_Mnix_ORM_Table1',
                'jtable' => 'mnix_test_table124',
                'fk'     => 'table4_id',
                'id'     => 'table1_id'
        )
    );
}