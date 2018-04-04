<?php

if (!defined('BASEPATH')) {
  exit('No direct script access allowed');
}

/**
 * Zend Framework Loader
 *
 * Put the 'Zend' folder (unpacked from the Zend Framework package, under 'Library')
 * in CI installation's 'application/libraries' folder
 * You can put it elsewhere but remember to alter the script accordingly
 *
 * Usage:
 *   1) $this->load->library('zend', 'Zend/Package/Name');
 *   or
 *   2) $this->load->library('zend');
 *      then $this->zend->load('Zend/Package/Name');
 *
 * * the second usage is useful for autoloading the Zend Framework library
 * * Zend/Package/Name does not need the '.php' at the end
 */
define('ZEND_LIB_1', '1.12/Zend/');
define('ZEND_LIB_2', 'Zend/');

define('ZF_VERSION', '1.12');

define('ZEND_LIB_VERSION', '0.0.2');

define('SPARKS_DIR', '../sparks/');

define('SPARK_NAME', 'zend-lib');

define('PATH_SLASH', '/');

class MY_Zend {

  /**
   * Constructor
   *
   * @param	string $class class name
   */
  function __construct($class = NULL) {
    $zend_lib = realpath(dirname(__FILE__));

    // include path for Zend Framework
    // alter it accordingly if you have put the 'Zend' folder elsewhere
    // ini_set('include_path',
    //   ini_get('include_path') . PATH_SEPARATOR . $zend_lib);
    ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR . $zend_lib . PATH_SLASH . ZF_VERSION);

    // BASEPATH . SPARKS_DIR . SPARK_NAME . PATH_SLASH . ZEND_LIB_VERSION . '/libraries'
    if ($class) {
      require_once (string) $zend_lib . PATH_SLASH . ZEND_LIB_1 . $class . EXT;
      log_message('debug', "Zend Class $class Loaded");
    } else {
      log_message('debug', "Zend Class Initialized");
    }
  }

  /**
   * Zend Class Loader
   *
   * @param	string $class class name
   */
  function load($class) {
    $zend_lib = realpath(dirname(__FILE__));
    require_once (string) $zend_lib . PATH_SLASH . ZEND_LIB_1 . $class . EXT;
    log_message('debug', "Zend Class $class Loaded");
  }

  function get_config($config) {
    return APPPATH . SPARKS_DIR . SPARK_NAME . PATH_SLASH . ZEND_LIB_VERSION . '/config/' . $config;
  }

  /**
   * @param $class
   * Updated for Zend Framework 2.2.0
   */
  function ZF_loader() {

    $zend_lib = realpath(dirname(__FILE__));
    require_once (string) $zend_lib . PATH_SLASH . ZEND_LIB_2 . '/Loader/ClassMapAutoloader.php';
    $loader = new Zend\Loader\ClassMapAutoloader();
    $loader->registerAutoloadMap($zend_lib . PATH_SLASH . ZEND_LIB_2 . 'autoload_classmap.php');
    $loader->register();
  }

}

?>
