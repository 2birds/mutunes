<?php
session_name('contributorCheck');
session_start();
/* POST/REDIRECT/GET
 * Prevents the re-sent post-data problem.
 */
require($_SERVER["DOCUMENT_ROOT"]."/mutunes/ga-utilities/GenerationFunctions.php");


$result = query('SELECT COUNT(id) FROM `melodies`;');
$row = mysqli_fetch_array($result);
//echo($row);

// Business logic.
if(isset($_POST['choice']) && $_POST['choice'] != '') {
  $_SESSION['isContributor'] = true;
  $_SESSION['speedcache'] = $_POST['speedcache'];

  $battleResults = explode("-",$_POST['choice']);

  // If user has contributed previously..
  if(isset($_COOKIE['email'])) {
    $q = "UPDATE contributors ".
      "SET contributions = contributions + 1 ".
      "WHERE `email` = '".$_COOKIE['email']."';";
    query($q);
  }else{ // New user
    // Record number of contributions before they register.
    if(isset($_SESSION['contributions'])) {
      $_SESSION['contributions'] += 1;
    }else{
      $_SESSION['contributions'] = 1;
    }
  }
    

  //if(isset($_POST['musician'])){
    $musician = $_POST['musician'];
    $_SESSION['musician'] = $_POST['musician'];
    //}else{
    //$musician = false;
    //}

    // Ensure that two melodies are still in population. Deals with
    // possibility that someone leaves the page open for too long
    // before making a comparison.

    /*
    $q = "SELECT `inPopulation` FROM `melodies` WHERE `id` in ($battleResults[0],$battleResults[1]);"; 
    $res = query($q);
    $melodiesStillInPopulation = true;
    while($row = mysqli_fetch_row($res)) {
      if($row[0] == 0) {
	$melodiesStillInPopulation = false;
      }
    }
      
    if($melodiesStillInPopulation == true) {
    */
      query("START TRANSACTION"); // DB Lock
      $q = "INSERT INTO `battles` (`winnerId`, `loserId`, `musician`,`time`) ".
	"VALUES ($battleResults[0], $battleResults[1], $musician, ".time().");";
      exec("echo '$q' >> testlog.txt");
      query($q);
      $q = "UPDATE `melodies` ".
	"SET `wins` = `wins` + 1, ".
	"`totalWins` = `totalWins` + 1 ".
	"WHERE `id` = '$battleResults[0]'".
	"AND `wins` is not null ".
	"AND `totalWins` is not null;";
      query($q);
      exec("echo '$q' >> testlog.txt");
      $q = "UPDATE `melodies` ".
	"SET `defeats` = `defeats` + 1, ".
	"`totalDefeats` = `totalDefeats` + 1 ".
	"WHERE `id` = '$battleResults[1]'".
	"AND `defeats` is not null ".
	"AND `totalDefeats` is not null;";
      query($q);
      exec("echo '$q' >> testlog.txt");
      query("COMMIT");

      checkGenerationThreshold();
      // }

  // redirect back to compare.php
  header("Location: ../compare.php");
  exit();
}

?>
