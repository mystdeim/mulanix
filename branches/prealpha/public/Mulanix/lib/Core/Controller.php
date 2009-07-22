<?php
/**
 * Mulanix Framework
 *
 * @package Mnix_Core
 * @author deim
 * @copyright 2009
 */
/**
 * @package Mnix_Core_Controller
 */
class Mnix_Core_Controller
{
	protected $_db;
	protected $_param;
    protected $_rights;
	protected $_xml;
	protected $_data;
	protected $_xsl;

	public function __construct($xml, $xsl, $rights, $param)
    {
        $this->_xml = $xml;
		$this->_xsl = $xsl;
        $this->_rights = $rights;
        $this->_param = $param;
	}
}