<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/mutunes/ga-utilities/MelodyGeneratorWithDuration.class.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/mutunes/ga-utilities/Melody.class.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/mutunes/ga-utilities/populationGenerator.php");

function go(){
$result = query('SELECT COUNT(id) FROM melodies;');
$row = mysqli_fetch_array($result);
//echo($row);

// Check for existing population, ie., is this the first time?
if($row[0] == 0) {
  //echo("Melody count: $row[0]\n");
  echo("Generating new population..\n");
  generateInitialPopulation();
}else{
  //echo("Melody count: $row[0]\n");

  // Get next melody, random competitor
  query("START TRANSACTION"); // Allows effective lock, prevents race conditions on concurrent access.
  $q = "SELECT currentPosition, popsize FROM generalProperties;";
  $res = query($q);
  $q = "UPDATE generalProperties SET currentPosition = currentPosition + 1 WHERE name = 'properties';";
  query($q);
  query("COMMIT");
  $row = mysqli_fetch_array($res);
  
  /* Current melody is also a measure of the number of operations
   * and so needs a mod operator to get the actual position
   * in the population.
   */
  if($row[0] % $row[1] == 0) {
    $m1 = $row[1];
  }else{
    $m1 = $row[0] % $row[1];
  }
  $m2 = rand(1,$row[1]);
  while($m1 == $m2) { //  Different random number.
    $m2 = rand(1,$row[1]);
  }

  // Get ids of these two random melodies.

  //$mels = array($m1, $m2);
  $q = "SELECT id FROM melodies WHERE inPopulation=1 AND position IN ($m1,$m2) LIMIT 0,2;";
  //echo($q);
  $res = query($q);
  //echo(gettype($res)."\n");
  $melIDs = array();
  while($fn = mysqli_fetch_array($res)) {
    // These are passed to "compare.php" to load the appropriate melodies. 
    $melIDs[] = $fn[0];
    // If the sound file doesn't exist, make it exists!
    if(!file_exists("music/".$fn[0].".wav")) {
      $q = "SELECT melodyString FROM melodies WHERE id=$fn[0];";
      //echo($q."\n");
      // Replace $res with $m here as I was overwriting something I needed
      $m = query($q) or die();
      $melString = mysqli_fetch_row($m);
      //echo($melString);
      $melString = $melString[0];
      //echo($melString."\n");
      $melString =
	Melody::melodyStringToArray($melString);
      //echo($melString."\n");
      $mg = new MelodyGenerator(true);
      $mg->writeMelody($mg->getNotes($melString),"".$fn[0]);

    } 
  } 
  $melIDs[] = "The query returned nothing.";
      return $melIDs;
}
}

/*
function makePlayer($mel,$against) {
  echo("<h4 onclick='selectThisSiblings(event)'>Melody ".$mel."</h4>".
       "<audio controls>\n".
       "<source src='music/".$mel.".mp3' type='audio/mp3' />\n".
       "<source src='music/".$mel.".wav' type='audio/wav' />\n".
       "</audio>".
       "<input type='radio' name='choice' value='".$mel."-".$against."' />");
}
*/

function makePlayer($mel,$against) {
  echo("<h4 onclick='selectThisSiblings(event)'>Melody player</h4>".
       "<audio controls>\n".
       "<source src='music/".$mel.".mp3' type='audio/mp3' />\n".
       "<source src='music/".$mel.".wav' type='audio/wav' />\n".
       "</audio>".
       "<input type='radio' name='choice' value='".$mel."-".$against."' />");
}

?>
