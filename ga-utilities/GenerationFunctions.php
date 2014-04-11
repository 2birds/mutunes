<?php
// Creates new generations
require_once($_SERVER["DOCUMENT_ROOT"]."/mutunes/ga-utilities/Melody.class.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/mutunes/ga-utilities/GeneticFunctionsWithDuration.class.php");


function checkGenerationThreshold() {
  // Gamma allows us to adjust the rate of evolution. Lower gamma => faster evolution
  $q = "SELECT `gamma`,`popsize` FROM `generalProperties` WHERE `name` = 'properties';";
  $res = query($q);
  $row = mysqli_fetch_row($res);
  $gamma = $row[0];
  $psize = $row[1];

  // n*log(n) is how many comparisons it should take to provide
  // enough information to accurately rank melodies.
  if(rand(1,round($psize * log($gamma * $psize) * $gamma)) == 1) {
    // Generate next generation.
    $q = "SELECT `percentToReplace` FROM `generalProperties` WHERE `name` = 'properties';";
    $res = query($q);
    $row = mysqli_fetch_row($res);
    $percentToReplace = $row[0];

    $pairsToReplace = round($psize / 100.0 * $percentToReplace); 
    if($pairsToReplace % 2 == 1) {
      $pairsToReplace--;
    }
    $pairsToReplace /= 2;

    nextGeneration($pairsToReplace); 
  }
}


/*
function checkGenerationThreshold() {
  $q = "SELECT threshold FROM generalProperties WHERE name='properties';";
  $res = query($q);
  $row = mysqli_fetch_array($res);
  $thresh = $row[0];

  exec("echo '$thresh' >> testlog.txt");

  $q = "SELECT max(wins - defeats) FROM melodies WHERE inPopulation = 1;";
  $res = query($q);
  $row = mysqli_fetch_array($res);
  $max = $row[0];
  
  $q = "SELECT min(wins - defeats) FROM melodies WHERE inPopulation = 1;";
  $res = query($q);
  $row = mysqli_fetch_array($res);
  $min = $row[0];

  if($max - $min > $thresh) {
    nextGeneration();
  }
  }*/


/*
function nextGeneration($numberOfPairsToCrossover = 1){
  echo("<h0>Making next generation..</h1>");
  // Get highest ranking
  $q = "SELECT id, melodyString FROM melodies ORDER BY (wins - defeats) WHERE inPopulation = 1 DESC LIMIT ".($numberOfPairsToCrossover * 2).";";
  $res = query($q);
  $parentId = array();
  $mels = array();

  while($row = mysqli_fetch_array($res)) {
    $parentId[] = $row['id'];
    $mels[] = $row['melodyString'];
  }
  //echo("<p>$parentId[0], $parentId[1]</p>");
  //echo("<p>$mels[0], $mels[1]</p>");

  // Reset scores for parent melodies.
  $q = "UPDATE melodies ".
    "SET wins=0, defeats=0 ".
    "WHERE id IN (".$parentId[0].",".$parentId[1].");";

  query($q);

  $mels[0] = Melody::melodyStringToArray($mels[0]);
  $mels[1] = Melody::melodyStringToArray($mels[1]);

  $offspring = GeneticFunctions::crossoverWithDuration($mels[0], $mels[1]);

  foreach($offspring[0] as $o) {
    echo($o."\n");
  }
  
  $mutatee = rand(0,1);
  $offspring[$mutatee] = GeneticFunctions::mutateWithDuration($offspring[$mutatee]);

  $mel1 = new Melody();
  $mel2 = new Melody();

  $mel1->setMelody($offspring[0]);
  $mel2->setMelody($offspring[1]);

  // Get info about melodies to be replaced.
  $q = "SELECT id, position FROM melodies WHERE inPopulation=1 ORDER BY (wins - defeats) LIMIT 2;";
  $res = query($q);
  $what = array();
  $where = array();

  while($row = mysqli_fetch_row($res)) {
    echo($row[0].", ".$row[1]."\n");
    $what[] = $row[0];
    $where[] = $row[1];
  }
  //echo("<p>$what[0], $what[1]</p>");
    //echo("<p>$where[0], $where[1]</p>");

  // The order of the parent listing corresponds to the half
  // received, ie., $parent[0] is 1st half, etc.
  //echo($what[0].", ".$what[1]);
  $mel1->setParents($parentId[0],$parentId[1]);
  $mel2->setParents($parentId[1],$parentId[0]);

  $mel1->setPosition($where[0]);
  $mel2->setPosition($where[1]);

  // Remove old dead melodies from population.
  $q = "UPDATE melodies ".
    "SET inPopulation = 0 ".
    "WHERE inPopulation=1 ".
    "AND id IN (".$what[0].",".$what[1].");";
  query($q);

  $q = "SELECT max(id), max(introducedAtGeneration) ".
    "FROM melodies;";
  $res = query($q);
  $row = mysqli_fetch_array($res);

  $mel1->setId($row[0] + 1);
  $mel2->setId($row[0] + 2);

  $mel1->setGeneration($row[1] + 1);
  $mel2->setGeneration($row[1] + 1);

  $mel1->writeToDB();
  $mel2->writeToDB();
}
*/

