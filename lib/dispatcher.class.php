<?php

/*
 * class Dispatcher
 * 
 * call a controller from namespace 'Rens\controllers' with className "arg[0]+Controller"
 */

namespace Rens;

class Dispatcher {
	private $_ns;
	private $_url;
	private $_default;

	public function __construct(){
		$this->_ns = __NAMESPACE__;
		$this->_url = $GLOBALS['url'];
		$this->_default = $GLOBALS['default'];
	}

	public function callHook(){
		$queryString = array();

		$urlArray = $this->pageGuide($this->_url, $this->_default);

		//controller
		if ($urlArray[0] !== ''){
			$controller = array_shift($urlArray);			
		} else {
			$controller = $default['controller'];
		}
		$controllerName = $this->_ns . '\\' . CONF_CTRLR . '\\' . ucfirst($controller);
		
		//action
		if (count($urlArray) > 0 && $urlArray[0] !== ''){
			$action = array_shift($urlArray);
		} else {
			$action = $this->_default['action'];
		}

		//query
		$queryString = $urlArray;
		
		$dispatch = new $controllerName($controller, $action);
		
		if (method_exists($controllerName, $action)){
			call_user_func_array(array($dispatch,"beforeAction"),$queryString);
			call_user_func_array(array($dispatch,$action),$queryString);
			call_user_func_array(array($dispatch,"afterAction"),$queryString);
		} else {
			new ErrorException();
		}
	}
	
	private function pageGuide($url, $default){
		$urlArray = array();

		if (isset($url)){
			$urlArray = explode("/",$url);

			foreach($urlArray as $val) {
				if (preg_match('/\W/i', $val)){
					new ErrorException();
					die(0);
				}
				
				$val = strtolower($val);
			}		
		} else {
			array_push($urlArray, $default['controller'], $default['action']);
		}

		return $urlArray;
	}
}