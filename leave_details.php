<?php
session_name('contributorCheck');
session_start();
?>
<?php require($_SERVER["DOCUMENT_ROOT"]."/mutunes/components/submitFormHandler.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
        <html xmlns="http://www.w3.org/1999/xhtml">

          <head>
              <title>MuTunes</title>
                <meta http-equiv="content-type" 
                    content="text/html;charset=utf-8" />
<?php include($_SERVER["DOCUMENT_ROOT"]."/mutunes/components/cssHeaders.php"); ?>
 <?php
  echo('<script type="text/javascript">');
echo("alert('".$msg."');");
  echo('</script>');
?>
<?php require($_SERVER["DOCUMENT_ROOT"]."/mutunes/components/javascriptHeaders.php"); ?>
                  </head>

<div class="main_container">
                  <body>
<?php
require($_SERVER["DOCUMENT_ROOT"]."/mutunes/components/title.php");
require($_SERVER["DOCUMENT_ROOT"]."/mutunes/components/menu.php");
?>

<p>There are two reasons to leave your details here:
<ol>
<li>To be credited as a composer/contributor at the end of this project</li>
<li>To be entered into <i>a fantastic prize draw!</i></li>
</ol>
The two people who make the biggest contribution (ie., make the most comparisons) will win a prize. I will be giving away a <a href="http://www.raspberrypi.org/" target="_blank">Raspberry Pi</a>, and a harmonica! May the odds be ever in your favour!
</p>
<?php
if(isset($_SESSION['isContributor'])) {
include($_SERVER['DOCUMENT_ROOT']."/mutunes/components/submitDetailsForm.php");
}else{
echo("<p class='error'><b>Whoops!</b> It looks like you haven't contributed yet to the project. <a href='compare.php'>Click here to help out.</a> If you've contributed previously and submitted your details, don't worry, we still have them.</p>");
}
?>

<?php require($_SERVER["DOCUMENT_ROOT"]."/mutunes/components/footer.php"); ?>
</div>
                       </body>
                     </html>
