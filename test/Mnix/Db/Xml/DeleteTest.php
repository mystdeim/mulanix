<?php
/**
 * Mulanix Framework
 *
 * @category Mulanix
 * @version $Id$
 * @author mystdeim <mysteim@gmail.com>
 */
require_once '_files/DeleteSub.php';
/*
 * Mulanix Framework
 */
class Mnix_Db_Xml_DeleteTest extends PHPUnit_Framework_TestCase
{
    /**
     * Тестовый xml-файл
     *
     * @var string
     */
    protected static $_fileXml;
    /**
     * Копия тестовый xml-файла
     *
     * @var string
     */
    protected static $_fileXmlBack;
    /**
     * Драйвер базы данных
     *
     * @var object(Mnix\Db\Driver\XmlSub)
     */
    protected $_driver;
    /**
     * Прописываем файлы
     */
    public static function setUpBeforeClass()
    {
        self::$_fileXml = __DIR__ . '/_files/delete.xml';
        self::$_fileXmlBack = __DIR__ . '/_files/deleteBack.xml';
    }
    /**
     * Иницилизируем драйвер базы данных перед каждым тестом
     */
    public function  setUp() {
        $this->_driver = new \Mnix\Db\Driver\XmlSub(array('file'=>'system.xml'));
        $this->_driver->_file = self::$_fileXml;
    }
    /**
     * Востанавливаем файл после каждого теста
     */
    protected function tearDown()
    {
        copy(self::$_fileXmlBack,  self::$_fileXml);
    }
    /**
     * Тестируем получение Бд
     */
    public function testConstruct()
    {
        $this->assertEquals('Mnix\Db\Driver\XmlSub', get_class($this->_driver));
    }
    /**
     * Удаляем 1 запись
     */
    public function testOneDelete()
    {
        $delete = new Mnix\Db\Xml\Delete($this->_driver);

        $res = $delete->table('table1')
                      ->where('?c = ?i', array('id', 1))
                      ->execute();
        $this->assertEquals($res, array(array('id'=>'1', 'attr1'=>'a1', 'attr2'=>'b1')));
        $file_content = '<?xml version="1.0" encoding="UTF-8"?>
<root>
  <table0/>
  <table1>
    <item id="2" attr1="a2" attr2="b2"/>
  </table1>
</root>
';
        $this->assertXmlStringEqualsXmlFile(self::$_fileXml, $file_content);
    }
    /**
     * Не удаляем
     */
    public function testNoDelete()
    {
        $delete = new Mnix\Db\Xml\Delete($this->_driver);

        $res = $delete->table('table1')
                      ->where('?c = ?i', array('id', 10))
                      ->execute();
        $this->assertEquals($res, array());
        $file_content = '<?xml version="1.0" encoding="UTF-8"?>
<root>
  <table0/>
  <table1>
    <item id="1" attr1="a1" attr2="b1"/>
    <item id="2" attr1="a2" attr2="b2"/>
  </table1>
</root>
';
        $this->assertXmlStringEqualsXmlFile(self::$_fileXml, $file_content);
    }
    /**
     * Удаляем все элементы
     */
    public function testAll()
    {
        $delete = new Mnix\Db\Xml\Delete($this->_driver);

        //Удаляем все элементы
        $res = $delete->table('table1')
                      ->execute();
        $this->assertEquals($res, array(
            array('id'=>'1', 'attr1'=>'a1', 'attr2'=>'b1'),
            array('id'=>'2', 'attr1'=>'a2', 'attr2'=>'b2')));
        $file_content = '<?xml version="1.0" encoding="UTF-8"?>
<root>
  <table0/>
  <table1/>
</root>
';
        $this->assertXmlStringEqualsXmlFile(self::$_fileXml, $file_content);
    }
    /**
     * Пытаемся удалить записи в пустой таблице
     */
    public function testNoItems()
    {
        $delete = new Mnix\Db\Xml\Delete($this->_driver);

        //Удаляем все элементы
        $res = $delete->table('table0')
                      ->execute();
        $this->assertEquals($res, array());
        $file_content = '<?xml version="1.0" encoding="UTF-8"?>
<root>
  <table0/>
  <table1>
    <item id="1" attr1="a1" attr2="b1"/>
    <item id="2" attr1="a2" attr2="b2"/>
  </table1>
</root>
';
        $this->assertXmlStringEqualsXmlFile(self::$_fileXml, $file_content);
    }
    /**
     * Пытаемся удалить записи в несуществующей таблице
     */
    public function testNoTable()
    {
        $delete = new Mnix\Db\Xml\Delete($this->_driver);

        //Удаляем все элементы
        $res = $delete->table('table2')
                      ->execute();
        $this->assertEquals($res, array());
        $file_content = '<?xml version="1.0" encoding="UTF-8"?>
<root>
  <table0/>
  <table1>
    <item id="1" attr1="a1" attr2="b1"/>
    <item id="2" attr1="a2" attr2="b2"/>
  </table1>
</root>
';
        $this->assertXmlStringEqualsXmlFile(self::$_fileXml, $file_content);
    }
}