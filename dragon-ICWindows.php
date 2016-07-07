<?php
session_start();
if (isset($_POST["endSession"])==1) session_destroy();
if(!isset($_SESSION['first']) || isset($_POST["endSession"])) $_SESSION['first']=TRUE;
else $_SESSION['first']=FALSE;
if(!isset($_SESSION['windows']) || isset($_POST["endSession"])) $_SESSION['windows']= array();
if(!isset($_SESSION['currentWindow']) || isset($_POST["endSession"])) $_SESSION['currentWindow']= array();
$addNew = isset($_POST["addNew"]);
$delete = isset($_POST["delete"]);
$save = isset($_POST["save"]);
$makeCurrent = isset($_POST["makeCurrent"]);
$windows = $_POST["windows"];
$currentWindow = $_POST["currentWindow"];
$newThick=$_POST["newThick"];
$newGrid=$_POST["newGrid"];
$newFrame=$_POST["newFrame"];
$newComm=$_POST["newComm"];
$deleteNo=$_POST["deleteNo"];
$newCurrent=$_POST["newCurrent"];
?>
<?php include("navigation.php") ?>
<?php include("projectSideNav.php") ?>

<div id="work_main">

<?php
  //if ($addNew==0 && $delete==0 && $makeCurrent==0)
if ($_SESSION['first']==TRUE)
{
$file = fopen("ICWindows.dat", "r") or exit("No inventory file found. Please create file 'ICWindows.dat'");
while(!feof($file))
  {
  $line = fgets($file);
  $window = explode("--",$line); //structure: avail/current(a/c)--thickness--grid(y/n)--frame(l/s[=long/short])--comment
  //  print_r($window) ;
  if ($window[0]=="c" ) $_SESSION['currentWindow'] = $window;
  elseif ($window[0]=="a") $_SESSION['windows'][] = $window;
  }
fclose($file);
}
elseif ($addNew==1)
{
  $newComm = $newComm . "\n";
  $window=array("a",$newThick,$newGrid,$newFrame,$newComm);
  $_SESSION['windows'][] = $window;
}
elseif ($delete==1)
{
  unset($_SESSION['windows'][$deleteNo]);
  $_SESSION['windows']=array_values($_SESSION['windows']);
}
elseif ($makeCurrent==1)
{
  $_SESSION['windows'][] = $_SESSION['currentWindow'];
  $_SESSION['currentWindow'] =$_SESSION['windows'][$newCurrent];
  unset($_SESSION['windows'][$newCurrent]);
  $_SESSION['windows']=array_values($_SESSION['windows']);
}
elseif ($save==1)
{
  $file = fopen("ICWindows.dat", "w") or exit("Could not open file 'ICWindows.dat' for writing");
  if (count($_SESSION['currentWindow'])>1) {
    fwrite($file,"c");
    for ($count=1;$count<count($_SESSION['currentWindow']);$count++) {
      fwrite($file,"--".$_SESSION['currentWindow'][$count]);
    }
    //   fwrite($file,"\n");
  }
  for ($winNum=0;$winNum<count($_SESSION['windows']);$winNum++) {
    fwrite($file,"a");
    $aWindow = $_SESSION['windows'][$winNum];
    for ($count=1;$count<count($aWindow);$count++) {
      fwrite($file,"--".$aWindow[$count]);
    }
    //    fwrite($file,"\n");
  }
fclose($file);
}

if (count($_SESSION['currentWindow'])>1) {
  echo "<table class=\"list\"><caption>current window</caption><tr class=\"title\"><td>thickness</td><td>grid</td><td>frame</td><td>comment</td></tr>\n<tr>";
  for ($count=1;$count<count($_SESSION['currentWindow']);$count++) {
    printf("<td>%s</td>",$_SESSION['currentWindow'][$count]);
  }
  echo "</tr></table><br/> ";
 }
 else echo "no current window <br/>";

  echo "<table class=\"list\"><caption>available windows</caption><tr class=\"title\"><td>No.</td><td>thickness</td><td>grid</td><td>frame</td><td>comment</td></tr>\n";
  for ($winNum=0;$winNum<count($_SESSION['windows']);$winNum++) {
    echo "<tr>\n";
    printf("<td>%d</td>",$winNum);
    $aWindow = $_SESSION['windows'][$winNum];
    for ($count=1;$count<count($aWindow);$count++) {
      printf("<td>%s</td>",$aWindow[$count]);
    }
    echo "</tr>\n";
  }
echo "</table>";

?>
<br/>

<form class="leftish" method="post">
  <input class="submit" type="submit" value="add window" name="addNew" title="Add new window to available windows"/>
	thickness <input type="text" name="newThick" size="7" value="<?php echo $newThick; ?>" /> 
	grid <input type="text" name="newGrid" size="2" value="<?php echo $newGrid; ?>" /> 
	frame <input type="text" name="newFrame" size="5" value="<?php echo $newFrame; ?>" /> 
	comment <input type="text" name="newComm" size="15" value="<?php echo $newComm; ?>" /> 
 <br/>
</form>

<form class="leftish" method="post">
<input class="submit" type="submit" value="delete window" name="delete" title="delete window from available windows"/>
No. <input type="text" name="deleteNo" size="2" value="<?php echo $deleteNo; ?>" />  <br/>
</form>
<form class="leftish"  method="post">
<input class="submit" type="submit" value="current window" name="makeCurrent" title="choose available window as current"/>
No. <input type="text" name="newCurrent" size="2" value="<?php echo $newCurrent; ?>" />  <br/>
</form>
<form class="leftish"  method="post">
  <input class="submit disabled" type="submit" value="save" name="save" disabled="disabled" title="write window list to file"/> <br/>
</form>

<br/>
<form class="leftish" method="post">
  <input class="submit" type="submit" value="end session" name="endSession" title="end this session, forget changes, reread file"/> <br/>
</form>

<br>

<hr/>

<div class="centered">
<small>Created by Ulrike</small> 
</div>
<!-- hhmts start -->
<!-- hhmts end -->
</div>
</body>
</html>
