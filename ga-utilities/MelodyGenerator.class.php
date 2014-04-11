<?php
require($_SERVER["DOCUMENT_ROOT"]."/mutunes/ga-utilities/midi_class_v178/classes/midi.class.php");

class MelodyGenerator {
  
  public function generateCumulativeGaussianDistribution($range, $sigma=7) {
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

  public function getNext($dist){
    $next = (float) rand() / (float) getrandmax();
    $i = 0;
    while($next > $dist[$i]) {
      $i++;
    }
    if(rand(1,2) == 1) {
      $i *= -1;
    }
    return $i;
  }

  public function generateRandomMelody($length = 16, $range=12, $disjunctness = 5){ // Rande in 8tves
  $melody = array();
  //$dist = MelodyGenerator->generateCumulativeGaussianDistribution($range,$disjunctness);
  $dist = $this->generateCumulativeGaussianDistribution($range,$disjunctness);
  
  $melody[] = 0; // First offset is always zero.
  
  $sum = 0;
  for($i = 1; $i < $length; $i++) {
    $next = $this->getNext($dist);
    while(abs($sum + $next) > $range) {
      $next = $this->getNext($dist);
    }

    $sum += $next;
    $melody[] = $next;
  }
  return $melody;
  }

  public function getNotes($offsets, $start_note=60) {
    $notes = array();

    $note = $start_note;
    for($i = 0, $size = count($offsets); $i < $size; $i++) {
      $note += $offsets[$i];
      $notes[] = $note;
    }

    return $notes;
  }

  public function writeMelody($notes, $fname="test") {
    $midi = new Midi(); // No "$" on the class.
    $midi->open(480);
    $midi->setBpm(35);
    $crotchet = 120;

    $tn = $midi->newTrack() - 1;

    for($n = 0, $size = count($notes); $n < $size; $n++) {
      // Calculate times
      $t = $n * $crotchet; // Time
      $to = $t + $crotchet; // Time offset

      // Generate control strings..
      $onMsg = "$t On ch=1 n=$notes[$n] v=90";
      $offMsg = "$to Off ch=1 n=$notes[$n] v=90";

      //echo($onMsg."\n");
      //echo($offMsg."\n");
      // Add on/off for a single note.
      $midi->addMsg($tn,$onMsg);
      $midi->addMsg($tn,$offMsg);
    }

    // Finish track
    $midi->addMsg($tn, "$to Meta TrkEnd");

    // Set directory
    $dir = 'music';

    // http://stackoverflow.com/questions/2900690/how-do-i-give-php-write-access-to-a-directory
    if(!file_exists($dir)) {
      mkdir ($dir, 0777);
    }
    $midi->saveMidFile($dir."/".$fname.".mid",0666);

    //  Create wav and mp3 of track.
    $path = getcwd()."/music/".$fname;
    //echo($path);
    exec("timidity -Ow ".$path.".mid ; lame -V2 ".$path.".wav ".$path.".mp3");

  }
}

?>
