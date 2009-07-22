<?php
/**
 * Mulanix Framework
 *
 * @package Mnix_ORM
 * @author deim
 * @copyright 2009
 */
/**
 * @package Mnix_ORM
 */
abstract class Mnix_ORM_Prototype
{
	/**
     * Флаг загрузки
     * @var boolean
     */
	protected $_isLoad   = false;
    /**
     * @var object(Mnix_Db_Select)
     */
	protected $_select   = null;
    /**
     * Кортеж
     * @var array
     */
	protected $_cortege;

	public function __construct($id = null)
    {
		if (isset($id)) $this->_cortege['id'] = $id;
	}
	protected function _select() 
	{
        if (!isset($this->_table)) Mnix_Core::putMessage(__CLASS__, 'err', 'No table in ' . get_class($this));
		$this->_select = Mnix_Db::connect()->select()->from($this->_table);
	}	
	/*public function save()
	{
	    testr($this->_cortege);
	    foreach ($this->_cortege as $key => $val) {
	        if (!is_array($val)) {
	            $arr[$key] = '?s';
	            $data[] = $val;
	        }
	    }
	    lib_Database_Factory::connect()->insert()->into($this->_table)->set($arr, $data)->execute();
	}*/
	/**
     * Загрузка данных из бд
     */
	protected function _load()
    {
		//Получаем объект из базы
		if (!isset($this->_select)) $this->_select();
        //Проверяем индитификатор
		if (count($this->_cortege) === 1 && isset($this->_cortege['id'])) $this->_select->where('?t = ?i', array($this->_table.'.id', $this->_cortege['id']));
		$res = $this->_select
			->limit(1)
			->query();
		//Проверяем отношения
		if ($res) {
            $this->_cortege = $res[0];
            $this->_setRelations();
        } else $this->_cortege = array();
		$this->_isLoad = TRUE;
		//Следующию сточку можно будет удалить!
		unset($this->_select);
        return $this;
	}
    /**
     * Проверяем отношения и инстанируем пустые объекты
     */
	protected function _setRelations()
    {
        //Проверяем кортеж на "жадность"
        foreach ($this->_cortege as $key => $value) {
            $field = explode('.', $key);
            if (isset($field[1])) {
                $this->_cortege[$field[0]][$field[1]] = $value;
                unset($this->_cortege[$key]);
            }
        }
        //1:1
		if (isset($this->_has_one)) {
			foreach ($this->_has_one as $key => $value) {
                $class = $value['class'];
                $obj = new $class($this->_cortege[$value['fk']]);
                if (is_array($this->_cortege[$key])) $obj->set($this->_cortege[$key]);
                $this->_cortege[$key] = $obj;
                //Удаляем ненужный внешний ключ //Хз зачем тут это 
                //var_dump($this->_cortege[$value['fk']]);
                //unset($this->_cortege[$value['fk']]);
			}
		}
        //Many
		if (isset($this->_has_many)) {
			foreach ($this->_has_many as $key => $value) {
                //Создаём коллекцию
                $collection = new Mnix_ORM_Collection($value['class']);
                //Формируем критерий поиска
                //Many:many
                if (isset($value['jtable'])) {
                    //Узнаём параметры создаваемого класса, обрабатываем атрибуты
					$param2 = Mnix_ORM_Prototype::takeParam($value['class']);
                    foreach ($param2['fields'] as $k => &$v) $v = $param2['table'].'.'.$v;
                    $select = Mnix_Db::connect()
                            ->select()
                            ->from(
                                array($this->_table, $value['jtable'], $param2['table']),
                                $param2['fields'])
                            ->where(
                                '?t = ?t AND ?t = ?t AND ?t = ?i',
                                array(
                                    $this->_table.'.id',
                                    $value['jtable'].'.'.$value['key'],
                                    $param2['table'].'.id',
                                    $value['jtable'].'.'.$value['fk'],
                                    $this->_table.'.id',
                                    $this->_cortege['id']
                                )
                            );
                //1:many
                } else {
                    $param = Mnix_ORM_Prototype::takeParam($value['class']);
                    $select = Mnix_Db::connect()
                            ->select()
                            ->from($param['table'])
                            ->where('?t = ?i',
                                array(
                                    $param['table'].'.'.$value['fk'],
                                    $this->_cortege['id']
                                )
                            );
                }
                $collection->putSelect($select);
                $this->_cortege[$key] = $collection;
			}
		}
	}
	/**
     * Поиск, аналагочен методу where() из Mnix_Db_Select
     * @param string $condition
     * @param mixed $data
     * @return object(Mnix_ORM_Prototype)
     */
	public function find($condition, $data = null)
    {
		$this->_select();
		$this->_select->where($condition, $data);
		$this->_isLoad = false;
		return $this;
    }
    /**
     * Параметры
     * @return array
     */
	public function getParam()
    {
		if (isset($this->_has_one)) $data['has_one'] = $this->_has_one;
		if (isset($this->_has_many)) $data['has_many'] = $this->_has_many;
		$data['table'] = $this->_table;
		$sel = Mnix_Db::connect()->query('SHOW FIELDS FROM ?t', $this->_table);
		foreach ($sel as $temp) $data['fields'][] = $temp['Field'];
		return $data;
	}
	/*public function join($name)
    {
		if (empty($this->_select)) $this->_select();
		if (isset($this->_has_one)) {
			foreach ($this->_has_one as $key => $val) {
				if ($name == $key) {
					$param1 = lib_ORM_Prototype::takeParam(get_class($this));
					$param2 = lib_ORM_Prototype::takeParam($val['class']);
					foreach ($param1['fields'] as $key => &$value) $value = $param1['table'].'.'.$value." AS '$value'";
					foreach ($param2['fields'] as $key => &$value) $value = $param2['table'].'.'.$value." AS '$name.$value'";
					$this->_select->from($param1['table'], array_merge($param1['fields'], $param2['fields']));
					$this->_select->join(array($this->_table => $param2['table']), array($val['fk'] => 'id'));
					$this->_isLoad = FALSE;
				}
			}
		} else Mnix_Core::putMessage(__CLASS__, 'err', 'Соединение в классе "'.get_class($this).'" к атрибуту "'.$name.'" не может быть применино');
	}*/
    /**
     * Get & Set методы
     * @param string $name
     * @param array $arg
     * @return mixed
     */
	public function __call($name, $arg = NULL)
    {
        switch (substr($name, 0, 3)) {
				case 'get':
					if (isset($arg[0])) {
						if (is_array($arg[0])) return $this->_getAttribute($arg[0]);
						else return $this->_getAttribute(array($arg[0]));
					} else {
						return $this->_getAttribute(array(strtolower(substr($name, 3))));
					}
					break;
				case 'set':
                    if (isset($arg[0])) {
                        $this->_isLoad = true;
						if (!is_array($arg[0])) {
                            $this->_cortege[strtolower(substr($name, 3))] = $arg[0];
                            //return $this;
                        } else {
                            $this->_cortege = $arg[0];
                            //return $this;
                        }
                        $this->_setRelations();
					} 
					break;
		}
	}
	
	protected function _getAttribute($atts)
    {
		if (!$this->_isLoad) $this->_load();

        //$obj->get(), возвращантся весь кортеж
		if (empty($atts[0])) $atts = array_keys($this->_cortege);

        //Обходим аттрибуты
		foreach ($atts as $att) {
            
            //Проверяем кортеж
            if (!is_array($this->_cortege[$att])) $data[$att] = $this->_cortege[$att];
		}
		if (isset($data)) {
            //Если запрос одного аттрибу, то его и возвращаем
			if (count($data) == 1) return current($data);
            //В противном случае массив
			else return $data;
		} else return null;
	}
    /**
     *
     * @param string $name
     * @return array
     */
	public static function takeParam($name)
    {
		//$data = lib_Service_Cache::get("usr/component/$name.php", __FILE__.$name, 'f', 'u');
		//if (!$data) {
			$obj = new $name;
			$data = $obj->getParam();
			//lib_Service_Cache::clear(__FILE__.$name);
			//lib_Service_Cache::put("usr/component/$name.php", $data, __FILE__.$name, 'f', 's');
		//}
		return $data; 
	}
}