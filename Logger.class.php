<?php

/**
 * A logging abstraction that kicks to watchdog conditionally, based on 
 * severity.
 */
class Logger {

  const ERROR = 0;
  const WARN = 1;
  const INFO = 2;
  public $VERBOSITY = 1;
 
  function __construct($module_name, $verbosity_level=null) {
    $this->module_name = $module_name;

    if (!is_null($verbosity_level)) {
      assert ($verbosity_level <= 2) && ($verbosity_level >= 0);
      $this->VERBOSITY = $verbosity_level;
    }
  }

  /**
   * Internal logging abstraction. Kick to watchdog.
   *
   * @param $msg Message to be logged.
   * @param $level Is this a debug-level message?
   * @param $file Optional file name/path. use __FILE__
   * @param $line Optional line number (needs file). use __LINE__
   */
  private function log($msg, $level, $file=null, $line=null) {
    if($level <= ($this->VERBOSITY)) {
        if($file){ $msg .= ' '.$file; }
        if($line){ $msg .= ':'.$line; }
        watchdog($this->module_name, $msg);
    }
  }

  /**
   * Logging functions
   *
   * example usage:
   *  $log->warn('danger danger!');
   *  $log->info('debug message here', __FILE__, __LINE__);
   *  $log->info('backtrace! '.print_r(debug_backtrace(), true), __FILE__, __LINE__);
   *  
   */
  public function info($msg, $file=null, $line=null) {
    $this->log($msg, self::INFO, $file, $line);
  }
                                  
  public function warn($msg, $file=null, $line=null) {
    $this->log($msg, self::WARN, $file, $line);
  }
                                   
  public function error($msg, $file=null, $line=null) {
    $this->log($msg, self::ERROR, $file, $line);
  }
            
}
