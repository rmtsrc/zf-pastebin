<?php
/**
 * Zend Framework Pastebin Application
 *
 * @author Seb Flippence
 * @see http://seb.flippence.net
 * @version v0.5
 * @license GNU General Public License - 2009
 */

// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));    

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
    get_include_path(),
)));
$configFile = APPLICATION_PATH . '/configs/application.ini';

/** Zend_Application */
require_once 'Zend/Loader/Autoloader.php';
$loader = Zend_Loader_Autoloader::getInstance();
//$loader->registerNamespace('Pastebin_');

// Load config
$config = new Zend_Config_Ini($configFile, APPLICATION_ENV);
Zend_Registry::set('config', $config);

// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV, 
    $configFile
);
$application->bootstrap()
            ->run();