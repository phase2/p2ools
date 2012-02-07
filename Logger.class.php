<?php

/**
 * A logging abstraction that kicks to watchdog conditionally, based on 
 * severity.
 */
class Logger {

  public static $ERROR = 0;
  public static $WARN = 1;
  public static $INFO = 2;
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
   * @param $debug Is this a debug-level message?
   */
  private function log($msg, $level) {
    if($level <= ($this->VERBOSITY)) {
        watchdog($this->module_name, $msg);
    }
  }

  public function info($msg) {
    $this->log($msg, $this->INFO);
  }
                                  
  public function warn($msg) {
    $this->log($msg, $this->WARN);
  }
                                   
  public function error($msg) {
    $this->log($msg, $this->ERROR);
  }
            
}