function nextGeneration($numberOfPairsToCrossover = 1){
  echo("<h1>Making next generation..</h1>");
  // Get highest ranking
  $q = "SELECT id, melodyString FROM melodies WHERE inPopulation = 1 ORDER BY (totalWins - totalDefeats) DESC LIMIT ".($numberOfPairsToCrossover * 2).";";
  $res = query($q);
  $parentId = array();
  $mels = array();

  while($row = mysqli_fetch_array($res)) {
    $parentId[] = $row['id'];
    $mels[] = $row['melodyString'];
  }

  // Reset scores for parent melodies. This may not be useful info soon
  foreach($parentId as $pid) {
    $q = "UPDATE melodies ".
      "SET wins=0, defeats=0 ".
      "WHERE id = $pid;";

    query($q);
  }

  // Get info to accurately ID new melodies and other info.
  $q = "SELECT max(id), max(introducedAtGeneration) ".
    "FROM melodies;";
  $res = query($q);
  $row = mysqli_fetch_array($res);

  $newID = $row[0];
  $generation = ++$row[1];

  // Get info about melodies to be replaced.
  $q = "SELECT id, position FROM melodies WHERE inPopulation=1 ORDER BY (totalWins - totalDefeats) LIMIT ".($numberOfPairsToCrossover * 2).";";
  $res = query($q);
  $what = array();
  $where = array(); // Where is important for replacing melodies in population

  while($row = mysqli_fetch_row($res)) {
    echo($row[0].", ".$row[1]."\n");
    $what[] = $row[0];
    $where[] = $row[1];
  }

  // Remove n worst melodies from population.
  foreach($what as $wha) {
  $q = "UPDATE melodies ".
    "SET inPopulation = 0, ".
    "removedAtGeneration = ".($generation - 1)." ".
    "WHERE inPopulation=1 ".
    "AND id = '$wha';";
  query($q);
  }
  echo("Debug");
  // Pure overhead. Convert melody representation and generate order
  // for crossover
  $order = array();
  for($i = 0; $i < count($mels); $i++) {
    $mels[$i] = Melody::melodyStringToArray($mels[$i]);
    $order[] = $i;
  }
  echo("Debug");

  shuffle($order); // Random order for crossover


  echo("Debug");

  // Have to do this crossover in pairs
  for($i = 0; $i < count($order) / 2; $i++) {
  echo("Debug");
    // Get two random melodies from the top n.
    $n1 = $order[2 * $i];
    $n2 = $order[(2 * $i) + 1];
    $m1 = $mels[$n1];
    $m2 = $mels[$n2];

    // Breed 'em. Breed 'em good.
    $offspring = GeneticFunctions::crossoverWithDuration($m1, $m2);
  echo("Debug");

  // Fuse repeats with some probability.
  if(rand(1,2) == 1) {
    $offspring[0] = mergeSame($offspring[0]);
  }

  if(rand(1,2) == 1) {
    $offspring[1] = mergeSame($offspring[1]);
  }

    // Mix one up a bit
    $mutatee = rand(0,1);
    $offspring[$mutatee] = GeneticFunctions::mutateWithDuration($offspring[$mutatee]);
  echo("Debug");

    $mel1 = new Melody();
    $mel2 = new Melody();

    $mel1->setMelody($offspring[0]);
    $mel2->setMelody($offspring[1]);

    // The order of the parent listing corresponds to the half
    // received, ie., $parent[0] is 1st half, etc.
    //echo($what[0].", ".$what[1]);
    $mel1->setParents($parentId[$n1],$parentId[$n2]);
    $mel2->setParents($parentId[$n2],$parentId[$n1]);

    // Insert new melodies into the population in the position of the old ones.
    $mel1->setPosition(array_pop($where));
    $mel2->setPosition(array_pop($where));

  echo("Debug");
    // Increment ID for every new melody
    $mel1->setId(++$newID);
    $mel2->setId(++$newID);
  echo("Debug");
  echo($generation);
    // Mark them as the same litter.
    $mel1->setGeneration($generation);
    $mel2->setGeneration($generation);

  echo("Debug");
    $mel1->writeToDB();
  echo("Debug");
    $mel2->writeToDB();
  echo("Debug");
  }
}

function mergeSame($m1) {
  $i = 1;
  $zeros = array(); // Notes we want to fuse into previous

  while($i < count($m1) - 1) { // All but start.
    if(explode(":",$m1[$i])[0] == "0") { // Look for zeroes.
      $zeros[] = $i;
    }
    $i++;
  }

  if(count($zeros) > 0){ // If there are any zeroes..
    $r = rand(0,count($zeros) - 1); // Pick a zero.

    $m2 = array_slice($m1, 0, $zeros[$r] - 1); // Take the stuff before it..

    // Add the zero's length to the previous note.
    $fused = explode(":",$m1[$zeros[$r] - 1]);
    $fused2 = explode(":",$m1[$zeros[$r]]);

    // Last array item
    $end = count($fused) - 1;

    $fused[$end] += $fused2[$end];

    $fused = implode(":",$fused);

    // Add new, longer note
    $m2[] = $fused;

    // Re-attach rest of melody.
    $m3 = array_merge($m2, array_slice($m1, $zeros[$r] + 1));
    return($m3);
  }else{ // No zeroes, carry on.
    return($m1);
  }
}

?>
