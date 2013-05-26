<?php

namespace Rens;

class Template {
	private $_controller;
	private $_action;

	public $_html;

	protected $variables = array();

	function __construct($controller, $action){
		$this->_controller = $controller;
		$this->_action = $action;	
		$this->_html = new HTML;
	}

	/** Set Variables **/
	function set($name,$value){
		$this->variables[$name] = $value;
	}

    function render($stdRender = 0){
		$html = $this->_html;

		extract($this->variables);

		if ($stdRender == 1){
			if (file_exists(ROOT . DS . CONF_APPROOT . DS . CONF_VIEW . DS . $this->_controller . DS . 'header.php')) {
				require_once (ROOT . DS . CONF_APPROOT . DS . CONF_VIEW . DS . $this->_controller . DS . 'header.php');
			} else {
				require_once (ROOT . DS . CONF_APPROOT . DS . CONF_VIEW . DS . 'header.php');
			}
		}

		if (file_exists(ROOT . DS . CONF_APPROOT . DS . CONF_VIEW . DS . $this->_controller . DS . $this->_action . '.php')) {
			require_once (ROOT . DS . CONF_APPROOT . DS . CONF_VIEW . DS . $this->_controller . DS . $this->_action . '.php');
		}

		if ($stdRender == 1){
			if (file_exists(ROOT . DS . CONF_APPROOT . DS . CONF_VIEW . DS . $this->_controller . DS . 'footer.php')) {
				require_once (ROOT . DS . CONF_APPROOT . DS . CONF_VIEW . DS . $this->_controller . DS . 'footer.php');
			} else {
				require_once (ROOT . DS . CONF_APPROOT . DS . CONF_VIEW . DS . 'footer.php');
			}
		}
    }
}