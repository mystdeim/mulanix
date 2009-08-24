<?php
/**
 * Mulanix Framework
 *
 * @category Mulanix
 * @package Mnix_Core
 * @version $Id$
 */
/**
 * @category Mulanix
 * @package Mnix_Core
 */
abstract class Mnix_Core_Controller
{
    /**
     * Параметры GET-запроса
     *
     * @var array
     */
	protected $_uriParam;
    /**
     * Ссылка на xml-документ
     * 
     * @var object(DOMDocument)
     */
    protected $_xml;
    /**
     * Корневая нода, для контента контроллера
     * 
     * @var object(DOMElement)
     */
    protected $_xmlNodeTemplate;
    /**
     * Конструктор
     *
     * @param object(DOMDocument) $xml - ссылка на DOMDocument
     * @param array $uriParam - параметры GET-запроса
     */
	public function __construct($xml, $uriParam, $xmlNodeTemplate)
    {
        //Запоминаем параметры
        $this->_xml = $xml;
        $this->_uriParam = $uriParam;
        $this->_xmlNodeTemplate = $xmlNodeTemplate;

    }
    /**
     * Генерация контенка контроллером
     *
     * После отработки возвращается нода со сгенерированным контентом
     *
     * return object(DOMElement)
     */
    abstract public function run();
}