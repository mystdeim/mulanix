<?php

class Template_model_Page extends lib_ORM_Prototype {
	
	protected $_table = 'page';
	
	static public function Factory($uri) {
		$db = lib_DataBase_Factory::connect();
		$sel = $db->select()
					->from(DEFAULT_PREFIX.'language')
					->where('short = ?s', $uri['lang'])
					->execute();
		if (isset($sel[0])) {
			
			$sel = $db->select()
						->from(DEFAULT_PREFIX.'page')
						->in('path', $uri['page'], 's')
						->execute();
			if (count($sel) === count($uri['page'])) {
				end($sel);
				$obj = new Template_model_Page();
				$obj->set(current($sel));
				return $obj;	
			}
		}		
	}
}