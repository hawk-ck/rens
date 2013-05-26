<?php

namespace Rens;

class ZPController {
	private $_ns;
	private $_controller;
	private $_model;
	private $_action;
	private $_template;
	private $_factory;

	protected $modelIns;
	protected $html;
	protected $tableList;
	protected $cache;
	
	public $stdRender;
	public $render;

	function __construct($controller, $action){
		$this->_ns = __NAMESPACE__;
		$this->_model = $this->_controller = $controller;
		$this->_action = $action;
		
		$this->cache = array();

		$this->stdRender = 1;
		$this->render = 1;

		$this->modelIns = Factory::model($this->_model);
		$this->_template = new Template($this->_controller, $this->_action);
		
		$this->html = $this->_template->_html;
	}
	
	protected function setTableList($tableList){
		if (is_array($tableList)){
			$this->tableList = $tableList;		
		} else {
			$this->tableList = array($this->_model);
		}
	}
	
	protected function useCache($cache){
		array_push($this->cache, $cache);
	}
	
	protected function clearCache(){
		$this->cache = array();
	}
	
	protected function setPageVal($name, $value){
		$this->_template->set($name, $value);
	}

	function __destruct(){
		if ($this->render){
			$this->_template->render($this->stdRender);
		}
	}
}