<?php
/**
 * Mulanix Framework
 *
 * @category Mulanix
 * @package Mnix_Core
 * @version 2009-08-10
 * @since 2009-08-10
 */
/**
 * @category Mulanix
 * @package Mnix_Core
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