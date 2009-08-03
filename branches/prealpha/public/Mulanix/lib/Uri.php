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
    protected static function _parse($data = null)
    {
        /*if (isset($data)) $requests = self::_parts($data);
        else $requests = self::_parts($_SERVER['REQUEST_URI']);
        $db = Mnix_Db::connect();*/
        //Ид родительского элемента
        /*$parent = 0;
        foreach ($requests as $request) {
            do {
                //Обязательность регулярки
                $obligate = true;
                //Совпадение регулярки
                $flag = true;
                //Ищем строки по родительскому идишнику
                $res = $db->select()->from('mnix_test_uri', '*')
                                    ->where('?t = ?i', array('parent', $parent))
                                    ->query();
                var_dump($res);
                //Распределяем по группам
                foreach ($res as $temp) {
                    $res[$temp['group']][$temp['order']] = $temp;
                }
                var_dump($res);
                if ($res) {
                    //Обходим урлы, соответствующие родительскому
                    foreach ($res as $temp) {
                        $pattern = '/^' . $temp['regular'] . '$/';
                        $group = $temp['group'];
                        if (preg_match($pattern, $request)) {
                            $parent = $temp['group'];
                            $id = $temp['page_id'];
                            break;
                        } else
                            $flag = false;
                            if (!$temp['obligate']) {
                                $parent = $temp['group'];
                                $id = $temp['page_id'];
                            } else {
                                $obligate = false;
                            }
                    }
                }
                var_dump(!$flag && !$obligate);
                //if (!$flag && !$obligate) break;
            } while (!$flag && $obligate);
        }*/
        /*$parent = 0;
        $ii = 0;
        //var_dump($requests);
        foreach ($requests as $request) {
            do {
                //Обязательность регулярки
                $obligate = true;
                //Совпадение регулярки
                $flag = true;
                //Ищем строки по родительскому идишнику
                $res = $db->select()->from('mnix_test_uri', '*')
                                    ->where('?t = ?i', array('parent', $parent))
                                    ->query();
                if ($res) {
                    //Распределяем по группам
                    $data = array();
                    foreach ($res as $key => $val) $data[$val['group']][$val['order']] = $val;
                    unset($res);
                    //Обходим группы
                    foreach ($data as $group) {
                        //var_dump($group);
                        //Обходим урлы, соответствующие группе
                        foreach ($group as $temp) {
                            //var_dump($temp);
                            $pattern = '/^' . $temp['regular'] . '$/';
                            $group = $temp['group'];
                            if (preg_match($pattern, $request)) {
                                $parent = $temp['group'];
                                $id = $temp['page_id'];
                            } else
                                $flag = false;
                                if (!$temp['obligate']) {
                                    $parent = $temp['group'];
                                    $id = $temp['page_id'];
                                } else {
                                    $obligate = false;
                                }
                        }
                        //Если нашли совпадения то выходим
                        if ($flag) break;
                    }
                }
            } while (!$flag && $obligate);
        }
        //Если не было последнего совпадения, то суём ошибку
        if (!$flag) {
            $res = $db->select()->from('mnix_test_uri', '*')
                                ->where('?t = ?i', array('group', -1))
                                ->query();
            $id = $res[0]['page_id'];
        }

        return $id;*/
        return self::_parse2($data);
    }
    protected static function _parse2($data = null)
    {
        if (isset($data)) $requests = self::_parts($data);
        else $requests = self::_parts($_SERVER['REQUEST_URI']);
        $db = Mnix_Db::connect();
        $state = 'first';
        $parent = 0;
        $iii = 0;
        do {
            var_dump('State:'.$state);
            switch ($state) {
                case 'first':
                    $request = array_shift($requests);
                    if ($request) $state = 'request';
                    else $state = 'end';
                    break;
                case 'request':
                    $res = $db->select()->from('mnix_test_uri', '*')
                                        ->where('?t = ?i', array('parent', $parent))
                                        ->query();
                    if ($res) {
                        $data = array();
                        foreach ($res as $key => $val) $data[$val['group']][$val['order']] = $val;
                        unset($res);
                        //var_dump($data);
                        $group = array_shift($data);
                        $state = 'group';
                    } else $state = 'end';
                    //var_dump($state);
                    break;
                case 'group':
                    $uri = array_shift($group);
                    if ($uri) $state = 'uri';
                    break;
                case 'uri':
                    //var_dump($uri)
                    $pattern = '/^' . $uri['regular'] . '$/';
                    $group = $uri['group'];
                    if (preg_match($pattern, $request)) {
                        $parent = $uri['group'];
                        $id = $uri['page_id'];
                        $state = 'first';
                    } else {
                        if (!$uri['obligate']) {
                            $parent = $uri['group'];
                            $id = $uri['page_id'];
                            $state = 'uri2';
                        } else {
                            $group = array_shift($data);
                            if ($group) $state = 'group';
                            else $state = 'error';
                        }
                    }
                    break;
                case 'uri2':
                    //var_dump($data);
                    $group = array_shift($data);
                    if ($group) $state = 'group';
                    else {
                        if ($request) $state = 'request';
                        else $state = 'error';
                    }
                    break;
                case 'error':
                    $res = $db->select()->from('mnix_test_uri', '*')
                                        ->where('?t = ?i', array('group', -1))
                                        ->query();
                    $id = $res[0]['page_id'];
                    $state = 'end';
                    break;
                default:
                    var_dump('NO state!');
                    break;
            }
            $iii++;
        } while ($state !== 'end' && $iii < 100);
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