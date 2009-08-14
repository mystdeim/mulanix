<?php
/**
 * Mulanix Framework
 *
 * @category Mulanix
 * @package Mnix_Uri
 * @version $Id$
 */
/**
 * Абстракция относительного пути
 *
 * @category Mulanix
 * @package Mnix_Uri
 */
class Mnix_Uri extends Mnix_ORM_Prototype
{
    protected $_table = 'mnix_uri';
    protected $_has_one = array(
		'page' => array(
				'class'  => 'Mnix_Engine_Page',
				'id'	 => 'page_id'));
    /**
     * Параметры в гет-запросе, если они есть
     *
     * @var array
     */
    protected $_param = array();
    /**
     * Проверяем какой параметр содержится в запросе
     * 
     * @param string $string
     * @param string $regular
     * @param string $name
     * @return boolean|int|string
     */
    protected function _checkParam($string, $regular, $name)
    {
        if (isset($regular) && preg_match('/' . $regular . '/', $string, $matches)) {
            $this->_param[$name] = current($matches);
            return current($matches);
        } else return false;
    }
    public function getParam()
    {
        if (!isset($this->_param['lang'])) $this->_param['lang'] = MNIX_DEFAULT_LANG;
        return $this->_param;
    }
    /**
     * Парсер строку адреса и возвращаем ид страницы
     *
     * @param string $data
     * return int
     */
    protected function _parse($data = null)
    {
        if (isset($data)) $requests = self::_parts($data);
        else $requests = self::_parts($_SERVER['REQUEST_URI']);
        $db = Mnix_Db::connect();
        $state = 1;
        $data = array();
        $parent = 0;
        $i = 0;
        do {
            //var_dump('State: ' . $state);
            switch ($state) {
                //Берём слово из урл
                case 1:
                    $res = array();
                    $request = array_shift($requests);
                    if ($request) {
                        $state = 2;
                    }
                    else $state = 0;
                    break;
                //Берём урл
                case 2:
                    $res_t = $db->select()->from($this->_table, '*')
                                          ->where('?t = ?i', array('parent', $parent))
                                          ->query();
                    if (isset($res) && count($res)) $res = array_merge($res_t, $res);
                    else $res = $res_t;
                    //var_dump($res);
                    if ($res) {
                        $uri = array_shift($res);
                        $state = 3;
                    } else $state = 5;
                    break;
                //Проверяем обязательность
                case 3:
                    if ($uri['obligate']) {
                        $parent = $uri['id'];
                        $id = $uri['page_id'];
                        $state = 4;
                    } else $state = 6;
                    break;
                //Проверяем регулярку
                case 4:
                    $pattern = '/^' . $uri['regular'] . '$/';
                    if (preg_match($pattern, $request)) {
                        $parent = $uri['id'];
                        $id = $uri['page_id'];
                        $this->_checkParam($request, $uri['parametr'], $uri['name']);
                        $state = 1;
                    } else $state = 7;
                    break;
                //Тут выдаём ошибочную страницу
                case 5:
                    $res = $db->select()->from($this->_table, '*')
                                        ->where('?t = ?i', array('parent', -1))
                                        ->query();
                    $id = $res[0]['page_id'];
                    $uri = $res[0];
                    $state = 0;
                    break;
                //Проверяем регулярку
                case 6:
                    $pattern = '/^' . $uri['regular'] . '$/';
                    $parent = $uri['id'];
                    if (preg_match($pattern, $request)) {
                        $id = $uri['page_id'];
                        $this->_checkParam($request, $uri['parametr'], $uri['name']);
                        $state = 1;
                    } else $state = 2;
                    break;
                //Смотрим остались ли урлы
                case 7:
                    $uri = array_shift($res);
                    if ($uri) $state = 3;
                    else $state = 5;
                    break;
                //TODO
                //Тут кидать исключение
                default:
                    var_dump('NO state!'.$state);
                    break;
            }
            //Типa защита
            $i++;
            if ($i > 100) {
                //TODO
                //Тут кидать исключение
                var_dump('TO MANY!');
                break;
            }
        } while ($state);
        $this->set($uri);
        return $id;
    }
    /**
     * Делим адрес на составные части
     *
     * @param string $data
     * return array
     */
    protected function _parts($data)
    {
        $requests = explode('/', $data);
        $data = array('/');
        //Анализируем на повторяющиеся ''
        foreach ($requests as $request) {
            if ($request !== '') $data[] = $request;
        }
        return $data;
    }
    /**
     * Возвращаем текущий ури
     *
     * @return Mnix_Uri
     */
    public static function current()
    {
        $obj = new Mnix_Uri;
        $obj->_parse();
        return $obj;
    }
}