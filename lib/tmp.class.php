<?php

namespace Rens;

class Tmp {

	//删除相应全局变量
	function stripSlashesDeep($value){
		$value = is_array($value) ? array_map('stripSlashesDeep', $value) : stripslashes($value);
		return $value;
	}
		
	function removeMagicQuotes(){
		if (get_magic_quotes_gpc()){
			$_GET    = $this->stripSlashesDeep($_GET   );
			$_POST   = $this->stripSlashesDeep($_POST  );
			$_COOKIE = $this->stripSlashesDeep($_COOKIE);
		}
	}
	
	//删除register globals，PHP5.4已删除此配置
	
	function unregisterGlobals(){
		if (ini_get('register_globals')){
			$array = array('_SESSION', '_POST', '_GET', '_COOKIE', '_REQUEST', '_SERVER', '_ENV', '_FILES');
			foreach ($array as $value){
				foreach ($GLOBALS[$value] as $key => $var){
					if ($var === $GLOBALS[$key]){
						unset($GLOBALS[$key]);
					}
				}
			}
		}
	}
}