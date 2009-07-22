<?php
/**
 * Mulanix Framework
 *
 * @category Mulanix
 * @package Mnix_Db
 * @subpackage Mnix_Db_Driver
 * @version 2009-05-07
 */
/**
 * @category Mulanix
 * @package Mnix_Db
 * @subpackage Mnix_Db_Driver
 */
class Mnix_Db_Driver_MySql
{
	protected $_con;
	public function __construct($obj)
    {
		$param = $obj->getParam();
		if (!is_resource($obj->getCon())) {
			$this->_con = mysqli_connect($param['host'], $param['login'], $param['pass'], $param['base']);
			
			//Кодировка поумолчанию utf8
			mysqli_query($this->_con, "SET NAMES 'utf8'");
			
			//Время по гринвичу
			mysqli_query($this->_con, "SET time_zone = '+00:00'");
			
			$obj->putCon($this->_con);
		} else $this->_con = $obj->getCon();
	}
	public function execute($sql)
    {
		$result = mysqli_query($this->_con, $sql);
		if ($result && $result !== TRUE) {
			for($data=array(); $row = mysqli_fetch_assoc($result); $data[] = $row);
			mysqli_free_result($result);
			return $data;
		} else return $result;
	}
	public function getError()
    {
		return mysqli_error($this->_con);
	}
}