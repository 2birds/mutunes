<?php
session_name('contributorCheck');
session_start();
?>
<!DOCTYPE html PUBLIC "-//W1C//DTD XHTML 1.0 Strict//EN
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

  <head>
  <title>MuTunes</title>
  <meta http-equiv="content-type" 
  content="text/html;charset=utf-8" />
  <?php require($_SERVER["DOCUMENT_ROOT"]."/mutunes/components/cssHeaders.php"); ?>
  <?php require_once($_SERVER["DOCUMENT_ROOT"]."/mutunes/ga-utilities/melodySelector.php"); ?>
  <?php require($_SERVER["DOCUMENT_ROOT"]."/mutunes/components/javascriptHeaders.php"); ?>
  </head>

  <body>
  <div class="main_container">
  <?php
  require($_SERVER["DOCUMENT_ROOT"]."/mutunes/components/title.php");
require($_SERVER["DOCUMENT_ROOT"]."/mutunes/components/menu.php");
?>

<div class="player">
  <form name="comparison" action="ga-utilities/melodyChoiceHandler.php" method="post" onsubmit="return(checkSelected());" >
  <div class="sub-player" onclick="selectThis(event)">
  <?php 
  $melIDs = go();
makePlayer($melIDs[0],$melIDs[1]);
?>
<span onclick='selectThisSiblings(event)' class="choice_label">I like this one..</span>
  </div>
  <div class="sub-player" onclick="selectThis(event)">
  <hr />
  <?php 
  makePlayer($melIDs[1],$melIDs[0]);
?>
<span onclick='selectThisSiblings(event)' class="choice_label">No, actually I prefer this one!</span>
  </div>
  <div class='speedControls'>
  <span>Playback<br />Speed</span>
  <div>
  <button type='button' onclick='playFast()'>&uarr;</button><br />
  <button type='button' onclick='playSlow()'>&darr;</button>
  </div>
  <span id='speedValue'>
  <?php // 
  if(isset($_SESSION['speedcache']) && $_SESSION['speedcache'] != ''){
    echo($_SESSION['speedcache']);
  }else{ echo('1.0');}
?>
    </span>
    
<?php
    if(isset($_SESSION['speedcache']) && $_SESSION['speedcache'] != '') {
      $speed = $_SESSION['speedcache'];
    }else{
      $speed = '1.0';
    }

    echo("<input id='speedcache' type='hidden' name='speedcache' value='".$speed."' />");
echo("<script>setSpeed(".$speed.")</script>");
?>
    </div>
<br />
<input type="radio" name="musician" value="true"
    <?php if(isset($_SESSION["musician"]) && $_SESSION["musician"] == "true") {echo("checked");} ?>
/>
<span class="choice_label">I am a musician.</span>
<input type="radio" name="musician" value="false"
    <?php if(!isset($_SESSION["musician"]) || $_SESSION["musician"] == "false") {echo("checked");} ?>
/>
<span class="choice_label">I am <u>not</u> a musician.</span>
<input type="submit" value="submit" />

</form>
</div>

<p>Here is your chance to contribute!</p>
<p>Listen to the two melodies, and select which one you think has more musical elements. Even if you only like a bit of it.</p>
    <p>If you think they&#8217;re both horrible, select the one you think is least awful.</p>

<p>This is your contribution, and it will sway the evolution of this piece.<p />
<p>Make as many comparisons as you like,the more the better. Thanks for helping me out!</P>

<?php require($_SERVER["DOCUMENT_ROOT"]."/mutunes/components/footer.php"); ?>
</div>
</body>
</html>
