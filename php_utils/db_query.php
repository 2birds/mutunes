<?php
function query($q) {
  $conn = mysqli_connect('ephesus.cs.cf.ac.uk', 'p1125589','ChapAk','p1125589');
  mysqli_select_db($conn,'p1125589');
  $result = mysqli_query($conn,$q);
  mysqli_close($conn);
  return $result;
}
?>
