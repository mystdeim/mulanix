<?php
/**
 * Mulanix Framework
 */
namespace Mnix\ActiveRecord;

require_once '_files/CollectionSub.php';
/**
 * Mulanix Framework
 *
 * @author deim
 */
class CollectionTest extends \DatabaseTestCaseSub
{
    public function test1()
    {
        CollectionSub::setDriverToSub($this->connection);
    }

}