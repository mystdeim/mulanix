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
        /*$res_p = $db->select()->from('mnix_test_uri', '*')
                              ->where('?t = ?i', array('parent', 0))
                              ->query();
        //Ид соответствующей страницы
        $id = (int)$res_p[0]['page_id'];
        foreach ($requests as $request) {
            //Ищем строки по родительскому идишнику
            $res = $db->select()->from('mnix_test_uri', '*')
                                ->where('?t = ?i', array('parent', $res_p[0]['id']))
                                ->query();
            //var_dump($res);
            if ($res) {
                //Соответсвие регулярному выражению
                $flag = false;
                var_dump($res);
                for ($i=0; $i < count($res) && !$flag; $i++) {
                    $pattern = '\'/^' . $res[$i]['regular'] . '$/\'';
                    if (preg_match($pattern, $request, $matches)) $flag = true;
                }
                //var_dump($res[$i]);
                if ($flag) $id = $res[--$i]['id'];
            }
        }
        return $id;*/
        $flag = true;
        $parent = 0;
        $id = 0;
        $iii = 0;
        reset($requests);
        do {
            $request = current($requests);
            //Ищем урлы по родительскому идишник
            $res = $db->select()->from('mnix_test_uri', '*')
                                ->where('?t = ?i', array('parent', $parent))
                                ->query();
            //var_dump($res);
            //var_dump($request);
            //Обходим урлы, соответствующие родительскому
            foreach ($res as $temp) {
                $pattern = '/^' . $temp['regular'] . '$/';
                //var_dump($pattern);
                if (preg_match($pattern, $request)) {
                    $parent = $temp['id'];
                    $id = $temp['page_id'];
                    //var_dump('NEXT!');
                    next($requests);
                    break;
                } else
                    if (preg_match($pattern, '')) {
                        $parent = $temp['id'];
                        $id = $temp['page_id'];
                        //var_dump('PROPYSK!');
                        break;
                    } else {

                    }
            }
            $iii++;
            if ($iii > 2) $flag = false;
        } while ($flag);
        //var_dump($id);
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
        $data = array();
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