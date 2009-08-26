<?php
/**
 * Mulanix Framework
 *
 * @category Mulanix
 * @package Mnix_Db
 * @subpackage Test
 * @version $Id$
 * @author mystdeim <mysteim@gmail.com>
 */

require_once dirname(dirname(__FILE__)) . '/Helper.php';

require_once dirname(dirname(dirname(__FILE__))) . '/lib/Mnix/Core.php';

require_once dirname(dirname(dirname(__FILE__))) . '/lib/Mnix/Db.php';

/**
 * @category Mulanix
 * @package Mnix_Db
 * @subpackage Test
 */
class Mnix_DbSub extends Mnix_Db
{
    public function getDb()
    {
        return $this->_db;
    }
}