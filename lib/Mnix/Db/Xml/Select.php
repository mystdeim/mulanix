<?php
/**
 * Mulanix Framework
 *
 * @category Mulanix
 * @version $Id: MySql.php 80 2009-08-25 12:26:43Z mystdeim $
 * @author mystdeim <mysteim@gmail.com>
 */
namespace Mnix\Db\Xml;
/**
 * @category Mulanix
 */
class Select extends \Mnix\Db\Base implements \Mnix\Db\iSelect
{
    protected $_query;

    public function table($table, $column = null)
    {
        $this->_query = '/root/' . $table . '/item';
        /*if (isset($column) && $column !== '*') {
            $this->_query .= '[@'.$column.']';
        }*/
        return $this;
    }
    public function execute()
    {
        $result = $this->_driver->query($this->_query);
        return $result;
    }
}