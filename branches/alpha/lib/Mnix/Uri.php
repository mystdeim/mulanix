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
                    if ($this->_checkUri($this->_cortege['id'], $request)) {
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
        $collection = $this->_getParts($this->_cortege['id']);

        $regexp = '';
        foreach ($collection as $temp) $regexp .= $temp->regexp;
    }
    protected function _getUri($request)
    {
        
    }
    protected function _getParts()
    {
        
    }
    /**
     * //Парсер строку адреса и возвращаем ид страницы
     *
     * @param string $data
     * return object(Mnix\Uri)
     */
    protected function _parse($data)
    {
        $requests = $this->_explode($data);
        $name = array_shift($requests);

        $uri = $this->_getNext(0, $name);
        $this->_checkLang(self::$_defaultLang);
        //var_dump($this->_lang);
        
        //Проверяем язык
        $name = array_shift($requests);
        if ($name) {
            if (strlen($name) === 2) {
                //var_dump($name);
                if (!$this->_checkLang($name)) return $this->_getNext(1, '404');
                $name = array_shift($requests);
            }
        }
        
        //Смотрим остальные
        while ($name) {
            $uriNew = $this->_getNext($uri->id, $name);
            if ($uriNew !== false) $uri = $uriNew;
            else return $this->_getNext(1, '404');
            $name = array_shift($requests);
        }
        
        return $uri;
    }

}