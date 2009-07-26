<?php
/**
 * Mulanix Framework
 *
 * @category Mulanix
 * @package Mnix_Db
 * @subpackage Driver
 * @version 2009-05-07
 */
/**
 * Драйвер доступа к MySql
 * 
 * @category Mulanix
 * @package Mnix_Db
 * @subpackage Driver
 */
class Mnix_Db_Driver_MySql
{
    /**
     * Указатель coединения c cерверoм
     *
     * @var object(mysqli)
     */
	protected $_con;
    /**
     * Коструктор
     *
     * В параметре передаёться объект Mnix_Db
     *
     * @param oblect(Mnix_Db) $obj
     */
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
    /**
     * Выполнение запросса к БД
     *
     * @param string $sql
     * @return array
     */
	public function execute($sql)
    {
		$result = mysqli_query($this->_con, $sql);
		if ($result && $result !== TRUE) {
			for($data=array(); $row = mysqli_fetch_assoc($result); $data[] = $row);
			mysqli_free_result($result);
			return $data;
		} else return $result;
	}
    /**
     * Получение ошибок
     *
     * @return string
     */
	public function getError()
    {
		return mysqli_error($this->_con);
	}
}