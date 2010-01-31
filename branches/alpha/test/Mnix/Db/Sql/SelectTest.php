<?php
/**
 * Mulanix Framework
 *
 * @version $Id$
 * @author mystdeim <mysteim@gmail.com>
 */

require_once '_files/SelectSub.php';
/**
 * Mulanix
 */
class Mnix_Db_SelectTest extends PHPUnit_Framework_TestCase
{
    public function testConnect()
    {
        $sel = new Mnix\Db\Sql\SelectSub('test');
        $sel->from('table')
                ->join(array('jtable' => 'table'),
                        array('id'     => 'fk'));
        $this->assertEquals(null, null);
    }
}