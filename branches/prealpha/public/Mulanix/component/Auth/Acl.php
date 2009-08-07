<?php

class Auth_Acl extends lib_Acl {
	
	protected $_role;
	protected $_db;
	protected $_select;
	protected $_table = 'right';
	protected $_table_a = 'alias';
	
	public function __construct() {
		$this->_db = lib_Database_Factory::connect();
		$this->_select = lib_Database_Factory::connect()->select();
		if (constant('DEFAULT_PREFIX')) {
			$this->_table = DEFAULT_PREFIX.$this->_table;
			$this->_table_a = DEFAULT_PREFIX.$this->_table_a;
		}
	}
	
	public function Role($obj) {
		$this->_role = array();
		$this->_helpGetClass($obj);
		testr($this->_role);
		return $this;
	}
	
	public function unionRole() {
		$args = func_get_args();
		foreach ($args as $value) {
			if (is_array($value)) {
				foreach ($value as $val) $this->_helpGetClass($val);
			} else $this->_helpGetClass($value);
		}
		testr($this->_role);
		return $this;
	}
	
	public function intersectRole() {
		$args = func_get_args();
		foreach ($args as $value) {
			if (is_array($value)) {
				foreach ($value as $val) $this->_helpGetClass($val, FALSE);
			} else $this->_helpGetClass($value, FALSE);
		}
		testr($this->_role);
		return $this;
	}
	
	public function isAllowed($obj) {
		
		/*foreach ($this->_role as $value) {
			$this->_select
				->from($this->_table)
					->join(array($this->_table => $this->_table_a), array($this->_table.'.action' => $this->_table_a.'.id'))
				->execute();
		}*/
		
		$sel = $this->_select
				->from($this->_table)
					->join(array($this->_table => array($this->_table_a => 'a0')), array('action' => 'id'))
				->execute();
		testr($sel);
		
		return FALSE;
	}
	
	private function _helpGetClass($obj, $mode = TRUE) {
		$table = explode('_', get_class($obj));
		end($table);
		$this->_role[] = array(strtolower(current($table)) => array('id' => $obj->getId(), 'mode' => $mode));
	}
}

/*SELECT r.id 'id', a0.text 'action', a1.text 'role', a2.text 'resource', a10.text 'field', a11.text 'field', c.value 'value'
FROM `sys_right` r
LEFT JOIN `sys_alias` a0 ON r.action = a0.id
LEFT JOIN `sys_alias` a1 ON r.role = a1.id
LEFT JOIN `sys_alias` a2 ON r.resource = a2.id
LEFT JOIN `sys_criterion` c
LEFT JOIN `sys_alias` a10 ON c.field = a10.id
LEFT JOIN `sys_alias` a11 ON c.predicate = a11.id ON r.id = c.id
WHERE 1 =1*/