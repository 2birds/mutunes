<?php
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
}
?>
