<?php
/**
 * Mulanix Framework
 *
 * @version $Id$
 */
namespace Mnix;
/**
 * Абстракция относительного пути
 *
 */
class Uri extends ActiveRecord
{
    protected $_table = 'mnix_uri';
    protected $_hasOne = array(
        //belongsTo
                'page' => array(
                    'class'  => 'Mnix\Engine\Page',
                    'field'  => 'uri_id'),
        //belongsToMany
                'lang' => array(
                    'class' => 'Mnix\Engine\Lang',
                    'field' => 'lang_id')
        );
    protected static $_defaultLang = NULL;
    /**
     * Возвращаем текущий ури
     *
     * @return object(Mnix\Uri)
     */
    public static function current()
    {
        $obj = new Uri;
        return $obj->_parse($_SERVER['PATH_INFO']);
    }
    public static function setDefaultLang($str)
    {
        self::$_defaultLang = $str;
    }
    public function _GET($str)
    {
        
    }
    /**
     * Делим адрес на составные части
     *
     * @param string $data
     * return array
     */
    protected function _explode($data)
    {
        $requests = explode('/', $data);
        $data = array('/');
        //Анализируем на повторяющиеся ''
        foreach ($requests as $request) {
            if ($request !== '') $data[] = $request;
        }
        return $data;
    }
    protected function _getNext($parent_id, $string)
    {
        
    }
    protected function _getLang($str)
    {
        
    }
    /**
     * //Парсер строку адреса и возвращаем ид страницы
     *
     *
     * @param string $data
     * return object(Mnix\Uri)
     */
    protected function _parse($data)
    {
        /*if (isset($data)) $requests = self::_parts($data);
        else $requests = $this->_explode($_SERVER['PATH_INFO']);
        //$db = Mnix_Db::connect();
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
        return $id;*/
        $requests = $this->_explode($data);
        $state = 1;
        $parent_id = 0;
        $i = 0;



        do {
            //var_dump('State: ' . $state);
            switch ($state) {
                //Берём слово из урл и проверяем язык
                case 1:
                    $request = array_shift($requests);
                    if ($request) {
                        if (strlen($request) === 2) {
                            //Тут проверяем язык или ставим дефолтный
                            //$this->_cortege['lang'] =
                            $state = 2;
                        } else $state = 3;
                    }
                    else $state = 0;
                    break;
                //Берём слово из урл
                case 2:
                    $request = array_shift($requests);
                    if ($request) $state = 2;
                    else $state = 0;
                    break;
                //Берём урл
                case 3:
                    $uri = $this->_getNext($parent_id, $request);
                    if ($uri !== FALSE) {
                        $state = 2;
                    } else $state = 0;
                    break;
                //TODO
                //Тут кидать исключение
                default:
                    var_dump('NO state!'.$state);
                    break;
            }
            //Типa защита
            $i++;
            if ($i > 10) {
                //TODO
                //Тут кидать исключение
                var_dump('TO MANY!');
                break;
            }
        } while ($state);

        return $uri;
    }

}