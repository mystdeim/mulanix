<?php
/**
 * Mulanix Framework
 *
 * @category Mulanix
 * @package Mnix_ORM
 * @version 2009-07-25
 * @since 2008-10-01
 */
/**
 * Представляет объектно-ориентированную проекцию Базы Данных
 *
 * Объявление объектов осуществляется с помощью добавления защищенных свойтв:
 * 1. $_table    - название таблицы
 * 2. $_has_one  - отношение 1:1
 * 3. $_has_many - отношения 1:N или N:N
 * Примеры:
 * Table1 и Table2 в отношии 1:1 или 1:N
 * <code>
 * class Table1 extends Mnix_ORM_Prototype
 * {
 *     protected $_table = 'table1';
 *     protected $_has_one = array(
 *		  'table2' => array(
 *		    	'class'  => 'Table2',
 *				'fk' 	 => 'table1_id')
 *     );
 * }
 * class Table2 extends Mnix_ORM_Prototype {
 *    protected $_table = 'table2';
 * }
 * //Тестируем
 * $table1 = new Table1();
 * $table2 = $table1->getTable2();
 * </code>
 * Table1 и Table4 в отношии N:N
 * <code>
 * class Table1 extends Mnix_ORM_Prototype
 * {
 *     protected $_table = 'table1';
 *     protected $_has_many = array(
 *		  'tables4' => array(
 *              'class'  => 'Table2',
 *              'jtable' => 'jtable',
 *              'id'     => 'table4_id',
 *              'fk' 	 => 'table1_id')
 *     );
 * }
 * class Table4 extends Mnix_ORM_Prototype {
 *    protected $_table = 'table4';
 * }
 * //Тестируем
 * $table1 = new Table1();
 * $tables4 = $table1->getTables4();
 * </code>
 *
 * @category Mulanix
 * @package Mnix_ORM
 */
abstract class Mnix_ORM_Prototype
{
	/**
     * Флаг загрузки
     *
     * @var boolean
     */
	protected $_isLoad = false;
    /**
     * Объект Mnix_Db_Select
     *
     * @var object(Mnix_Db_Select)
     */
	protected $_select = null;
    /**
     * Кортеж
     *
     * @var array
     */
	protected $_cortege;
    /**
     * Конструктор
     *
     * @param int $id
     */
	public function __construct($id = null)
    {
		if (isset($id)) $this->_cortege['id'] = $id;
	}
	protected function _select() 
	{
        if (!isset($this->_table)) Mnix_Core::putMessage(__CLASS__, 'err', 'No table in ' . get_class($this));
		$this->_select = Mnix_Db::connect()->select()->from($this->_table, '*');
	}
	/**
     * Загрузка данных из бд
     *
     * return object($this)
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
            //Проверяем кортеж на "жадность", преобразуем name.field=>data в name=array('field'=>data)
            foreach ($this->_cortege as $key => $value) {
                $field = explode('.', $key);
                if (isset($field[1])) {
                    $this->_cortege[$field[0]][$field[1]] = $value;
                    unset($this->_cortege[$key]);
                }
            }
        } else {
            //TODO тут нужно кидать исключение
        }
		$this->_isLoad = TRUE;
		//Следующию сточку можно будет удалить!
		unset($this->_select);
        return $this;
	}
	/**
     * Поиск, аналагочен методу where() из Mnix_Db_Select
     *
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
     *
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
    public function join($name)
    {
        if (isset($this->_has_one[$name])) {
            $jclass = $this->_has_one[$name]['class'];
            $jparam = Mnix_ORM_Prototype::takeParam($jclass);
            //Пересчитываем столбцы для соединямой таблицы
            foreach ($jparam['fields'] as $key => $value) {
                $jparam['fields'][$value] = $name.'.'.$value;
                unset($jparam['fields'][$key]);
            }
            $this->_select = Mnix_Db::connect()->select()
                ->from($this->_table, '*')
                ->joinLeft(
                    array($jparam['table']             => $this->_table),
                    array($this->_has_one[$name]['fk'] => 'id'),
                    $jparam['fields']);
        }
        return $this;
    }
    /**
     * Get & Set методы
     *
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
                            return $this;
                        } else {
                            $this->_cortege = $arg[0];
                            return $this;
                        }
					} 
					break;
		}
        //Есле не Get, Set кидать исключение
	}
	/**
     * Запрос аттрибута
     *
     * @param array $atts
     * @return mixed
     */
    protected function _getAttribute($atts) {
        if (!$this->_isLoad) $this->_load();
        //Обходим аттрибуты
        foreach ($atts as $att) {
        //Проверяем кортеж, если в кортеже есть массив, то это "жадные" данные!
            if (isset($this->_cortege[$att]) && !is_array($this->_cortege[$att])) $data[$att] = $this->_cortege[$att];
            else {
            //Проверяем отношения
                $data[$att] = $this->_getRelation($att);
            }
        }
        //Если запрос одного аттрибу, то его и возвращаем
        if (count($data) == 1) return current($data);
        //В противном случае массив
        else return $data;
    }
    protected function _getRelation($name)
    {
        //1:1
		if (isset($this->_has_one[$name])) {
            $class = $this->_has_one[$name]['class'];
            $param = Mnix_ORM_Prototype::takeParam($class);
            $obj = new $class;
            $obj->find('?t = ?i',
                array(
                $param['table'].'.'.$this->_has_one[$name]['fk'],
                $this->_cortege['id']
                )
            );
            //Добавляем в объект данные из жадного запроса, если они существуют
            if (isset($this->_cortege[$name])) $obj->set($this->_cortege[$name]);
            return $obj;
		}
        //many
		if (isset($this->_has_many[$name])) {
                //Создаём коллекцию
                $collection = new Mnix_ORM_Collection($this->_has_many[$name]['class']);
                //Many:many
                if (isset($this->_has_many[$name]['jtable'])) {
                    //Узнаём параметры создаваемого класса, обрабатываем атрибуты
					$param2 = Mnix_ORM_Prototype::takeParam($this->_has_many[$name]['class']);
                    //foreach ($param2['fields'] as $k => &$v) $v = $param2['table'].'.'.$v;
                    $select = Mnix_Db::connect()
                            ->select()
                            ->from($this->_table)
                            ->from($this->_has_many[$name]['jtable'])
                            ->from($param2['table'], $param2['fields'])
                            ->where(
                                '?t = ?t AND ?t = ?t AND ?t = ?i',
                                array(
                                    $this->_table.'.id',
                                    $this->_has_many[$name]['jtable'].'.'.$this->_has_many[$name]['id'],
                                    $param2['table'].'.id',
                                    $this->_has_many[$name]['jtable'].'.'.$this->_has_many[$name]['fk'],
                                    $this->_table.'.id',
                                    $this->_cortege['id']
                                )
                            );
                //1:many
                } else {
                    $param = Mnix_ORM_Prototype::takeParam($this->_has_many[$name]['class']);
                    $select = Mnix_Db::connect()
                            ->select()
                            ->from($param['table'], '*')
                            ->where('?t = ?i',
                                array(
                                    $param['table'].'.'.$this->_has_many[$name]['fk'],
                                    $this->_cortege['id']
                                )
                            );
                }
                $collection->putSelect($select);
                return $collection;
		}
    }
    /**
     * Запрос парметров класса(таблица, поля и тп)
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