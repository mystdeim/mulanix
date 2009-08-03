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
        if (isset($data)) $requests = self::_parts($data);
        else $requests = self::_parts($_SERVER['REQUEST_URI']);
        $db = Mnix_Db::connect();
        //Ид родительского элемента
        $parent = 0;
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
                    //Обходим урлы, соответствующие родительскому
                    foreach ($res as $temp) {
                        $pattern = '/^' . $temp['regular'] . '$/';
                        if (preg_match($pattern, $request)) {
                            $parent = $temp['id'];
                            $id = $temp['page_id'];
                            break;
                        } else
                            $flag = false;
                            if (!$temp['obligate']) {
                                $parent = $temp['id'];
                                $id = $temp['page_id'];
                            } else {
                                $obligate = false;
                            }
                    }
                }
            } while (!$flag && $obligate);
        }
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