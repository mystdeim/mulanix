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
    protected static $_defaultLang = NULL;
    /**
     * Возвращаем текущий ури
     *
     * @return object(Mnix\Uri)
     */
    public static function current()
    {
        $obj = new Uri;
        return $obj->_parse($_SERVER['REQUEST_URI']);
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
    protected function _getNext($parent, $name)
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

        $requests = $this->_explode($data);
        //array_shift($requests);
        $state = 1;
        $parent_id = 0;
        $i = 0;
        do {
            switch ($state) {
                //Берём слово из урл и проверяем язык
                case 1:
                    $request = array_shift($requests);
                    if ($request) {
                        if (strlen($request) === 2) {
                            //Тут проверяем язык или ставим дефолтный
                            //TODO:
                            //$this->_cortege['lang'] =
                            $state = 2;
                        } else $state = 3;
                    } else $state = 0;
                    break;
                //Берём слово из урл
                case 2:
                    $request = array_shift($requests);
                    if ($request) $state = 3;
                    else $state = 0;
                    break;
                //Берём урл
                case 3:
                    var_dump($request);
                    $uri = $this->_getNext($parent_id, $request);
                    if ($uri !== FALSE) $state = 2;
                    else $state = 0;
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