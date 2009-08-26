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

require_once dirname(__FILE__) . '/Helper.php';

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