<?php
session_start();

define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(__DIR__));

define('CONF_APPROOT', 'app');
define('CONF_CTRLR', 'controllers');
define('CONF_MODEL', 'models');
define('CONF_VIEW', 'views');
define('CONF_LIB', 'lib');

$url = !empty($_GET['url']) ? $_GET['url'] : NULL;

require_once (ROOT . DS . 'config' . DS . 'config.php');
require_once (ROOT . DS . 'lib' . DS . 'autoload.php');

require_once (ROOT . DS . 'lib' . DS . 'shared.php');