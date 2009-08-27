<?php
/**
 * Mulanix Framework
 *
 * @category Mulanix
 * @package Mnix_Core
 * @subpackage Test
 * @version $Id$
 * @author mystdeim <mysteim@gmail.com>
 */

require_once dirname(dirname(dirname(__FILE__))) . '/lib/Mnix/Core.php';

/**
 * @category Mulanix
 * @package Mnix_Core
 * @subpackage Test
 */
class Mnix_CoreSub extends Mnix_Core
{
    public function getPath($class)
    {
        return $this->_getPath($class);
    }
}