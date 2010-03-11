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
    protected $_lang  = null;
    protected $_uri   = null;
    protected $_get   = null;
    protected $_param = array();
    public function  __construct()
    {

    }
    /**
     * Передаётся строка запроса $_SERVER['PATH_INFO']
     *
     * @param string $uri
     * @return object(Mnix\Uri)
     */
    public function putUri($uri)
    {
        $this->_uri = $this->_explode($uri);
        return $this;
    }
    public function putGet($get)
    {
        $this->_get = $get;
        return $this;
    }
    public function putLangObj($lang)
    {
        $this->_lang = $lang;
        return $this;
    }
    public function getLang()
    {
        reset($this->_uri);
        $this->_lang->short = current($this->_uri);
        if ($this->_lang->load()) {
            array_shift($this->_uri);
            return $this->_lang;
        } else return false;
    }
    public function Param()
    {
        //var_dump($this->_param);
        return $this->_param;
    }
    public function parse()
    {
        $this->_cortege['id'] = 1;

        $i = 0;
        $state = 1;
        do {
            $i++;
            switch ($state) {
                case 1:
                    $request = array_shift($this->_uri);
                    if ($request) $state = 2;
                    else $state = 3;
                    break;
                case 2:
                    if ($this->_checkUri($request)) {
                        $state = 4;
                        $flag  = true;
                    } else {
                        $state = 0;
                        $flag  = false;
                    }
                    break;
                case 3:
                    $state = 0;
                    $flag  = true;
                    break;
                case 4:
                    $request = array_shift($this->_uri);
                    if ($request) $state = 2;
                    else $state = 0;
                    break;
            }
        } while ($state && $i<100);
        return $flag;
    }
    /**
     * Делим адрес на составные части
     *
     * @param string $data
     * return array
     */
    protected function _explode($data)
    {
        //Deleting $_GET parametrs
        //$data = explode('?', $data);
        $requests = explode('/', $data);
        $data = array();
        //Анализируем на повторяющиеся ''
        foreach ($requests as $request) {
            if ($request !== '') $data[] = $request;
        }
        return $data;
    }
    protected function _checkUri($request)
    {
        $flag = false;
        $uries = $this->_getUries($this->_cortege['id']);
        
        foreach ($uries as $uri) {
            $parts = $this->_getParts($uri['id']);

            $regexp = '';
            foreach ($parts as $part) $regexp .= '('.$part['regexp'].')';

            if (preg_match('/^'.$regexp.'$/', $request)) {

                //Смотрим параметры в ЧПУ
                foreach ($parts as $part) {
                    if ($part['type']) {
                        preg_match('/'.$part['regexp'].'/', $request, $pocket);
                        $this->_param[$part['name']] = $pocket[0];
                    }
                    $request = preg_replace('/'.$part['regexp'].'/', '', $request);
                }

                //Проверяем параметры в ЧПУ
                $gets = $this->_getGetParams($uri['id']);
                foreach($gets as $get) {
                    if (isset($this->_get[$get['name']])) 
                        $this->_param[$get['name']] = $this->_get[$get['name']];
                }

                $flag = true;
                break;
            }
        }
        return $flag;
    }
    protected function _getUries($parent)
    {
        
    }
    protected function _getParts($parent)
    {
        
    }
    protected function _getGetParams($parent)
    {

    }
}