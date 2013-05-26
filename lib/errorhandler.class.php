<?php

namespace Rens;

class Errorhandler {

	function __construct(){
		if (file_exists(ROOT . DS . CONF_APPROOT . DS . CONF_VIEW . DS . '404.php')){
			require_once (ROOT . DS . CONF_APPROOT . DS . CONF_VIEW . DS . '404.php');
		} else {
			echo '<!DOCTYPE html><html><head><meta charset="utf-8"><title>404</title></head><body><h1>404 - Page not found</h1></body></html>';
		}
	}
}