<?php
function generalPlayer($mel) {
  echo("<h4 onclick='selectThisSiblings(event)'>Melody ".$mel."</h4>".
       "<audio controls>\n".
       "<source src='music/".$mel.".mp3' type='audio/mp3' />\n".
       "<source src='music/".$mel.".wav' type='audio/wav' />\n".
       "</audio>");
}
?>
