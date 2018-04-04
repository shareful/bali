<?php

/**
 * Created by JetBrains PhpStorm.
 * User: eclark
 * Date: 5/7/13
 * Time: 9:50 PM
 * To change this template use File | Settings | File Templates.
 */
define('CONFIG_PATH', '../../config/');

class MY_Zend_Model {

  public $db;
  public $CI;

  public function __construct($env = 'production', $profiler = false) {

    $this->CI = & get_instance();

    $this->CI->my_zend->load('Loader');

    $db_file = $this->CI->my_zend->get_config('database.xml');

    Zend_Loader::loadClass('Zend_Config_Xml');
    Zend_Loader::loadClass('Zend_db');

    $db_config = new Zend_Config_Xml($db_file, $env);

    $this->db = Zend_Db::factory($db_config->database->adapter, array(
                'host' => $db_config->database->params->host,
                'username' => $db_config->database->params->username,
                'password' => $db_config->database->params->password,
                'dbname' => $db_config->database->params->dbname,
    ));

    $this->db->getConnection();

    $this->db->getProfiler()->setEnabled($profiler);

    return $this;
  }

}