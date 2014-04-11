<?php
class Vector {
  // Treat arrays as vectors, perform vector operations on them.

  // Square root of the sum of the square
  static public function magnitude($v) {
    $sum = 0;
    for($i = 0, $size = count($v); $i < $size; $i++) {
      $sum += pow($v[$i],2);
    }
    return sqrt($sum);
  }

  static public function dotProduct($v1, $v2) {
    if(count($v1) == count($v2)) {
      $sum = 0;
      for($i = 0, $size = count($v1); $i < $size; $i++) {
        $sum += $v1[$i] * $v2[$i];
      }
      return $sum;
    }else{
      die("Vectors must be the same length.");
    }
  }

  static public function crossProduct($v1, $v2) {
    if(count($v1) == count($v2)) {
      $pos = 0;
      $neg = 0;

      for($i = 0, $size = count($v1); $i < $size; $i++) {
        $i2 = ($i +1) % $size;
        $pos += $v1[$i] * $v2[$i2];
        $neg += $v2[$i] * $v1[$i2];
      }
      return $pos - $neg;
    }else{
      die("Vectors must be the same length.");
    }

  }

  static public function angleBetween($v1, $v2) {
    if(count($v1) == count($v2)) {
      return acos(Vector::dotProduct($v1,$v2) / (Vector::magnitude($v1) * Vector::magnitude($v2)));
    }else{
      die("Vectors must be the same length.");
    }
  }

}

function test() {
  $v = new Vector();
  $a = array(1,2,3);
  $a2 = array(4,5,6);
  echo($v->magnitude($a)."\n");
  echo($v->crossProduct($a,$a2)."\n");
  echo($v->angleBetween($a,$a2)."\n");
  echo(Vector::angleBetween(array(1,2,3),array(1,2,3))."\n");
  echo(Vector::angleBetween(array(1,2,3),array(0,2,3))."\n");
}
?>
