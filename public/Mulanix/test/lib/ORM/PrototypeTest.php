<?php
/**
 * Description of PrototypeTest
 *
 * @author deim
 */
class Test_Mnix_ORM_PrototypeTest extends PHPUnit_Framework_TestCase
{
    public function __construct($name = NULL, array $data = array(), $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
    }
    public function testGet()
    {
        //1
        $table1 = new Test_Mnix_ORM_Table1();
        $table1->load();
        //2
        $table2 = new Test_Mnix_ORM_Table2();
        $id = $table1->getId();
        $this->assertEquals($id, 1);
        //3
        $text = $table1->get('text');
        $this->assertEquals($text, 'text11');
        //4
        $arr = $table1->get(array('id', 'text'));
        $this->assertEquals($arr, array('id' => 1, 'text' => 'text11'));
        //5
        $obj = $table1->getTable2();
        $this->assertEquals(get_class($obj), 'Test_Mnix_ORM_Table2');
    }
    /**
     *
     */
     public function testHasOne()
     {
         $table1 = new Test_Mnix_ORM_Table1();
         $table1->find('id=?i', 1);
         $table2 = $table1->getTable2();
         $table2->load();
         $this->assertEquals($table2->getText(), 'text21');
     }
     /**
     *
     */
     public function testHasMany1()
     {
         $table1 = new Test_Mnix_ORM_Table1();
         $table1->find('id=?i', 1)->load();
         $tables3 = $table1->getTables3();
         $tables3->load();
         $i = 0;
         foreach ($tables3 as $table3) {
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
         $tables3->join('table2');
         $tables3->load();
         //var_dump($tables3);
         foreach ($tables3 as $table3) {
             //var_dump($table3);
             $table2 = $table3->getTable2();
             //var_dump($table2);
             //var_dump($table2->getText());
             $this->assertEquals($table2->getText(), 'text22');
         }
     }
     public function testHasMany2Many()
     {
         
     }
}