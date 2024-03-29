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
        $table1 = new Test_Mnix_ORM_Table1(1);
        $this->assertEquals($table1->id, 1);
        //1a
        $this->assertEquals($table1->idd, null);
    }
    public function testSet()
    {
        //1
        $table1 = new Test_Mnix_ORM_Table1();
        $table1->id = 3;
        $this->assertEquals($table1->id, 3);
    }
    public function testCallGet()
    {
        //1
        $table1 = new Test_Mnix_ORM_Table1(1);
        $table1->load();
        //2
        $table2 = new Test_Mnix_ORM_Table2(1);
        $id = $table2->id;
        $this->assertEquals($id, 1);
        //3
        $text = $table1->get(array('text'));
        $this->assertEquals($text, 'text11');
        //4
        $arr = $table1->get(array('id', 'text'));
        $this->assertEquals($arr, array('id' => 1, 'text' => 'text11'));
        //5
        $obj = $table1->table2;
        $this->assertEquals(get_class($obj), 'Test_Mnix_ORM_Table2');
        $this->assertEquals($obj->text, 'text21');
    }
    public function testCallSet()
    {
        //1
        $table1 = new Test_Mnix_ORM_Table1();
        $table1->set(array('id' => 3));
        $this->assertEquals($table1->id, 3);
        //2
        $table1 = new Test_Mnix_ORM_Table1();
        $table1->set(array('id' => 3, 'text' => 'text13'));
        $this->assertEquals($table1->get(array('id', 'text')), array('id' => 3, 'text' => 'text13'));
        //3
        $table1 = new Test_Mnix_ORM_Table1();
        $table1->id = 3;
        $this->assertEquals($table1->id, 3);
    }
    public function testHasOne()
    {
         //1
         $table1 = new Test_Mnix_ORM_Table1();
         $table1->find('id=?i', 1);
         $table2 = $table1->table2;
         $table2->load();
         $this->assertEquals($table2->text, 'text21');
         //2
         $table1 = new Test_Mnix_ORM_Table1();
         $table1->find('id=?i', 1);
         $table2 = $table1->table2;
         $this->assertEquals($table2->text, 'text21');
         //3 JOIN
         $table1 = new Test_Mnix_ORM_Table1(1);
         $table1->join('table2');
         $table2 = $table1->table2;
         $this->assertEquals($table2->text, 'text21');
         //4
         $table3 = new Test_Mnix_ORM_Table3();
         $table3->find('id=?i', 1);
         $table1 = $table3->table1;
         $this->assertEquals($table1->text, 'text11');
     }
}