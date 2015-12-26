<?php
$CalcGemFromTot = isset($_POST["CalcGemFromTot"]);
$CalcTotFromGem = isset($_POST["CalcTotFromGem"]);
$CalcRFromU = isset($_POST["CalcRFromU"]);
$R6 = $_POST["R6"];
$R7 = $_POST["R7"];
$R67 = $_POST["R67"];
$Utot = $_POST["Utot"];
$Ugem = $_POST["Ugem"];
$Udrift = $_POST["Udrift"];
$Rrest = $_POST["Rrest"];
if ($R7==0 && $R6!=0) $R7 = 3-$R6;
if ($R6==0 && $R7!=0) $R6 = 3-$R7;
if (!isset($_POST["Rrest"])) $Rrest = 2.8417;
if (!isset($_POST["R67"]) && $R6!=0) $R67 = $R6+$R7;
elseif (!isset($_POST["R67"]) && $R6==0) $R67 = 3;
if (!isset($_POST["Ugem"])) $Ugem = 350;
?>

<?php include("xhtmlNav.php") ?>
<?php include("projectSideNav.php") ?>


<div id="work_main" class="centered">
    <h3><a class="intLink" name="UFromR">Voltages from R6, R7</a></h3>

<?php
  //  echo "R6 " . $R6 . ", R7 " . $R7 . ", U_tot = " . $Utot . ", U_gem = " . $Ugem . " V <br/>";
?>

<form action="TacticHV.php#UFromR" method="post">
	<label for="R6">R6</label><input type="text" name="R6" id="R6" size="4" value="<?php echo $R6; ?>" /> 
<label for="R7">R7</label><input type="text" name="R7" id="R7" size="4" value="<?php echo $R7; ?>" />
<label for="Rrest">R<sub>other</sub></label><input type="text" name="Rrest" id="Rrest" size="4" value="<?php echo $Rrest; ?>" /> <br/>
<br/>
<label for="Utot">U<sub>tot</sub></label><input type="text" name="Utot" id="Utot" size="5" value="<?php echo $Utot; ?>"/>
<a class="tooltip"><input class="submit" type="submit" value="calculate U_gem" name="CalcGemFromTot"/><span>calculate the voltage across the GEMs from the total applied voltage</span></a> <br/>
<br/>
<label for="Ugem">U<sub>gem</sub></label><input type="text" name="Ugem" id="Ugem" size="5" value="<?php echo $Ugem; ?>"/>
<a class="tooltip"><input class="submit" type="submit" value="calculate U_tot" name="CalcTotFromGem"/><span>calculate the total applied voltage from the voltage across the GEMs</span></a> <br/>
<br/>

</form>

<?php
  //echo "CalcGemFromTot " . $CalcGemFromTot . "CalcTotFromGem " . $CalcTotFromGem . " <br/>";
if ($CalcGemFromTot==1)
{
	    $Ugem = $R6/($Rrest+$R6+$R7) * $Utot;
	    $Udrift = $Rrest/($Rrest+$R6+$R7) * $Utot;	    
	    echo "U<sub>gem</sub> = " . (round($Ugem*10)/10) . " V, U<sub>drift</sub> = " . (round($Udrift*10)/10) . " V <br/>" ;
	    if ($Utot!=0) echo "gem/hv = " .  (round($Ugem/$Utot*1000))/1000 . " <br/>";
	    else echo "<br/>";
 }
elseif ($CalcTotFromGem==1 && $R6!=0)
{
	    $Utot = ($Rrest+$R6+$R7)/$R6 * $Ugem;	    
	    $Udrift = $Rrest/($Rrest+$R6+$R7) * $Utot;	    
	    echo "U<sub>tot</sub> = " . (round($Utot)) . " V, U<sub>drift</sub> = " . (round($Udrift*10)/10) . " V <br/>";
	    if ($Utot!=0) echo "gem/hv = " .  (round($Ugem/$Utot*1000))/1000 . " <br/>";
	    else echo "<br/>";
 }
 else echo "<br/><br/>";

?>
    <hr/>
    <h3><a class="intLink" name="RFromU">R6, R7 from voltages</a></h3>

<?php
  //  echo "R6 " . $R6 . ", R7 " . $R7 . ", U_tot = " . $Utot . ", U_gem = " . $Ugem . " V <br/>";
?>

<form action="TacticHV.php#RFromU" method="post">
<label>R6+R7</label><input type="text" name="R67" size="4" value="<?php echo $R67; ?>" /> 
<label>R<sub>other</sub></label><input type="text" name="Rrest" size="4" value="<?php echo $Rrest; ?>" /> <br/>
<label>U<sub>tot</sub></label><input type="text" name="Utot" size="5" value="<?php echo $Utot; ?>"/>
<label>U<sub>gem</sub></label><input type="text" name="Ugem" size="5" value="<?php echo $Ugem; ?>"/>
<br/>
<br/>
<a class="tooltip"><input class="submit" type="submit" value="calculate R6, R7" name="CalcRFromU"/><span>calculate R6 and R7 from the applied voltages</span></a> <br/>
<br/>
</form>

<?php
  //echo "CalcGemFromTot " . $CalcGemFromTot . "CalcTotFromGem " . $CalcTotFromGem . " <br/>";
    if ($CalcRFromU==1 && $R67!=0 && $Utot!=0)
{
	    $R6  = $Ugem/ $Utot * ($Rrest+$R67) ;
	    $R7 = $R67-$R6;	    
	    $Udrift = $Rrest/($Rrest+$R6+$R7) * $Utot;	    
	    echo "R6 = " . (round($R6*100)/100) . " , R7 = " . (round($R7*100)/100) . "<br/>";
	    echo "U<sub>drift</sub> = " . (round($Udrift*10)/10) . " V <br/>";
 }
 else echo "<br/><br/>";

?>


	<br/>
	
    <hr/>
<center>
<small>Created by Ulrike</small> 
</center>
<!-- hhmts start -->
<!-- hhmts end -->
</div>
</body>
</html>
