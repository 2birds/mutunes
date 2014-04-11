<?php
session_name('contributorCheck');
session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

  <head>
  <title>MuTunes</title>
  <meta http-equiv="content-type" 
  content="text/html;charset=utf-8" />
  <?php require($_SERVER["DOCUMENT_ROOT"]."/mutunes/components/cssHeaders.php"); 
  /*
 require($_SERVER["DOCUMENT_ROOT"]."/mutunes/ga-utilities/Melody.class.php");
require($_SERVER["DOCUMENT_ROOT"]."/mutunes/ga-utilities/GeneticFunctionsWithDuration.class.php");
  */
require_once($_SERVER["DOCUMENT_ROOT"]."/mutunes/ga-utilities/GenerationFunctions.php");
  ?>
  </head>

  <body>
  <div class="main_container">
  <?php
  require($_SERVER["DOCUMENT_ROOT"]."/mutunes/components/title.php");
require($_SERVER["DOCUMENT_ROOT"]."/mutunes/components/menu.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/mutunes/ga-utilities/convergenceData.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/mutunes/ga-utilities/melodySelector.php"); ?>

<?php

echo("<p>\n");
for($i = 0; $i < 100; $i++){
  $cd = getConvergenceData();
  echo("Longest common substring: $cd[0]<br />Average: $cd[1]");

  $mels = go();
  //echo("<p>$mels[0], $mels[1]</p>");

  $fittest = disjunctFitness($mels[0],$mels[1]);
  $battleResults = explode("-",$fittest);

  if(isset($_POST['musician'])){
    $musician = $_POST['musician'];
  }else{
    $musician = false;
  }

  query("START TRANSACTION"); // DB Lock
  $q = "INSERT INTO `battles` (`winnerId`, `loserId`, `musician`) ".
    "VALUES ($battleResults[0], $battleResults[1], $musician);";
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

  // This is now redundant. I think.

  checkGenerationThreshold();


}
echo("</p>\n");

function disjunctFitness($melID1, $melID2) {
  // Returns id of most disjunct melody
  $q = "SELECT `id`, `melodyString` from `melodies` where `id` in ($melID1, $melID2);";
  $res = query($q);

  $disjunctness = array();
  while($arr = mysqli_fetch_array($res)) {
    $mel = Melody::melodyStringToArray($arr['melodyString']);

    $cumulative = 0;
    $c = 0;
    foreach($mel as $m) {
      // Next line here should be abs($m[0])
      // What I have done is selected for ending as far as possible
      // from the starting note.
      $cumulative += $m[0];
      $c++;
    }

    // Store average offset.
    $disjunctness[$arr['id']] = $cumulative / $c;
  }

  // Return most disjunct melody, or random one if equally disjunct.
  if($disjunctness[$melID1] > $disjunctness[$melID2]){
    $ans =  "$melID1-$melID2";
  }else if($disjunctness[$melID2] > $disjunctness[$melID1]){
    $ans =  "$melID2-$melID1";
  }else{
    if(rand(0,1) == 0) {
    $ans =  "$melID1-$melID2";
    }else{
      $ans =  "$melID2-$melID1";
    }
  }

  return $ans;
}

function doPost($data, $destination){
  /* Based on http://stackoverflow.com/questions/5647461/how-do-i-send-a-post-request-with-php */
  $url = $destination;
  //$data = array('key1' => 'value1', 'key2' => 'value2');

  // use key 'http' even if you send the request to https://...
  $options = array(
		   'http' => array(
				   'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
				   'method'  => 'POST',
				   'content' => http_build_query($data),
				   ),
		   );
  $context  = stream_context_create($options);
  echo($url);
  $result = file_get_contents($url, false, $context);

  var_dump($result);
}


?>
   
<?php require($_SERVER["DOCUMENT_ROOT"]."/mutunes/components/footer.php"); ?>
</div>
</body>
</html>
