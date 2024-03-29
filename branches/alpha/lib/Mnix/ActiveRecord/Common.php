<?php
/**
 * Mulanix Framework
 */
namespace Mnix\ActiveRecord;
/**
 * Mulanix Framework
 *
 * @author deim
 */
abstract class Common
{
    protected static $_driver = NULL;
    /**
     * Объект Mnix\Db\Select
     *
     * @var object(Mnix\Db\Select)
     */
    protected $_select = NULL;
    public static function setDb($db)
    {
        self::$_driver = $db;
    }
    protected function _getDriver()
    {
        return self::$_driver;
    }
    protected function _placeHolder($condition, $data = null)
    {
        //Сюда кладём параметры, которые будем биндить при запросе
        $bind = array();

        if (isset($data)) {

            if (!is_array($data)) $data = array($data);
            else reset($data);

            foreach ($data as $temp) {
                $pos = strpos($condition, '?');
                $condition = substr($condition, 0, $pos)
                        . $this->_shielding($temp, $condition[$pos+1], $bind)
                        . substr($condition, $pos+2);
            }

        }
        return array('query' => $condition, 'bind' => $bind);
    }
    protected function _shielding($value, $mode, &$bind)
    {
        $mask = function($bind) {
            return ':b' . count($bind);
        };

        switch ($mode) {
            case 'a':
                $mask = $this->_table . '.' . $value;
                break;
            case 'i':
                $mask = $mask($bind);
                $bind[] = array($mask, $value, \PDO::PARAM_INT);
                break;
            case 's':
                $mask = $mask($bind);
                $bind[] = array($mask, $value, \PDO::PARAM_STR);
                break;
        }

        return $mask;
    }
}