<?php
$Rc = isset($_POST["Rcalc"]);
$Yc = isset($_POST["Ycalc"]);
$B = $_POST["B"];
$MdA = $_POST["MdA"];
$A = $_POST["A"];
$ED = $_POST["ED1"];
$Q = $_POST["Q"];
$Q_2 = $_POST["Q_2"];
$FC = $_POST["FC"];
$Tr = $_POST["Tr"];
$E = $_POST["E"];
$E_2 = $_POST["E_2"];
$E_3 = $_POST["E_3"];
$t = $_POST["t"];
$p = $_POST["p"];
$N = $_POST["N"];
$p_2 = $_POST["p_2"];
$N_2 = $_POST["N_2"];
$Nr = $_POST["Nr"];
$cs = $_POST["cs"];
$R_2 = $_POST["R_2"];
$eff_1 = $_POST["eff_1"];
$eff_2 = $_POST["eff_2"];
$eff_3 = $_POST["eff_3"];
if (!isset($_POST["B"])) $B = 1;
if (!isset($_POST["Q"])) $Q = 1;
if (!isset($_POST["MdA"])) $MdA = 1;
if (!isset($_POST["eff_1"])) $eff_1 = 98;
if (!isset($_POST["eff_2"])) $eff_2 = 78;
if (!isset($_POST["eff_3"])) $eff_3 = 100;
if (!isset($_POST["Tr"])) $Tr = 90;
if (!isset($_POST["Q_2"])) $Q_2 = $Q;
if (!isset($_POST["N"])) $N = $N_2;
if (!isset($_POST["p"])) $p = $p_2;
if (isset($_POST["E_3"])) $E_2 = $E_3;
if (isset($_POST["E_3"])) $E = $E_3;
$nucleus = $_POST['nucleus'];
if (!isset($_POST['nucleus'])) $nucleus = '15o';
$MD1const = 0.0004815;
?>

<?php 
include("xhtmlNav.php");
include("projectSideNav.php") ;

function csv_to_array($file) {
  $data = array();
  $headers = array();
  if ( !file_exists($file) ) {
    echo $file . ' not found.';
    return FALSE;
  }
  if ( !is_readable($file) ) {
    echo $file . ' not readable.';
    return FALSE;
  }
  $file_handle = fopen($file, 'r');
  while (!feof($file_handle)) {
    $line = fgetcsv($file_handle, 10240, ',');
      if (empty($headers))
	$headers = $line;
      else if (is_array($line)) {
	array_splice($line, count($headers));
	foreach ($headers as $column => $col_header) {
	  $current_row[$col_header] = $line[$column];
	}
	$data[] = $current_row;
      }
    }
  fclose($file_handle);
  return $data;
}


function value_from_array($array, $search_key, $search_value, $return_key){
  if ( !is_array($array) ) {
    echo 'not an array';
    return FALSE;
  }
  foreach ( $array as $row ) {
    if ( $row[$search_key] == $search_value ) {
      return $row[$return_key];
    }
  }
  echo $search_key . '==' . $search_value . ' not found in array.<br/>';
  return FALSE;
}

$nubtab = csv_to_array('nubtab.csv'); 
?>

<div id="work_main" class="centered">

<p>
<a class="text" href="dragon-tools.php#md1" >MD1 calibration </a> - <a class="text" href="dragon-tools.php#yield" >Yield calculation</a>
</p>
 <hr/>
<h3><a class="intLink" name="md1">MD1 energy calibration, tune ratios, ED1 calibration</a></h3>


<form action="dragon-tools.php#md1" method="post">
<label for="B">MD1 field [G]:</label>
	 <input type="text" name="B" id="B" size="6" value="<?php echo $B; ?>" />
<label for="MdA">MD1 setpoint [A]:</label>
 <input type="text" name="MdA" id="MdA" size="6" value="<?php echo $MdA; ?>" /> <br/>
<label for="nucleus">Nucleus:</label>
<input type="text" list="nucleus" name="nucleus" size="5" value="<?php echo $nucleus; ?>" />
<datalist id="nucleus">
<?php
foreach ( $nubtab as $nucleon ) {
  echo '<option>' . $nucleon['AX'] . '</option>';
}
?>
</datalist>
<label for="Q"></label>
Charge state: <input type="text" name="Q" id="Q" size="2" value="<?php echo $Q; ?>"/><br/>
<label for="ED1">ED1 setpoint voltage [kV]:</label>
 <input type="text" name="ED1" id="ED1" size="5" value="<?php echo $ED; ?>"/><br/>
<input class="submit" type="submit" value="calculate" />
</form>

