<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Mnix\Db\Xml;

require_once dirname(dirname(dirname(dirname(dirname(__DIR__))))) . '/boot/bootstrap.php';
require_once dirname(dirname(dirname(dirname(dirname(__DIR__))))) . '/lib/Mnix/Exception.php';
require_once dirname(dirname(dirname(dirname(dirname(__DIR__))))) . '/lib/Mnix/Core.php';
require_once dirname(dirname(dirname(dirname(dirname(__DIR__))))) . '/lib/Mnix/Db.php';
require_once dirname(dirname(dirname(dirname(dirname(__DIR__))))) . '/lib/Mnix/Db/Base.php';
require_once dirname(dirname(dirname(dirname(dirname(__DIR__))))) . '/lib/Mnix/Db/iDelete.php';
require_once dirname(dirname(dirname(dirname(dirname(__DIR__))))) . '/lib/Mnix/Db/Xml/Base.php';
require_once dirname(dirname(dirname(dirname(dirname(__DIR__))))) . '/lib/Mnix/Db/Xml/Delete.php';
require_once dirname(dirname(__DIR__)) . '/Driver/_files/XmlSub.php';
/**
 * Description of DeleteSub
 *
 * @author deim
 */
class DeleteSub extends \Mnix\Db\Xml\Delete
{
    //put your code here
}