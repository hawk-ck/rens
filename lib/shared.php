<?php

/** 根据是否为开发环境控制errors显示，记住应用时设为false **/
function setReporting(){
	if (DEVELOPMENT_ENVIRONMENT == true){
		error_reporting(E_ALL);
		ini_set('display_errors','On');
	} else{
		error_reporting(E_ALL);
		ini_set('display_errors','Off');
		ini_set('log_errors', 'On');
		ini_set('error_log', ROOT.DS.'tmp'.DS.'logs'.DS.'error.log');
	}
}

/**  exception_handler  **/
function exception_handler($exception) {
	echo "Uncaught exception: " . $exception->getMessage();
}

set_exception_handler('exception_handler');

/** Autoload **/
$autoload = new Rens\Autoload($_libMap, $_libPath);

setReporting();
//$tmp = new Rens\Tmp();
//$tmp->removeMagicQuotes();
//$tmp->unregisterGlobals();

$routing = new Rens\Dispatcher;
$routing->callHook();