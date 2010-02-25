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
abstract class ActiveRecord extends ActiveRecord\Common
{
    const COLLECTION = 'Mnix\ActiveRecord\Collection';
    /**
     * Флаг загрузки
     *
     * @var boolean
     */
    protected $_isLoad = false;

    /**
     * Кортеж
     *
     * @var array
     */
    protected $_cortege = array();
    protected static $param = NULL;
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
     * Gets tablename & fields
     *
     * @return array
     */
    protected function _getParam()
    {
        //TODO: create for mysql, this works only for sqlite
        $data['table'] = $this->_table;
        $res = $this->_getDriver()->query('PRAGMA table_info(' . $this->_table . ')', \PDO::FETCH_ASSOC);
        foreach ($res as $temp) $data['field'][] = $temp['name'];
        return $data;
    }
    public function join($name) 
    {
        if (isset($this->_hasOne) && isset($this->_hasOne[$name])) {
            $jclass = $this->_hasOne[$name]['class'];
            $jparam = $jclass::getParam();
            //$jparam = $jclass::getParam();

            foreach ($jparam['field'] as $value) {
                 $jfields[$value] = $name . '.' . $value;
            }
            $this->_select()
                    ->joinLeft(
                        array($jparam['table']             => $this->_table),
                        array($this->_hasOne[$name]['field'] => 'id'),
                    $jfields);
        }

        if (isset($this->_belongsTo) && isset($this->_belongsTo[$name])) {
            $jclass = $this->_belongsTo[$name]['class'];
            $jparam = $jclass::getParam();

            foreach ($jparam['field'] as $value) {
                 $jfields[$value] = $name . '.' . $value;
            }
            $this->_select()
                    ->joinLeft(
                        array($jparam['table']             => $this->_table),
                        array('id' => $this->_belongsTo[$name]['field']),
                    $jfields);
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
        //if (in_array($name, array('_hasOne', '_hasMany', '_belongsTo'))) throw new \Mnix\Exception;
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
            //var_dump($att);
            //Проверяем кортеж, если в кортеже есть массив, то это "жадные" данные!
            if (isset($this->_cortege[$att])) {
                if (!is_array($this->_cortege[$att])) $data[$att] = $this->_cortege[$att];
                else {
                    $obj = $this->_getRelation($att);
                    $obj->set($this->_cortege[$att]);
                    $data[$att] = $obj;
                }
            } else {
                $obj = $this->_getRelation($att);
                $obj->load();
                $data[$att] = $obj;
            }
        }
        //Если запрос одного аттрибу, то его и возвращаем
        if (count($data) == 1) return current($data);
        //В противном случае массив
        else return $data;
    }
    /**
     * Set object of relation
     *
     * @param string $name
     * @param array $data
     * @return mixed
     */
    protected function _getRelation($name) {
        //parent:child
        if (isset($this->_hasOne) && isset($this->_hasOne[$name])) {

            $class = $this->_hasOne[$name]['class'];
            $obj = new $class;
            $obj->set(array($this->_hasOne[$name]['field'] => $this->_cortege['id']));
            
            return $obj;
        }

        //child:parent
        if (isset($this->_belongsTo) && isset($this->_belongsTo[$name])) {

            $class = $this->_belongsTo[$name]['class'];
            $obj = new $class;
            $obj->set(array('id' => $this->_cortege[$this->_belongsTo[$name]['field']]));

            return $obj;
        }

        //parent:childs
        if (isset($this->_hasMany) && isset($this->_hasMany[$name])) {
            $class = $this->_hasMany[$name]['class'];
            $collection = static::COLLECTION;
            $collection = new $collection($class);
            
            $param = $class::getParam();
            
            $select = new \Mnix\Db\Select($this->_getDriver());
            $select->table($param['table'], $param['field'])
                   ->where($param['table'].'.'.$this->_hasMany[$name]['field'].' = ' . (int)$this->_cortege['id']);

            $collection->select($select);
            var_dump($collection);
            //$collection->load();
            //var_dump($collection);
            
            return $collection;
        }
        /*if (isset($this->_has_many[$name])) {
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
        }*/
    }
    /**
     * Запрос парметров класса(таблица, поля и тп)
     *
     * @param string $name
     * @return array
     */
    public static function getParam()
    {
        $name = get_called_class();
        if (!isset(static::$param[$name])) {
            $obj = new $name;
            static::$param[$name] = $obj->_getParam();
        }
        return static::$param[$name];
    }
    /**
     * Loading object from Database
     *
     * @return bool
     */
    public function load()
    {
        if (isset($this->_cortege)) {
            $temp = 0;
            foreach($this->_cortege as $key => $value) {
                $this->_select()->bindValue(':a' . $temp, $value);
                $this->_select()->where($this->_table . '.' . $key . ' = :a' . $temp++);
            }
            $result = $this->_select()->execute();
            //var_dump($result);
            if (count($result) === 1) {
                foreach (current($result) as $key => $value) {
                    //var_dump($key);
                    $flag = explode('.', $key);
                    if (count($flag) === 2) $data[$flag[0]][$flag[1]] = $value;
                    else $data[$key] = $value;
                }
                //var_dump($data);
                $this->_cortege = $data;
                unset($this->_select);
                return true;
            } else {
                return false;
            }
        }
    }
}