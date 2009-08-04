<?php
/**
 * Mulanix Framework
 *
 * @category Mulanix
 * @package Mnix_Ri
 * @since 2008-10-01
 * @version 2009-05-08
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
    protected $_url;
    /**
     * Парсер строку адреса и возвращаем ид страницы
     *
     * @param string $data
     * return int
     */
    /*protected static function _parse($data = null)
    {
        if (isset($data)) $requests = self::_parts($data);
        else $requests = self::_parts($_SERVER['REQUEST_URI']);
        $db = Mnix_Db::connect();
        $state = 1;
        $data = array();
        $parent = 0;
        $iii = 0;
        do {
            //var_dump('State:'.$state);
            switch ($state) {
                //Берём слово из урл
                case 1:
                    $request = array_shift($requests);
                    if ($request) $state = 2;
                    else $state = 0;
                    break;
                //Получаем группы
                case 2:
                    $res = $db->select()->from('mnix_test_uri', '*')
                                        ->where('?t = ?i', array('parent', $parent))
                                        ->query();
                    if ($res) {
                        foreach ($res as $key => $val) $data[$val['group']][$val['order']] = $val;
                        unset($res);
                        //var_dump($data);
                        $group = array_shift($data);
                        $state = 3;
                    } else $state = 8;
                    break;
                //Берём урл из группы
                case 3:
                    $uri = array_shift($group);
                    if ($uri) $state = 4;
                    else $state = 8;
                    break;
                //Проверяем обязательность
                case 4:
                    if ($uri['obligate']) {

                        $parent = $uri['group'];
                        $id = $uri['page_id'];

                        $state = 5;
                    } else $state = 9;
                    break;
                //Проверяем регулярку
                case 5:
                    $pattern = '/^' . $uri['regular'] . '$/';
                    if (preg_match($pattern, $request)) {
                        $parent = $uri['group'];
                        $id = $uri['page_id'];
                        $state = 6;
                    } else $state = 12;
                    break;
                //Если урлов нет, то в 1
                case 6:
                    $uri = array_shift($group);
                    if ($uri) $state = 7;
                    else $state = 1;
                    break;
                //Смотрим остались ли запросы
                case 7:
                    $request = array_shift($requests);
                    if ($request) $state = 4;
                    else $state = 0;
                    break;
                //Тут выдаём ошибочную страницу
                case 8:
                    $res = $db->select()->from('mnix_test_uri', '*')
                                        ->where('?t = ?i', array('group', -1))
                                        ->query();
                    $id = $res[0]['page_id'];
                    $state = 0;
                    break;
                //Проверяем регулярку
                case 9:
                    //var_dump($uri);
                    $pattern = '/^' . $uri['regular'] . '$/';
                    $parent = $uri['group'];
                    $id = $uri['page_id'];
                    if (preg_match($pattern, $request)) {
                        $state = 6;
                    } else $state = 10;
                    break;
                //Если урлов нет, то в 11
                case 10:
                    $uri = array_shift($group);
                    if ($uri) $state = 4;
                    else $state = 11;
                    break;
                //Проверяем остались ли группы
                case 11:
                    $group = array_shift($data);
                    if ($group) $state = 3;
                    else $state = 2;
                    //exit();
                    break;
                //Проверяем остались ли группы
                case 12:
                    $group = array_shift($data);
                    if ($group) $state = 3;
                    else $state = 8;
                    break;
                //TODO
                //Тут кидать исключение
                default:
                    var_dump('NO state!'.$state);
                    break;
            }
            //Типa защита
            $iii++;
            if ($iii > 100) {
                //TODO
                //Тут кидать исключение
                break;
            }
        } while ($state);
        return $id;
    }*/
    protected static function _parse($data = null)
    {
        if (isset($data)) $requests = self::_parts($data);
        else $requests = self::_parts($_SERVER['REQUEST_URI']);
        $db = Mnix_Db::connect();
        $state = 1;
        $data = array();
        $parent = 0;
        $iii = 0;
        do {
            //var_dump('State:'.$state);
            switch ($state) {
                //Берём слово из урл
                case 1:
                    $request = array_shift($requests);
                    if ($request) {
                        $state = 2;
                    }
                    else $state = 0;
                    break;
                //Берём урл
                case 2:
                    $res_t = $db->select()->from('mnix_test_uri2', '*')
                                          ->where('?t = ?i', array('parent', $parent))
                                          ->query();
                    if (isset($res) && count($res)) $res = array_merge($res_t, $res);
                    else $res = $res_t;
                    if ($res) {
                        //var_dump($res);
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
                        $state = 1;
                    } else $state = 7;
                    break;
                //Тут выдаём ошибочную страницу
                case 5:
                    $res = $db->select()->from('mnix_test_uri2', '*')
                                        ->where('?t = ?i', array('parent', -1))
                                        ->query();
                    $id = $res[0]['page_id'];
                    $state = 0;
                    break;
                //Проверяем регулярку
                case 6:
                    $pattern = '/^' . $uri['regular'] . '$/';
                    $parent = $uri['id'];
                    if (preg_match($pattern, $request)) {
                        $id = $uri['page_id'];
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
            $iii++;
            if ($iii > 100) {
                //TODO
                //Тут кидать исключение
                var_dump('TO MANY!');
                break;
            }
        } while ($state);
        return $id;
    }
    /**
     * Делим адрес на составные части
     *
     * @param string $data
     * return array
     */
    protected static function _parts($data)
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
        self::_parse();
        return new Mnix_Uri(1);
    }
}