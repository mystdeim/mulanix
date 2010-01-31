<?php
 /**
 * Mulanix Framework
 *
 * @version $Id$
 * @author mystdeim <mysteim@gmail.com>
 */
namespace Mnix\Db;
/**
 * Mulanix Framework
 */
interface iDelete
{
    /**
     *
     * @param string $table
     */
    public function table($table);
    /**
     *
     * @param string $condition
     * @param mixed $data
     */
    public function where($condition, $data = null);
    /**
     * 
     */
    public function execute();
}