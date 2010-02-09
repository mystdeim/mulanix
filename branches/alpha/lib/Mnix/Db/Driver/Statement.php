<?php
/**
 * Mulanix Framework
 */
namespace Mnix\Db\Driver;
/**
 * Mulanix Framework
 *
 * @author deim
 */
class Statement extends \PDOStatement
{
    protected $_pdo;
    protected $_boundParams;
    protected function __construct($pdo)
    {
        $this->_pdo = $pdo;
    }
    public function bindValue($parameter, $value, $data_type = \PDO::PARAM_STR)
    {
        $this->_boundParams[$parameter] = array(
            'value' => $value,
            'type' => $data_type
        );
        parent::bindValue($parameter, $value, $data_type);
    }
    public function getSQL()
    {
        $sql = $this->queryString;

        if (count($this->_boundParams)) {
            foreach ($this->_boundParams as $key => $param) {
                $pdo = $this->_pdo;
                $cast = function($value, $type) use ($pdo, $param) {
                            switch ($type) {
                                case \PDO::PARAM_BOOL:
                                    return (bool) $value;
                                    break;
                                case \PDO::PARAM_NULL:
                                    return null;
                                    break;
                                case \PDO::PARAM_INT:
                                    return (int) $value;
                                    break;
                                default:
                                    return $pdo->quote($param['value'], $param['type']);
                            }
                        };
                $sql = str_replace($key, $cast($param['value'], $param['type']), $sql);
            }
        }
        return $sql;

    }
}