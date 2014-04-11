<?php
class Gaussian{
  public static function generateCumulativeGaussianDistribution($range, $sigma=7) {
    $dist = array();
    $twoSigmaSquared = 2 * pow($sigma, 2);
    $csum = 0;
    for($i = 0; $i <= $range; $i++) { // Up to and including..
      $next = exp(-1 * (pow($i,2) / $twoSigmaSquared));
      $dist[] = $next;
      $csum += $next;
    }

    // Normalise to 1.
    for($i = 0; $i <= $range; $i++) { // Up to and including..
      $dist[$i] = $dist[$i] / $csum;
      if($i > 0) { // Cumulative part..
	$dist[$i] += $dist[$i - 1];
      }
    }

    return $dist;
  }
}
?>