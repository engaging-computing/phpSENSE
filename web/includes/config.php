<?php

/* Setup Global Directories */
define('DATA_DIR', dirname(dirname(__FILE__)) . '/data/');
define('CONF_DIR', dirname(__FILE__) . '/conf/');
define('API_DIR',  dirname(__FILE__) . '/api/');
define('LIB_DIR',  dirname(__FILE__) . '/lib/');
define('BASE_DIR', dirname(dirname(__FILE__)));
define('PIC_DIR',  DATA_DIR . 'pics/');

set_include_path(LIB_DIR . PATH_SEPARATOR . get_include_path());
ini_set('auto_detect_line_endings', 1);

/* Load the System Config, based on host */
$config_file = CONF_DIR;
$config_file .= ($_SERVER['REMOTE_ADDR'] == '127.0.0.1') ? "debugging.xml" : "production.xml";
$config = simplexml_load_file($config_file);

/* Set Error Reporting, based on host */
$error_level  = ($_SERVER['REMOTE_ADDR'] == '127.0.0.1') ? E_ALL : E_ERROR;
error_reporting($error_level);

/* Set Timze Zone */
date_default_timezone_set((string) $config->timezone);

/* Set Database Constants */
define('DB_NAME', (string) $config->database->name);
define('DB_USER', (string) $config->database->user);
define('DB_PASS', (string) $config->database->pass);
define('DB_HOST', (string) $config->database->host);
define('DB_PORT', (string) $config->database->port);

/* Set Mongo Constants */
define('MDB_DATABASE', (string) $config->mongo->name);

/* Setup AWS Keys */
define('AWS_ACCESS_KEY', 	(string) $config->apis->aws->accesskey);
define('AWS_SECRET_KEY', 	(string) $config->apis->aws->secretkey);
define('AWS_IMG_BUCKET', 	(string) $config->apis->aws->bucket);

/* Setup YouTube Keys */
define('YOUTUBE_USER',		(string) $config->apis->youtube->user);
define('YOUTUBE_PASS',		(string) $config->apis->youtube->pass);
define('YOUTUBE_KEY',		(string) $config->apis->youtube->key);
define('YOUTUBE_CLIENTID',	(string) $config->apis->youtube->clientid);
define('YOUTUBE_APPID',		(string) $config->apis->youtube->appid);
define('YOUTUBE_PUB',		(((string) $config->apis->youtube->public) == "on") ? true : false);

/* Setup Twitter Keys */
define('TWITTER_USER', 		(string) $config->apis->twitter->user);
define('TWITTER_PASS', 		(string) $config->apis->twitter->pass);
define('TWITTER_PUB',		(((string) $config->apis->twitter->public) == "on") ? true : false);

/* Setup Delicious Keys */
define('DELICIOUS_USER', 	(string) $config->apis->delicious->user);
define('DELICIOUS_PASS', 	(string) $config->apis->delicious->pass);
define('DELICIOUS_PUB',		(((string) $config->apis->delicious->public) == "on") ? true : false);

/* Setup RECAPTCHA Keys */
define('RECAPTCHA_PUBLIC',	(string) $config->apis->recaptcha->public);
define('RECAPTCHA_PRIVATE',	(string) $config->apis->recaptcha->private);

/* Set Google Maps Key */
define('GMAP_KEY',			(string) $config->apis->gmaps->key);

/* Set Google Analytics Account */
define('GOOGLE_ACCOUNT',	(string) $config->analytics->account);

$flotval = (string) $config->flot;
$flotval = (($flotval == "true") ? true : false);
define('FLOT_ENABLED',      $flotval);

/* Initalizes the Database object */
require_once 'database.php';
require_once 'mongo.php';

/* Initalizes the Session object */
require_once 'session.php';
$session = new Session();
$session->start();

/* Include API Libraries */
require_once API_DIR . 'authentication.php';
require_once API_DIR . 'event.php';
require_once API_DIR . 'geocode.php';
require_once API_DIR . 'media.php';
require_once API_DIR . 'news.php';
require_once API_DIR . 'search.php';
require_once API_DIR . 'experiment.php';
require_once API_DIR . 'session.php';
require_once API_DIR . 'social.php';
require_once API_DIR . 'search.php';
require_once API_DIR . 'support.php';
require_once API_DIR . 'user.php';
require_once API_DIR . 'util.php';
require_once API_DIR . 'vis.php';
require_once API_DIR . 'graph.php';
require_once API_DIR . 'activity.php';

/* Initalizes Smarty Templates */
require_once 'smarty/lib/Smarty.class.php';

$smarty = new Smarty();
$smarty->template_dir = dirname(__FILE__) . '/smarty/templates/templates';
$smarty->compile_dir =  dirname(__FILE__) . '/smarty/templates/templates_c';
$smarty->cache_dir =    dirname(__FILE__) . '/smarty/templates/cache';
$smarty->config_dir =   dirname(__FILE__) . '/smarty/templates/configs';

/* Assign Global Smarty Variables */
$smarty->assign('google_account',   GOOGLE_ACCOUNT);
$smarty->assign('FLOT_ENABLED',     FLOT_ENABLED);
$smarty->assign('GMAP_KEY',         GMAP_KEY);

?>