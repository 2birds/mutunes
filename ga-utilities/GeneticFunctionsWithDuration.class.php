<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/mutunes/ga-utilities/Gaussian.class.php");

class GeneticFunctions {
  // Default crossover point is in the middle.
  public static function crossover($a, $b, $point= -1) {
    if($point == -1) {
      $point = count($a) / 2;
    }
// Must be the same type and size.
    if(gettype($a) == 'array' && 
       gettype($b) == 'array' &&
       count($a) == count($b)) {
      $offspring1 = array();
      $offspring2 = array();

      for($i = 0, $size = count($a); $i < $size; $i++) {
	if($i < $point) { // 1st half..
	  $offspring1[] = $a[$i];
	  $offspring2[] = $b[$i];
	}else{ // 2nd half..
	  $offspring1[] = $b[$i];
	  $offspring2[] = $a[$i];
	}
      }

      return array($offspring1, $offspring2);

    }else{
      die("Arguments must be 2 equally sized arrays.");
    }
  }

  public static function crossoverWithDuration($a, $b, $gaussianDist = false) {
// Must be the same type and size.
    if(gettype($a) == 'array' && 
       gettype($b) == 'array') {

      $total = 0;
      for($i = 0, $size = count($a); $i < $size; $i++) {
	$e = explode(":",$a[$i]);
	$total += $e[1]; 
      }

      if($gaussianDist) {
      $range = $total / 2;
      $dist = Gaussian::generateCumulativeGaussianDistribution($range);
      $p = (float) rand() / (float) getrandmax();

      $crossoverPoint = 0;

      exec("echo $p > testlog.txt");
      while($p > $dist[$crossoverPoint]) {
      exec("echo $dist[$crossoverPoint] >> testlog.txt");

	$crossoverPoint++;
      }

      if(rand(1,2) == 1) {
	$crossoverPoint *= -1;
      }
      exec("echo $crossoverPoint >> testlog.txt");

      $crossoverPoint += $total / 2;
      // What did I just do? Selected a temporal crossover point
      // using a random number and a gaussian distribution. Short
      // answer - it's most likely to cross over in the middle,
      // with decreasing likelyhood further out.
      }else{
	// Uniform distribution. Default.
	$crossoverPoint = rand(1,$total - 1);
      }

      // Separate melody A.
      $a1 = array();
      $a2 = array();

      $aCumSum = 0;
      foreach($a as $a_elem) {
	$parts = explode(":",$a_elem);
	if($aCumSum < $crossoverPoint && $crossoverPoint < ($aCumSum + $parts[1])) {
	  $diff = $crossoverPoint - $aCumSum;
	  $a1[] = $parts[0].":".$diff;
	  $a2[] = $parts[0].":".($parts[1] - $diff);
	}else if($aCumSum < $crossoverPoint){
	  $a1[] = $a_elem;
	}else{
	  $a2[] = $a_elem;
	}
	$aCumSum += $parts[1];
      }

      // Separate melody B.
      $b1 = array();
      $b2 = array();

      $bCumSum = 0;
      foreach($b as $b_elem) {
	$parts = explode(":",$b_elem);
	if($bCumSum < $crossoverPoint && $crossoverPoint < ($bCumSum + $parts[1])) {
	  $diff = $crossoverPoint - $bCumSum;
	  $b1[] = $parts[0].":".$diff;
	  $b2[] = $parts[0].":".($parts[1] - $diff);
	}else if($bCumSum < $crossoverPoint){
	  $b1[] = $b_elem;
	}else{
	  $b2[] = $b_elem;
	}
	$bCumSum += $parts[1];
      }

      return array(array_merge($a1,$b2),
		   array_merge($b1,$a2));

    }else{
      die("Arguments must be 2 equally sized arrays.");
    }
  }

  // Mutate a random position by a random amount up or down.
  public static function mutate($genome, $amount = 1) {
    // Never mutate the 1st note as we are dealing in offsets.
    $m = rand(1,count($genome)-1);
    if(rand(1,2) == 1) {
      $amount *= -1;
    }

    $genome[$m] = $genome[$m] + $amount;
    return $genome;
  }

  public static function mutateWithDuration($genome, $amount = 1) {
    // Never mutate the 1st note as we are dealing in offsets.
    $m = rand(1,count($genome)-1);
    if(rand(1,2) == 1) {
      $amount *= -1;
    }

    $new = explode(":", $genome[$m]);
    $new[0] += $amount;
    $genome[$m] = implode(":",$new);
    return $genome;
  }
}
?>