<?php
if ($nucleus!=0 && $B!=0 && $Q!=0) {
  $ME = value_from_array($nubtab,'AX',$nucleus,'ME');
  //  echo 'ME = ' . $ME . '<br/>';
  $A = value_from_array($nubtab,'AX',$nucleus,'A');
  $A = ($A * 931494 + $ME) / 931494;
  echo 'ME = ' . $ME . ' keV, A = ' . round($A,3) . '<br/>';

  $E = $MD1const * ($Q*$B/$A)* ($Q*$B/$A);
  $E = round($E*100)/100;
  $ED_calc =  round(2468 * ($B/10000*$B/10000)*$Q/$A * 100)/100;
  $ED2_calc_1 = round(0.8 * $ED_calc*100)/100;
  if (!isset($_POST["E_2"])) $E_2 = $E;
  if (!isset($_POST["E_3"])) $E_3 = $E;
  echo "E = " . $MD1const . " * (" . $Q . " * " . $B . " / " . round($A,2) . ")<sup>2</sup> keV/u<br/>"; 
  echo "<b>E = " . $E . " keV/u = ". (round($E*$A)/1000) . " MeV </b><br/> ED1 = " . $ED_calc. " kV, ED2 = " . $ED2_calc_1. " kV <br/>";
}
 else
   echo "Insert MD1, A and q to calculate the beam energy. <br/>"	;
?>
	<br/>
	
<?php
if ($B!=0 && $ED!=0)
{
	$Aq = round(2468 * ($B/10000*$B/10000)/$ED * 100)/100;
	$ED2_calc_2 = round(0.8 * $ED*100)/100;
		if ($Q==0)
		{
			 echo  "A/q = " . $Aq . ", ED2 = " . $ED2_calc_2 . " kV<br/>";
		}
		else echo "A/q = " . $Aq . ", A = " . $Aq*$Q .", ED2 = " . $ED2_calc_2 . " kV<br/>";

}
else
echo "Insert B and ED1 to calculate the mass-to-charge ratio (and q for mass). <br/>"	
?>




<table id="magTables">
<tr>
<td class="tooltip" align="left">

<span class="leftTable">insert MD1 field to scale MD2, Q fields</span>
<table>
<tr>
<td align="center"><b>Magnet</b></td>
<td align="center"><b>Field</b></td>
</tr>
<tr>
<td align="center">Q1</td>
<td align="right"><?php echo round($B * 0.709*1000)/1000; ?></td>
</tr>
<tr>
<td align="center">Q2</td>
<td align="right"><?php echo round($B * 0.677*1000)/1000; ?></td>
</tr>
<tr>
<td align="center">MD1</td>
<td align="right"><?php echo round($B * 1.000*1000)/1000; ?></td>
</tr>
<tr>
<td align="center">Q3</td>
<td align="right"><?php echo round($B * 0.553*1000)/1000; ?></td>
</tr>
<tr>
<td align="center">Q4</td>
<td align="right"><?php echo round($B * 0.735*1000)/1000; ?></td>
</tr>
<tr>
<td align="center">Q5</td>
<td align="right"><?php echo round($B * 0.381*1000)/1000; ?></td>
</tr>
<tr>
<td align="center">Q6</td>
<td align="right"><?php echo round($B * 0.366*1000)/1000; ?></td>
</tr>
<tr>
<td align="center">Q7</td>
<td align="right"><?php echo round($B * 0.512*1000)/1000; ?></td>
</tr>
<tr>
<td align="center">MD2</td>
<td align="right"><?php echo round($B * 1.230*1000)/1000; ?></td>
</tr>
<tr>
<td align="center">Q8</td>
<td align="right"><?php echo round($B * 0.387*1000)/1000; ?></td>
</tr>
<tr>
<td align="center">Q9</td>
<td align="right"><?php echo round($B * 0.238*1000)/1000; ?></td>
</tr>
<tr>
<td align="center">Q10</td>
<td align="right"><?php echo round($B * 0.266*1000)/1000; ?></td>
</tr>
</table>

</td>
<td class="tooltip" align="right">

<span  class="rightTable">insert MD1 setpoint to scale SX setpoints</span>
<table>
<tr>
<td align="center"><b>Magnet</b></td>
<td align="center"><b>Setpoint</b></td>
</tr>
<tr>
<td align="center">SX1</td>
<td align="right"><?php echo round($MdA * 0.0528*10000)/10000; ?></td>

</tr>
<tr>
<td align="center">SX2</td>
<td align="right"><?php echo round($MdA * 0.0112*10000)/10000; ?></td>
</tr>
<tr>
<td align="center">MD1</td>
<td align="right"><?php echo round($MdA * 1.000*10000)/10000; ?></td>
</tr>
<tr>
<td align="center">SX3</td>
<td align="right"><?php echo round($MdA * 0.0100*10000)/10000; ?></td>
</tr>
<tr>
<td align="center">SX4</td>
<td align="right"><?php echo round($MdA * 0.0974*10000)/10000; ?></td>
</tr>
</table>

</td>
</tr>
</table>


    <hr/>
<h3><a class="intLink" name="yield">Yield calculation</a></h3>
<form action="DragonTools.php#yield" method="post">
	needed for R: <br/>	
