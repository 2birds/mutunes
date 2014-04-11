<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/mutunes/ga-utilities/MelodyGeneratorWithDuration.class.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/mutunes/ga-utilities/Melody.class.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/mutunes/php_utils/db_query.php");
function generateInitialPopulation(){
  $q = "SELECT popsize FROM generalProperties;";
  $res = query($q);
  $row = mysqli_fetch_array($res);


  echo("Popsize = $row[0]\n");
  
  $mg = new MelodyGenerator(true);
  for($i = 0; $i < $row[0]; $i++) {
    $mel = new Melody();
    $mel->setId($i+1);
    $mel->setPosition($i+1);
    $newMelody = $mg -> generateRandomMelody();
    //echo($newMelody);
    $mel->setMelody($mg->generateRandomMelody());
    
    //MelodyGenerator::writeMelody(MelodyGenerator::getNotes($mel->getMelody()),"$mel->getId()");
    $mel->setGeneration(0);
    $mel->inPopulation(true);
    // Other values are set by default.
    $mel->writeToDB();
    
  }
}
?>
