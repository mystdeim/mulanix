<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Mnix\Db;
/**
 * Description of ISelect
 *
 * @author deim
 */
interface  iSelect
{
    public function table($table, $column = null);
}