FC4 current [enA]: <input type="text" name="FC" size="3" value="<?php echo $FC; ?>"/> 
Transmission through target [%]: <input type="text" name="Tr" size="3" value="<?php echo $Tr; ?>"/><br/>
Beam energy [AkeV]: <input type="text" name="E_2" size="4" value="<?php echo $E_2; ?>"/>
Charge state: <input type="text" name="Q_2" size="2" value="<?php echo $Q_2; ?>"/>
Run time [s]: <input type="text" name="t" size="6" value="<?php echo $t; ?>"/><br/>
Target pressure [Torr]: <input type="text" name="p" size="4" value="<?php echo $p; ?>"/>
N<sub>elastic</sub>: <input type="text" name="N" size="7" value="<?php echo $N; ?>"/><br/>
<input class="submit" type="submit" value="calculate R" name="Rcalc"/>
<input type="hidden" name="eff_1" size="3" value="<?php echo $eff_1; ?>"/>
<input type="hidden" name="eff_2" size="3" value="<?php echo $eff_2; ?>"/>
<input type="hidden" name="eff_3" size="3" value="<?php echo $eff_3; ?>"/>
<input type="hidden" name="Nr" size="7" value="<?php echo $Nr; ?>"/>
<input type="hidden" name="cs" size="3" value="<?php echo $cs; ?>"/><br/>
<input type="hidden" name="N_2" size="7" value="<?php echo $N_2; ?>"/>
</form>
<br/>
<?php
	if ($Rc==1 && $FC!=0 && $Tr!=0 && $E_2!=0 && $Q_2!=0 && $t!=0 && $p!=0 && $N!=0)
{
	$R = ($FC*1e-9*($Tr/100))/(1.602176487e-19*$Q_2)*$t*$p/($N*$E_2*$E_2);
	$Nb = $N*$R*$E_2*$E_2/$p;
	echo "R = " . round($R*10)/10 . " Torr/(AkeV)<sup>2</sup><br/>";
	printf("incident beam N<sub>beam</sub> = %.3e <br/>",$Nb);
	if (!isset($_POST["R_2"])) $R_2 = round($R*100)/100;
	if (!isset($_POST["N_2"])) $N_2 = $N;
	if (!isset($_POST["E_3"])) $E_3 = $E_2;
	if (!isset($_POST["p_2"])) $p_2 = $p;
}
?>
<br/>
To calculate yield:<br/>
<form action="DragonTools.php#yield" method="post">
R [Torr/(AkeV)<sup>2</sup>]: <input type="text" name="R_2" size="7" value="<?php echo $R_2; ?>"/>
Beam energy [AkeV]: <input type="text" name="E_3" size="4" value="<?php echo $E_3; ?>"/>
Target pressure [Torr]: <input type="text" name="p_2" size="4" value="<?php echo $p_2; ?>"/><br/>
N<sub>elastic</sub>: <input type="text" name="N_2" size="7" value="<?php echo $N_2; ?>"/>
Recoils N<sub>recoil</sub>: <input type="text" name="Nr" size="7" value="<?php echo $Nr; ?>"/>
Charge state fraction [%]: <input type="text" name="cs" size="3" value="<?php echo $cs; ?>"/><br/>
	Efficiencies:<br/>
	through separator [%]: <input type="text" name="eff_1" size="3" value="<?php echo $eff_1; ?>"/>
	through MCPs [%]: <input type="text" name="eff_2" size="3" value="<?php echo $eff_2; ?>"/>
	BGOs [%]: <input type="text" name="eff_3" size="3" value="<?php echo $eff_3; ?>"/>
 <center>
<input class="submit" type="submit" value="calculate N, Y" name="Ycalc"/>
<input type="hidden" name="Q_2" size="2" value="<?php echo $Q_2; ?>"/>
<input type="hidden" name="t" size="6" value="<?php echo $t; ?>"/><br/>
<input type="hidden" name="FC" size="3" value="<?php echo $FC; ?>"/> 
<input type="hidden" name="Tr" size="3" value="<?php echo $Tr; ?>"/><br/>
<input type="hidden" name="E_2" size="4" value="<?php echo $E_2; ?>"/>
<input type="hidden" name="N" size="7" value="<?php echo $N; ?>"/><br/>
</center>
</form>
<br/>
<center>
<?php
	
	if ($Yc==1 && $Nr!=0 && $cs!=0 && $R_2!=0 && $N_2!=0 && $p_2!=0)
		{
			
			$eff = $eff_1/100 * $eff_2/100 * $eff_3/100;
			echo "efficiency = " . $eff . "<br/>";
			$Nb = $N_2*$R_2*$E_3*$E_3/$p_2;
			$Y = $Nr/($Nb*($cs/100)*$eff);
			printf("incident beam N<sub>beam</sub> = %.3e <br/>",$Nb);
			printf("Yield Y = %.3e<br/>",$Y);
		}
//else echo "calculate R, Y<br/>";
?>

</center>

    <hr/>
<center>
<small>Created by Ulrike</small> 
</center>
<!-- hhmts start -->
<!-- hhmts end -->
</div>
</body>
</html>
