<?php

namespace Rens;

class Factory {
	static private $_ns;
	static private $_model;

	static function model($model){
		self::$_ns = __NAMESPACE__;
		self::$_model = $model;

		$modelPath = ROOT . DS . CONF_APPROOT . DS . CONF_MODEL . DS . self::$_model . 'model.php';
		$nsPath = self::$_ns . '\\' . CONF_MODEL . '\\';

		if (file_exists($modelPath)){
			$modelName = $nsPath . ucfirst(self::$_model) . 'Model';
		} else {
			$modelName = $nsPath . ucfirst(U_MODEL) . 'Model';
		}

		return new $modelName(self::$_model);
	}
}