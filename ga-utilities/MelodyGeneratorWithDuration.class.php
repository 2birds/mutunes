<?php
require($_SERVER["DOCUMENT_ROOT"]."/mutunes/ga-utilities/midi_class_v178/classes/midi.class.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/mutunes/ga-utilities/Gaussian.class.php");

class MelodyGenerator {

  private $WITH_DURATION = false;
  
  // Essentially the divisibility of the initial unit of length (eg quarter note, etc)
  private $DEFAULT_DURATION = 1; // Multiple of Crochets (can be fraction)
  private $SHORTEST_NOTE; // Smallest fraction of a crotchet. All durations represented as fractions of this.
  private $TICKS_PER_CROTCHET;
  private $GRAIN; // Number of ticks in the shortest note.

  public function __construct($withDuration = false, $ticksPerCrotchet = 480, $shortestNote = 0.25) {
    $this->WITH_DURATION = $withDuration;
    $this->SHORTEST_NOTE = $shortestNote; // Fraction of a crotchet
    $this->TICKS_PER_CROTCHET = $ticksPerCrotchet;
    $this->GRAIN = $this->TICKS_PER_CROTCHET * $this->SHORTEST_NOTE;
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

  public function generateRandomMelody($length = 16, $range=12, $disjunctness = 5){ // Range in 8tves
  $melody = array();
  $dist = Gaussian::generateCumulativeGaussianDistribution($range,$disjunctness);
  
  $numberOfGrains = $this->DEFAULT_DURATION / $this->SHORTEST_NOTE;
  $melody[] = "0:$numberOfGrains"; // First offset is always zero.
  
  $sum = 0;
  $lengthSum = 0;
  while($lengthSum < ($length - $this->DEFAULT_DURATION)) {
    $next = $this->getNext($dist);
    while(abs($sum + $next) > $range) {
      $next = $this->getNext($dist);
    }

    $sum += $next;
    if($this->DEFAULT_DURATION) {
      $melody[] = "$next:$numberOfGrains";
      $lengthSum += $this->DEFAULT_DURATION; // Multiple of crotchets. We're aiming to get 4 bars of 4/4 out of this.
    }else{
      $melody[] = $next;
      $lengthSum++;
    }
  }
  return $melody;
  }

  public function getNotes($offsets, $start_note=60) {
    $notes = array();

    // Kinda kludgey. Must be a better way..
    $note = $start_note;
    $cumulative = $start_note;

    for($i = 0, $size = count($offsets); $i < $size; $i++) {
      $offset = $offsets[$i];
      if($this->WITH_DURATION) {
        $offset = explode(":",$offset);
        $cumulative += $offset[0];
        $note = $cumulative.":".$offset[1];    
      }else{
        $note += $offset;
      }
      $notes[] = $note;
    }

    return $notes;
  }

  public function writeMelody($notes, $fname="test") {
    $midi = new Midi(); // No "$" on the class.
    $midi->open($this->TICKS_PER_CROTCHET);
    $midi->setBpm(120);

    if($this->WITH_DURATION) {
      $grain = $this->GRAIN;
    }else{
      $grain = $this->TICKS_PER_CROTCHET;
    }

    $tn = $midi->newTrack() - 1;

    $t = 0; // Start time.

    for($n = 0, $size = count($notes); $n < $size; $n++) {
      if($this->WITH_DURATION) {
        $offsetAndTime = explode(":",$notes[$n]);
        $note = $offsetAndTime[0];
        $duration = (int) $offsetAndTime[1];

        $to = $t + ($grain * $duration); // End time. The rapture for this note.
      }else{
        $note = $notes[n];
        // Calculate times
        $to = $t + $crotchet; // Time offset
      }

      // Generate control strings..
      $onMsg = "$t On ch=1 n=$note v=90";
      $offMsg = "$to Off ch=1 n=$note v=90";
      //echo($onMsg."\n");
      //echo($offMsg."\n");

      // Add on/off for a single note.
      $midi->addMsg($tn,$onMsg);
      $midi->addMsg($tn,$offMsg);

      $t = $to; // End of previous note, start of next note.
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
