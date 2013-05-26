<?php

namespace Rens;

class Autoload {
	private $_ns;
	private $_path = array();
	private $_libMap = array();

	public function __construct($libMap, $libPath){
		$this->_ns = __NAMESPACE__;		
		
		$this->setClassMap($libMap);
		$this->setClassPath($libPath);
		$this->register();
	}

	protected function setClassPath($libPath){
		foreach ($libPath as $p){
			array_push($this->_path, ROOT . DS . $p);
		}		
	}
	protected function setClassMap($libMap){
		$this->_libMap = $libMap;
	}

	protected function register(){
		spl_autoload_register(array($this, 'loader'));
	}
	
	public function loader($className){
		//通过lib类映射表加载
		if (isset($this->_libMap[$className])){
			require_once(ROOT . DS . CONF_LIB . DS . $this->_libMap[$className] . '.class.php');
			return;
		}

		//其他		
		if (strpos($className, $this->_ns) === 0){
			$file = strtolower(str_replace('\\', DS, $className));
			
			foreach ($this->_path as $p){
				$file = preg_replace('/^'. $this->_ns .'/i', $p, $file) . '.php';			

				if (file_exists($file)){
					require_once $file;
					return;
				}
			}
		}
		else {
			new Errorhandler();
			die(0);
		}
		
		new Errorhandler();
		die(0);
	}

	public function unregister(){
		spl_autoload_unregister(array($this, 'loader'));
	}

}