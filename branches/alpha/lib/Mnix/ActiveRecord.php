<?php
/**
 * Mulanix Framework
 */
namespace Mnix;
/**
 * Mulanix Framework
 *
 * @author deim
 */
abstract class ActiveRecord
{
    /**
     * Флаг загрузки
     *
     * @var boolean
     */
    protected $_isLoad = false;
    /**
     * Объект Mnix\Db\Select
     *
     * @var object(Mnix\Db\Select)
     */
    protected $_select = null;
    /**
     * Кортеж
     *
     * @var array
     */
    protected $_cortege = array();
    protected $_driver;
    protected static $param;
    /**
     * Конструктор
     *
     * @param int $id - индетификатор объекта
     */
    public function __construct($id = null)
    {
        if (isset($id)) {
            if (is_int($id)) {
                $this->_cortege['id'] = $id;
            } else {
                
            }
        }
    }
    public function setDriver($driver)
    {
        $this->_driver = $driver;
        return $this;
    }
    protected function _getDriver()
    {
        if (!isset($this->_driver)) $this->_driver = Db::connect()->driver();
        return $this->_driver;
    }
    protected function _select()
    {
        if (!isset($this->_select)) {
            $this->_select = new Db\Select($this->_getDriver());
            $this->_select->table($this->_table, '*');
        }
        return $this->_select;
    }
    /**
     * Загрузка данных из бд
     *
     * return object($this)
     */
    protected function _load()
    {
        /*$res = $this->_driver->query('SELECT * FROM person', \PDO::FETCH_ASSOC);
        foreach ($res as $row) {
    var_dump($row);
}*/
        //Получаем объект из базы
        //if (!isset($this->_select)) $this->_select();
        //Проверяем индетификатор
        /*if (count($this->_cortege) === 1 && isset($this->_cortege['id'])) {
            //var_dump($this->_select());
            //$this->_select();
            $this->_select()->where($this->_table.'.id = :id')
                            ->bindValue(':id', $this->_cortege['id'], \PDO::PARAM_INT);
            //var_dump($this->_select);
        }*/

        if (!isset($this->_select)) {
            $this->_select();
        }

        $res = $this->_select
                ->limit(1)
                ->execute();
        //var_dump($res);
        if (count($res)) {
            $this->_setAttribute(current($res));
            $this->_isLoad = TRUE;
        } else {
            //TODO тут нужно кидать исключение
        }
        //Следующию сточку можно будет удалить!
        unset($this->_select);
        return $this;
    }
    /**
     * Записываем кортеж
     *
     * @param array $arr
     */
    protected function _setAttribute($arr)
    {
        $this->_cortege = array_merge($this->_cortege, $arr);
        //Проверяем кортеж на "жадность", преобразуем name.field=>data в name=array('field'=>data)
        foreach ($this->_cortege as $key => $value) {
            $field = explode('.', $key);
            if (isset($field[1])) {
                $this->_cortege[$field[0]][$field[1]] = $value;
                unset($this->_cortege[$key]);
            }
        }
        $this->_isLoad = TRUE;
    }
    /**
     * Поиск, аналагочен методу where() из Mnix_Db_Select
     *
     * @param string $condition
     * @param mixed $data
     * @return object(Mnix_ORM_Prototype)
     */
    public function find($condition, $data = null) {
        $this->_select()->where($condition);
        $this->_isLoad = FALSE;
        return $this;
    }
    /**
     * Параметры
     *
     * @return array
     */
    protected function _getParam() {
        /*if (isset($this->_hasOne)) $data['has_one'] = $this->_has_one;
        if (isset($this->_has_many)) $data['has_many'] = $this->_has_many;
        $data['table'] = $this->_table;
        $sel = Mnix_Db::connect()->query('SHOW FIELDS FROM ?t', $this->_table);
        foreach ($sel as $temp) $data['fields'][] = $temp['Field'];
        return $data;*/

        $res = $this->_driver->query('PRAGMA table_info(' . $this->_table . ')', \PDO::FETCH_ASSOC);
        //foreach ($res as $temp) var_dump($temp);
        //var_dump($res);
    }
    public function join($name) {
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
     * Перегрузка обращения к членам класса
     *
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->_getAttribute(array($name));
    }
    /**
     * Перегрузка записи членов класса
     *
     * @param string $name
     * @param mixed $value
     */
    public function __set($name, $value)
    {
        $this->_setAttribute(array($name => $value));
    }
    /**
     * Запрос кортежа
     *
     * @param array $arr
     * @return array
     */
    public function get($arr) 
    {
        if (!is_array($arr)) $arr = array($arr);
        return $this->_getAttribute($arr);
    }
    /**
     * Запись кортежа
     *
     * С помощбю передачи массива в качестве аргумента
     *
     * @param array $arr
     * @return object(this)
     */
    public function set($arr) {
        $this->_setAttribute($arr);
        return $this;
    }
    /**
     * Запрос аттрибута
     *
     * @param array $atts
     * @return mixed
     */
    protected function _getAttribute($atts)
    {
        //Обходим аттрибуты
        foreach ($atts as $att) {
            //Проверяем кортеж, если в кортеже есть массив, то это "жадные" данные!
            if (isset($this->_cortege[$att])) {
                if (!is_array($this->_cortege[$att])) $data[$att] = $this->_cortege[$att];
                else {
                    /*$obj = new $this->_hasOne[$att]['class'];
                    $obj->set($this->_cortege[$att]);
                    $data[$att] = $obj;*/
                    $data[$att] = $this->_getRelation($att, $data);
                }
            } else $data[$att] = $this->_getRelation($att);
        }
        //Если запрос одного аттрибу, то его и возвращаем
        if (count($data) == 1) return current($data);
        //В противном случае массив
        else return $data;
    }
    protected function _getRelation($name, $data = NULL) {
        //1:1
        if (isset($this->_hasOne[$name])) {
            $class = $this->_hasOne[$name]['class'];

            $obj = new $class;

            if (isset($this->_hasOne[$name]['fk'])) {
                /*$obj->find('?t = ?i',
                        array(
                        $param['table'].'.'.$this->_has_one[$name]['fk'],
                        $this->_cortege['id']));*/
            } else {
                $obj->id = $this->_cortege[$this->_hasOne[$name]['id']];
                $obj->load();
                //Удалям лишнее поле
                unset($this->_cortege[$this->_hasOne[$name]['id']]);
            }
            //Добавляем в объект данные из жадного запроса, если они существуют
            if (isset($data)) $obj->set($data);
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
                        ->where('?t = ?t AND ?t = ?t AND ?t = ?i',
                        array(
                        $this->_table.'.id',
                        $this->_has_many[$name]['jtable'].'.'.$this->_has_many[$name]['fk'],
                        $param2['table'].'.id',
                        $this->_has_many[$name]['jtable'].'.'.$this->_has_many[$name]['id'],
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
    public static function takeParam($name) {
        //$data = lib_Service_Cache::get("usr/component/$name.php", __FILE__.$name, 'f', 'u');
        //if (!$data) {
        $obj = new $name;
        $data = $obj->getParam();
        //lib_Service_Cache::clear(__FILE__.$name);
        //lib_Service_Cache::put("usr/component/$name.php", $data, __FILE__.$name, 'f', 's');
        //}
        return $data;
    }
    /**
     * Loading object from Database
     *
     * @return bool
     */
    public function load()
    {
        if (isset($this->_cortege)) {
            $condition = array();
            $temp = 0;
            foreach($this->_cortege as $key => $value) {
                var_dump($value);
                $this->_select()->bindValue(':a' . $temp, $value);
                $condition[] = $key . ' = :a' . $temp++;
            }
            var_dump(implode(' AND ', $condition));
            $result = $this->_select()->where(implode(' AND ', $condition))->execute();
            if (count($result) === 1) {
                $this->_cortege = current($result);
                return true;
            } else {
                return false;
            }
        }
        /*$this->_isLoad = FALSE;
        return $this;*/
    }
}