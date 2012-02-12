<?php

/**
 * A logging abstraction that kicks to an arbitrary logger based on severity.
 */
class Logger {

  const ERROR = 0;
  const WARN = 1;
  const INFO = 2;
  const DEBUG = 3;
  public $VERBOSITY = 2;

  /**
   * The name of the module that this Logger belongs to.
   */
  public $module_name;

  /**
   * The log function to wrap. Must accept the module name as the first argument
   * and the log message as the second.
   */
  public $log_func_name;
 
  function __construct($module_name, $verbosity_level=null, $log_func_name='watchdog') {
    $this->module_name = $module_name;
    $this->log_func_name = $log_func_name;

    if (!is_null($verbosity_level)) {
      assert ($verbosity_level <= 2) && ($verbosity_level >= 0);
      $this->VERBOSITY = $verbosity_level;
    }
  }

  /**
   * Internal function for calling the logger specified at construction.
   */
  private function call_logger($args_arr) {
    return call_user_func_array($this->log_func_name, $args_arr);
  }

  /**
   * Internal logging abstraction. Kick to watchdog.
   *
   * @param $msg Message to be logged.
   * @param $level Is this a debug-level message?
   * @param $var The var to `print_r`; optional.
   */
  private function log($msg, $level, $var=null) {
    if($level <= ($this->VERBOSITY)) {
      $bt = debug_backtrace();
      $file = array_pop(explode('/', $bt[1]['file']));
      $line = $bt[1]['line'];

      $msg = "${file}:${line} ${msg}";

      if (!is_null($var)) {
        $msg .= ": " . print_r($var, true);
      }

      $args = array($this->module_name, $msg);
      $this->call_logger($args);
    }
  }

  public function debug($msg, $var=null) {
    $this->log($msg, self::DEBUG, $var);
  }
                                  
  public function info($msg, $var=null) {
    $this->log($msg, self::INFO, $var);
  }
                                  
  public function warn($msg, $var=null) {
    $this->log($msg, self::WARN, $var);
  }
                                   
  public function error($msg, $var=null) {
    $this->log($msg, self::ERROR, $var);
  }
            
}
