<?php
/**
 * Mulanix Framework
 */
namespace Mnix\ActiveRecord;

require_once '_files/CollectionSub.php';

require_once dirname(__DIR__) . '/_files/ActiveRecordSub.php';
require_once dirname(__DIR__) . '/_files/ActiveRecordSub/Person.php';
require_once dirname(__DIR__) . '/_files/ActiveRecordSub/Car.php';
require_once dirname(__DIR__) . '/_files/ActiveRecordSub/Comp.php';
/**
 * Mulanix Framework
 *
 * @author deim
 */
class CollectionTest extends \DatabaseTestCaseSub
{
    public function test1()
    {
        \Mnix\ActiveRecord::setDb($this->connection);
    }
    public function testSimpleLoad()
    {
        $select = new \Mnix\Db\Select($this->connection);
        $select->table('person', '*');

        $collection = new CollectionSub('Mnix\ActiveRecordSub\Person');
        $collection->select($select);

        $collection->load();

        $this->assertEquals(2, count($collection));
        
    }
}