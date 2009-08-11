<?php
class Test_Mnix_ORM_CollectionTest extends PHPUnit_Framework_TestCase
{
    public function __construct($name = NULL, array $data = array(), $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
    }
    public function testHasMany1()
    {
        $table1 = new Test_Mnix_ORM_Table1();
        $table1->find('id=?i', 1)->load();
        $tables3 = $table1->getTables3();
        $tables3->load();
        $i = 0;
        foreach ($tables3 as $table3)
        {
            $i++;
            //Защита при превышнии размера
            if ($i > 100) die();
        }
        $this->assertEquals($i, 2);
        $tables3->rewind();
        $val = $tables3->current();
        $key = $tables3->key();
        $this->assertEquals($val->getText(), 'text31');
        $this->assertEquals($key, 0);
    }
    public function testHasMany2()
    {
        $table1 = new Test_Mnix_ORM_Table1();
        $tables3 = $table1->getTables3();
        $tables3->load();
        foreach ($tables3 as $table3) {
            $table2 = $table3->getTable2();
        }
        $this->assertEquals($table2->getText(), 'text24');
        //Join
        $table1 = new Test_Mnix_ORM_Table1();
        $tables3 = $table1->getTables3();
        $tables3->join('table2');
        $tables3->load();
        foreach ($tables3 as $table3) {
            $table2 = $table3->getTable2();

        }
        $this->assertEquals($table2->getText(), 'text24');
    }
    public function testHasMany2Many()
    {
        $table1 = new Test_Mnix_ORM_Table1(3);
        $tables4 = $table1->getTables4();
        $tables4->load();
        foreach ($tables4 as $table4) {
            $this->assertEquals($table4->getText(), 'text43');
        }
        //JOIN
        $table1 = new Test_Mnix_ORM_Table1(3);
        $tables4 = $table1->getTables4();
        $tables4->join('table2');
        foreach ($tables4 as $table4) {
            $table2 = $table4->getTable2();
            $this->assertEquals($table2->getText(), 'text23');
        }
    }
}