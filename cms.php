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

$q = "SELECT * FROM melodies WHERE inPopulation=1 ORDER BY ((totalWins - totalDefeats) + (wins / 10)) DESC;";
$result = query($q);

if(isset($_POST['threshold'])){
    $q = "UPDATE generalProperties ".
      "SET threshold=".$_POST['threshold']." ".
      "WHERE name='properties';";
    query($q);
}

if(isset($_POST['popsize'])){
    $q = "UPDATE generalProperties ".
      "SET popsize=".$_POST['popsize']." ".
      "WHERE name='properties';";
    query($q);
}

if(isset($_POST['gamma'])){
    $q = "UPDATE generalProperties ".
      "SET gamma=".$_POST['gamma']." ".
      "WHERE name='properties';";
    query($q);
}

if(isset($_POST['percentToReplace'])){
    $q = "UPDATE generalProperties ".
      "SET percentToReplace =".$_POST['percentToReplace']." ".
      "WHERE name='properties';";
    query($q);
}

if(isset($_POST['reset']) && $_POST['reset'] == 'reset') {
  resetSystem();
}

function resetSystem(){
  query("START TRANSACTION;");
  $q = "TRUNCATE TABLE battles;";
  query($q);
  $q = "TRUNCATE TABLE melodies;";
  query($q);
  $q = "TRUNCATE TABLE contributors;";
  query($q);
  $q = "UPDATE generalProperties SET currentPosition=1 WHERE name='properties';";
  query($q);
  query("COMMIT;");
  exec("rm music/*.wav music/*.mp3 music/*.mid"); 
  generateInitialPopulation();
  
  echo("<script type='text/javascript'>alert('Population reset successful!');</script>");
}
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

if($uname == 'rob' && $pword == 'samiad'){ ?>
<form name='controls' action='' method='post'>
  Threshold for crossover:<input type='text' name='threshold' /><br />
  Popsize:<input type='text' name='popsize' /><br />
  Gamma:<input type='text' name='gamma' /><br />
  Crossover percentage:<input type='text' name='percentToReplace' /><br />
  Reset:<input type='checkbox' name='reset' value='reset' /><br />
  <input type='submit' value='Submit!' />
</form>
			     <h3>Number of comparisons so far:
    <?php
    $q = "SELECT max(battleId) FROM battles;";
  $res = query($q);
  $row = mysqli_fetch_row($res);
  echo($row[0]);
  ?>
  </h3>

			 <table>
			 <tr><th>id</th>
			 <th>Score</th>
			 <th>Wins</th>
			 <th>defeats</th>
			 <th>Total Wins</th>
			 <th>Total Defeats</th></tr>
			 <?php
			 while($row = mysqli_fetch_array($result)) {
			   echo("<tr>".
				"<td>");

			   if($row['totalWins'] + $row['totalDefeats'] == 0){
			     echo("<span class='new'>New!</span>");
			   }

			   echo($row['id']."</td>".
				"<td>".($row['totalWins'] - $row['totalDefeats'])."</td>".
				"<td>".$row['wins']."</td>".
				"<td>".$row['defeats']."</td>".
				"<td>".$row['totalWins']."</td>".
				"<td>".$row['totalDefeats']."</td>".
				"</tr>");
			 }
?>
</table>
    <?php }else{
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
