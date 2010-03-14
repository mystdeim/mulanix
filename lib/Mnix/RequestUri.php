<?php
/**
 * Mulanix Framework
 */
namespace Mnix;
/**
 * Mulanix Framework
 *
 * @author deim
 */
class RequestUri
{
    const LANG = 'Mnix\Lang';
    const URI  = 'Mnix\Uri';
    protected $_lang;
    protected $_uri;
    protected $_query;
    public function putQuery($query)
    {
        $this->_query = $this->_explode($query);
        return $this;
    }
    public function langExists()
    {
        $lang = new LANG;
        $lang->short = 'en';
        if ($lang->load()) {
            $this->_lang = $lang;
            return true;
        }
        else return false;
    }
    public function getLang()
    {
        return $this->_lang;
    }
    public function uriExists()
    {
        
    }
    public function getUri()
    {
        return $this->_uri;
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
        $data = explode('?', $data);
        $requests = explode('/', $data[0]);
        $data = array();
        //Анализируем на повторяющиеся ''
        foreach ($requests as $request) {
            if ($request !== '') $data[] = $request;
        }
        return $data;
    }
}