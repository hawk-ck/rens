<?php

//开发环境设置
define ('DEVELOPMENT_ENVIRONMENT', TRUE);

//DB设置
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_NAME', 'test');
define('DB_PASSWORD', '');

//URI根路径，用于styles和js载入
define('BASE_PATH', 'http://localhost/rens/');

//用户认证设置
define('AUTHENTICATE', FALSE);

//sql limit限制
define('PAGINATE_LIMIT', 25);

//default display
$default['controller'] = 'window';
$default['action'] = 'index';

//universal model
define('U_MODEL', 'universal');

//autoload Maps & Path
$_libMap = array(
	'Rens\Dispatcher' => 'dispatcher',
	'Rens\RensException' => 'exception',
	'Rens\HTML' => 'html',
	'Rens\Template' => 'template',
	'Rens\Tmp' => 'tmp',
	'Rens\Errorhandler' => 'errorhandler',
	'Rens\ZPController' => 'zpcontroller',
	'Rens\ZPModel' => 'zpmodel',
	'Rens\Factory' => 'factory'
);

$_libPath = array (CONF_APPROOT, CONF_LIB);