<?php

/**
 * Includes utilities for interacting with taxonomy terms in a sane way.
 */
class Tax {

  /**
   * Logger
   */
  private $lg;

  function __construct() {
    $this->lg = new Logger("p2ools Tax", Logger::INFO);
  }

  public function get_term($id, $vocab_id=null) {
    $tid = $this->get_tid($id, $vocab_id);
    return taxonomy_term_load($tid);
  }

  /**
   * Retrieve a single term id by name, optionally specifying vocab.
   *
   * @param $id Name or tid of the term
   * @param $vocab Machine name or vid of the vocab
   *
   * @return a single term, else false
   */
  public function get_tid($id, $vocab_id=null) {
    $tid = false;
    $this->lg->debug("Retrieving term with id ${id} from vocab ${vocab_id}.");

    $query = new EntityFieldQuery;
    $query->entityCondition('entity_type', 'taxonomy_term');
    $query->propertyCondition((is_numeric($id)) ? 'tid' : 'name', $id);

    if (!is_null($vocab_id)) {
      $query->propertyCondition('vid', $this->resolve_vid($vocab_id));
    }

    $result = $query->execute(); 
    if ($result) {
      $tid = current($result['taxonomy_term'])->tid;
    }

    return $tid;
  }


  /**
   * Get a vocabulary's vid based on vid or machine name.
   */
  private function resolve_vid($id) {
    if (!is_numeric($id)) {
      $vocab = taxonomy_vocabulary_machine_name_load($id);

      if (!$vocab) {
        $this->lg->error("Couldn't find vocab of machine name '${id}'.");
        return false;
      }

      return $vocab->vid;
    }

    return $id;
  }

}
