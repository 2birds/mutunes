<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/mutunes/php_utils/db_query.php");

class Melody {
  // Unique id
  private $id;
  private $position;

  // Array of offsets
  private $melody;
  
  // Stats since initiation or last parenting, whichever is most recent
  private $wins = 0;
  private $defeats = 0;

  // Stats since instantiation.
  private $totalWins = 0;
  private $totalDefeats = 0;

  // Initial pop gets a zero. First set of children: 1, etc.,..
  private $introducedAtGeneration = 0;

  // Boolean. Important. Obvious what it's for, I would hope.
  private $inPopulation=true;

  // Array containing id of parents (Two at most!)
  private $parents=array(0,0); // 0 means God (ie. me) created it.

  // Setters
  
  function setId($id) {
    if(!isset($this->id) && gettype($id) == 'integer') {
      $this->id = $id;
    }else{
      die("Either ID is set or ID is not of type 'integer'.");
    }
  }

  function setPosition($pos) {
      $this->position = $pos;
  }

  function setMelody($mel) {
    $type = gettype($mel);

    if($type == 'string') {
      $this->melody = $this->melodyStringToArray($mel);
    }else if($type == 'array'){
      $this->melody = $mel;
    }else{
      die("Melody must be of type 'string' or 'array'.");
    }
  }

  function resetStats() {
    $this->wins = 0;
    $this->defeats = 0;
  }

  function addWin() {
    $this->wins += 1;
    $this->totalWins += 1;
  }

  function addDefeat() {
    $this->defeats += 1;
    $this->totalDefeats += 1;
  }

  function setGeneration($gen) {
    if(gettype($gen) == 'integer') {
      $this->introducedAtGeneration = $gen;
    }else{
      die("Error!");
    }
  }

  // Try to make p1 the higher-ranking one.
  function setParents($p1,$p2) {
    $this->parents = array($p1,$p2);
  }

  function inPopulation($p) {
    if($p == null) {
      return $this->inPopulation;
    }else if(gettype($p) == 'boolean') {
      $this->inPopulation = $p;
    }
  }

  // Getters
  function getId() {
    return $this->id;
  }

  function getPosition() {
    return $this->position;
  }

  function getMelody() {
    return $this->melody;
  }

  function getWins() {
    return $this->wins;
  }

  function getTotalWins() {
    return $this->totalWins;
  }

  function getDefeats() {
    return $this->defeats;
  }

  function getTotalDefeats() {
    return $this->totalDefeats;
  }

  function getGeneration() {
    return $this->introducedAtGeneration;
  }

  function getParents() {
    return $this->parents;
  }

  function writeToDB() {
    $q = "INSERT INTO melodies (id, melodyString, wins, defeats, totalWins, totalDefeats, introducedAtGeneration, parentAId, parentBId, inPopulation, position) ".
      "VALUES (".$this->id.",".
      "'".Melody::melodyArrayToString($this->melody)."',".
      $this->wins.",".
      $this->defeats.",".
      $this->totalWins.",".
      $this->totalDefeats.",".
      $this->introducedAtGeneration.",".
      $this->parents[0].",".
      $this->parents[1].",".
      "true,".
      $this->position.");"; // Initial position is equal to ID, because why not?
    //echo($q);
    query($q);
  }

  // Static functions.
  static function melodyArrayToString($mArray) {
    return implode(",",$mArray);
  }
  
  static function melodyStringToArray($mString) {
    return explode(",",$mString);
  }

  //static
}

