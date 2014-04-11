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
<?php 
require_once($_SERVER["DOCUMENT_ROOT"]."/mutunes/components/cssHeaders.php"); 
require_once($_SERVER["DOCUMENT_ROOT"]."/mutunes/php_utils/db_query.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/mutunes/ga-utilities/populationGenerator.php");

?>
                  </head>

                  <body>
<div class="main_container">
<?php
require($_SERVER["DOCUMENT_ROOT"]."/mutunes/components/title.php");
require($_SERVER["DOCUMENT_ROOT"]."/mutunes/components/menu.php");
?>
 <?php
if(isset($_POST['username'])) {
  $uname = $_POST['username'];
}else{
  $uname = '';
}

if(isset($_POST['password'])) {
  $pword = $_POST['password'];
}else{
  $pword = '';
}

if($uname == 'rob' && $pword == 'samiad'){
  $q = "SELECT * FROM `melodies`;";
  $res = query($q);

     $melodies_csv_file = "";
     $melodies_csv_file = $melodies_csv_file."Melody ID,";
     $melodies_csv_file = $melodies_csv_file."Melody Representation,";
     $melodies_csv_file = $melodies_csv_file."Wins,";
     $melodies_csv_file = $melodies_csv_file."Defeats,";
     $melodies_csv_file = $melodies_csv_file."Total Wins,";
     $melodies_csv_file = $melodies_csv_file."Total Defeats,";
     $melodies_csv_file = $melodies_csv_file."Generation of Introduction,";
     $melodies_csv_file = $melodies_csv_file."Parent A ID,";
     $melodies_csv_file = $melodies_csv_file."Parent B ID,";
     $melodies_csv_file = $melodies_csv_file."In Population,";
     $melodies_csv_file = $melodies_csv_file."Position,";
     $melodies_csv_file = $melodies_csv_file."Removed At Generation,";
     $melodies_csv_file = $melodies_csv_file."\n";
  ?>
     <!--
  <table>
     <th>Melody ID</th>
     <th>Melody Representation</th>
     <th>Wins</th>
     <th>Defeats</th>
     <th>Total Wins</th>
     <th>Total Defeats</th>
     <th>Generation of Introduction</th>
     <th>Parent A ID</th>
     <th>Parent B ID</th>
     <th>In Population</th>
     <th>Position</th>
     <th>Removed At Generation</th>
     -->
     <?php
     while($row = mysqli_fetch_row($res)) {
       //echo("\n<tr>");
       for($i = 0; $i < count($row); $i++) {
	 //echo("<td>");
	 //echo($row[$i]);
	 //echo("</td>");

	 // Add to csv for d/l
	 $melodies_csv_file = $melodies_csv_file.str_replace(",","/",$row[$i]).",";
       }
       //echo("</tr>");
       $melodies_csv_file = $melodies_csv_file."\n";
       }

  $comparisons_csv_file = "";
  $comparisons_csv_file = $comparisons_csv_file."Comparison number,";
  $comparisons_csv_file = $comparisons_csv_file."Winner ID,";
  $comparisons_csv_file = $comparisons_csv_file."Loser ID,";
  $comparisons_csv_file = $comparisons_csv_file."Is musician,";
  $comparisons_csv_file = $comparisons_csv_file."Timestamp,\n";
  $q = "SELECT * FROM `battles`;";
  $res = query($q);

  while($row = mysqli_fetch_row($res)) {
    for($i = 0; $i < count($row); $i++) {
      $comparisons_csv_file = $comparisons_csv_file.$row[$i].","; 
    }
    $comparisons_csv_file = $comparisons_csv_file."\n"; 
  }
file_put_contents("mels.csv",$melodies_csv_file);
file_put_contents("comparisons.csv",$comparisons_csv_file);
  ?>
  <!--</table>-->
  <a href='mels.csv' download='mels.csv' >Download melodies information</a><br /> 
  <a href='comparisons.csv' download='comparisons.csv' >Download comparisons information</a><br /> 
      <?php
}else{
?>
   <form action='' method='post'>
      Uname: <input type='text' name='username'><br />
      Password: <input type='password' name='password'><br />
      <input type='submit' value='submit' />
</form>
      <?php } ?>
<?php require_once($_SERVER["DOCUMENT_ROOT"]."/mutunes/components/footer.php"); ?>
</p>
</div>
                       </body>
                     </html>
