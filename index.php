<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

   <head>
   <title>MuTunes</title>
   <meta http-equiv="content-type" 
   content="text/html;charset=utf-8" />
   <?php require($_SERVER["DOCUMENT_ROOT"]."/mutunes/components/cssHeaders.php"); ?>
   </head>

   <body>
   <div class="main_container">
   <?php
   require($_SERVER["DOCUMENT_ROOT"]."/mutunes/components/title.php");
require($_SERVER["DOCUMENT_ROOT"]."/mutunes/components/menu.php");
?>

<p>Welcome to the MuTunes project! This is the final project of my undergraduate degree. The intention is to allow composition of melodies and, eventually, entire pieces of music by a great number of composers, using a <a href='http://http://en.wikipedia.org/wiki/Genetic_algorithm' target='_blank'>genetic algorithm</a>.</p>
<p>Traditional composition limits the number of composers who can contribute by a number of factors: technical knowledge of music, ability to communicate between composers and ability to reach consensus being just a few. The MuTunes project bypasses all of these problems. By allowing composers to steer the development of the music by simply expressing a preference, people with no musical ability or training can contribute. The level of your contribution depends entirely on how many times you are willing to express a preference (ie., vote).</p> 
<i>But how does it work?</i>
<p>I'm glad you asked. First, a series of notes is generated at random (well, nearly random). Then, users are presented with two of these series to listen to. Then they vote for which one they prefer. From these votes, the unpopular ideas are weeded out, and the popular ideas are kept. Then these popular ideas are cross-bred, like prize-winning horses, in an attempt to produce something even better. Over time, highly musical melodies should emerge, with many people as their composers.</p>

<p>Lastly, should you notice anything wrong with the site, please <a href='mailto:HawkinsRH@cardiff.ac.uk?Subject=Mutunes%20site%20problem' target='_top'>email me</a> with a description. Thanks!</p>
<?php require($_SERVER["DOCUMENT_ROOT"]."/mutunes/components/footer.php"); ?>
</div>
</body>
</html>
