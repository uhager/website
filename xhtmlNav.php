<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en-US" xml:lang="en-US" xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="Content-type" content="text/html;charset=iso-8859-1" /> 
    <meta name="description" content="Ulrike Hager, JYFLTRAP, projects, programs and publications"/>
    <meta name="keywords" content="Ulrike Hager, JYFLTRAP"/>
    <meta name="author" content="Ulrike Hager"/>
    <link rel="icon" href="favicon.ico" type="image/x-icon"/>
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon"/>
     <title>Home of Ulrike Hager</title>
<link href="styleSheet.css" rel="stylesheet" type="text/css"/>
<?php
$pathArray = explode("/",$_SERVER['PHP_SELF']);
$thisScript = $pathArray[count($pathArray)-1];
$thisScript = str_replace(".php","",$thisScript);
$pathArray = explode("-",$thisScript);
$thisProject = $pathArray[0];
$thisProject = $thisProject . "List";
	echo "<style type=\"text/css\">\n";
printf("#%s {display:block; position:relative;}\n",$thisProject);
	echo "</style>\n";
?>
  </head>
<body>

<div id="upperNav">
	 <ul class="nav">
	 <li><a class="nav" href="index.php">About</a> </li>
<li><a class="nav" href="publications.php">Publications</a></li> 
 <li><a class="nav" href="conf_contrib.php">Presentations</a> </li> 
<li><a class="nav" href="projects.php">Projects</a></li> 
<!--	 <li><a class="nav" href="ongoing.php" >Ongoing</a></li> -->
<li><a class="nav" href="links.php">Links</a> </li>
</ul>
</div>


