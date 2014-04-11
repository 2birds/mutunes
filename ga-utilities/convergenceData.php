<?php
require_once($_SERVER['DOCUMENT_ROOT']."/mutunes/php_utils/db_query.php");

function getConvergenceData() {
  $q = "SELECT melodyString FROM `melodies` WHERE inPopulation = 1;";
  $mels = query($q);

  $comparisons = 0;
  $avgCommonSubstringLength = 0;
  $longestCommonSubstring = 0;
  $melStrings = array();

  while($mel = mysqli_fetch_row($mels)) {
    $newMelString = $mel[0];
    
    foreach($melStrings as $melString) { // Compare new string to all previous.
      $commonSubstringLength = similar_text($newMelString, $melString);

      // Record maximum
      if($commonSubstringLength > $longestCommonSubstring) {
	$longestCommonSubstring = $commonSubstringLength;
      }

      // Record average
      $avgCommonSubstringLength += $commonSubstringLength;
      $comparisons++;
    }

    $melStrings[] = $newMelString;
    }

  return(array($longestCommonSubstring, $avgCommonSubstringLength / $comparisons));
}
?>