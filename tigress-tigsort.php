<?php include("navigation.php") ?>


<div id="main">
    <h2>TigSortGUI analyser</h2>
<h3>1. Installation</h3>
<p>
Required:
</p>
<ul class="text"> 
   <li><a class="text" href="http://root.cern.ch">ROOT</a>, tested with versions 5.26, 5.30, 5.34 </li>
<li><a class="text" href="http://ladd00.triumf.ca/~olchansk/rootana/">ROOTANA</a></li>
<li>
<?php 
$filename = 'code-archives/TigSortGUI.tgz';
if (file_exists($filename)) {
  $path = explode("/",$filename);
  printf("<a class=\"text\" href=\"%s\">%s source tarball</a> (last updated %s).",$filename,$path[1], date ( "M d, Y, H:i (T)",filemtime($filename)));
 }
else printf("%s source tarball (which could not be found, contact me).",$path[1]);
?>
  Alternatively, you can download the source code from my <a class="text" href="https://github.com/uhager/TigSortGUI">github page</a>.
</li>
</ul>


<p>
Before installing ROOTANA, you need to set the 'ROOTSYS' environment variable, so ROOTANA will be installed with ROOT support, see <a class="text"  href="http://ladd00.triumf.ca/~olchansk/rootana/">http://ladd00.triumf.ca/~olchansk/rootana/</a>.  Then, you need to set the 'ROOTANA' environment variable to point to your ROOTANA installation. Example:
</p>
<pre class="quote">
export ROOTSYS=$PKGS/root_v5.30
export ROOTANA=$PKGS/rootana
export PATH=$ROOTSYS/bin:$PATH
export LD_LIBRARY_PATH=$ROOTSYS/lib:$ROOTANA:$LD_LIBRARY_PATH
</pre>
<p>
Un-tar the <a class="text" href="code-archives/TigSortGUI.tgz">TigSortGUI tarball</a>, go to the TigSortGUI directory, and type make. The executable is tigsortGUI, which you can now copy to somewhere that's in your path. 
</p>

<h3>2. Running</h3>
<p>
In the following, examples are given for analysis of data taken during experiments S1107 and S1201. I used runs 1693 and 1696 from S1107, and 1861 &ndash; 1864 for S1201 for no particular reason other than that those are relatively short runs. The required configuration files are included in the source tarball. 
</p>
<p class="centered">
<img src="images/TigSort_main_empty.png" width=400 alt="TigSort main window" style="padding:10px 0 10px "><br>
TigSortGUI main window
</p>

<h4>2.1. From Midas file</h4>
<ul class="text"> 
<li>load detector configuration: Menu 'Configuration' &rarr;  'DAQ addresses' &rarr; 'load from .tigsort file'  </li>
<li>select S1201.tigsort or S1107.tigsort</li>
<li>the trees that were defined in the input file can be selected in the drop down box above the list boxes</li>
<li>the defined detectors for the selected tree will show up in the left list box</li>
<li>load data files: Menu 'Data' &rarr; 'Select midas files'</li>
<li>choose MIDAS data files, this is multiple selection</li>
<li>it does not matter whether you use '.mid' or '.mid.bz2' files, ROOTANA handles both equally well</li>
</ul>
<p>
You can adjust the output file name in the text boxes on the top left: prefix - postfix - actual name of output file, prefix and postfix are used for the automatic file name generation but you can change the name directly in the third text box.<br>
The configuration file defines the TTree structure and the detectors with their channels and signals. The input must be separated by whitespace, empty lines and lines starting with '#' are ignored.<br>
Example configuration file:
</p>
<pre class="quote">
buffer     500
tree
        name    SharcEvents
        description     Sharc data
        detector
                name    DiBx1_F
                description     Si downstream box 1 front
                datatype        Charge
                signals
                        range 0 23  0x00800317  0x00800300
                end
        end
end
</pre>
<p>
Explanation:<br>
The indentation is purely for readability and has no function.<br>
<b>buffer</b> is the number of events that will be assembled before the event is further processed. This is not the number of MIDAS event fragments, but of actual events that are being assembled. The size of this buffer will depend on the trigger rate. The default value is 1000. You can check whether your buffer is sufficiently large but looking at the resulting root tree  (see below). Each tree contains the trigger event ID (from the DAQ) and an analyser event ID, i.e. the order in which the events were found in the MIDAS stream by the enalyser. If the analyser ID is larger than the trigger ID, some events were probably not completely assembled.  <br>
<b>tree</b>: the root TTree. You can sort your data into several trees. For example, in 'S1107.tigsort', the scaler events are in a separate tree from the SHARC events. <br>
<b>detector</b>: each detector in a tree must have a unique name. The signals can be given either as a range (channel_min  channel_max address_min address_max), or as individual 'channel - address' combinations. The channel numbers do not need to be consecutive, nor do they need to start with 0.<br>
<b>datatype</b>: there are different values that can be extracted for a detector. These are 
</p>
<ul class="text"> 
<li>Charge (default)</li>
<li>CFD</li>
<li>LED</li>
<li>WfEnergy
<ul class="text"> 
<li>needs additional parameters for calculation of baseline and peak</li>
<li>base1Min  base1Max base2Min  base2Max   peakMin  peakMax</li>
<li>the number of baseline bins must be equal to the number of peak bins to make sense</li>
</ul>
<li>WfPeak: needed parameters:  peakMin  peakMax</li>
<li>WfMinBin</li>
<li>WfMaxBin</li>
<li>Timestamp</li>
<li>TimestampUp</li>
<li>Lifetime</li>
<li>TriggersAccepted</li>
<li>TriggersRequested</li>
</ul>

<h4>2.2 From root file</h4>
<p>
TigSortGUI can also read back trees from root files it has previously generated. Thus, you can do the analysis stepwise, without each time re-running the MIDAS files.
</p>
<ul class="text"> 
<li>load detector configuration: Menu 'Configuration' &rarr;  'DAQ addresses' &rarr; 'load from .root file'  </li>
<li>select a root file that was previously created by TigSortGUI and contains the same tree structure as the files you want to analyse</li>
<li>the tree selection combo box lists the TTrees that were found in the selected root file</li>
<li>the left list box contains the TBranches belonging to the selected tree</li>
<li>load data files: Menu 'Data' &rarr; 'Select root files'</li>
<li>choose root data files, this is multiple selection, the data files must have the same tree structure as the file you selected for configuration</li>
</ul>
					  <p>Note: Reading scalers back does not currently work.</p>

<h4>2.3 Calibration</h4>
<p>
You can calibrate the energies of the detectors. Load the calibration input file from the menu: 'Configuration' &rarr; 'Calibration' or 'Formulas/Histograms' &rarr; 'load from .tigsort file'. 'S1201_gains.tigsort' contains calibration for some detectors. This file has a similar format as the input file in Section 2.1. The 'input' field specifies which detector to calibrate. The 'name' field can be omitted, if no name is given for the calibration, it will automatically be named detector_calib.

<h4>2.4 Adding sorting, formulas, cuts</h4>
<p>
There are several ways to sort and manipulate the data. You define what you want to do in input files similar to the configuration file used in Section 2.1. All objects in the same tree must have unique names (if a name is already taken, the new object will not be added, thus, if you make a mistake in an input file, you can fix it and reload the whole file, the stuff that was already loaded will be ignored. ).
<ul class="text"> 
<li>always requires 'input' field to denote which data to use.  <br>This must be the name of one (or more, depending on object) object that has already been defined</li>
<li>each detector has two sets of data: the channels, and the energies for the channels.  <br>If you give the detector name as input, both will be used.  <br>You can also add either "_channel" or "_value" to the detector name to access the one or the other.</li>
<li>sorter
<ul class="text"> 
<li>maxValue: returns the maximum value for a detector together with the channel </li>
<li>selectChannel: choose specific channels from a detector, returns both the channel and the energy</li>
<li>combine: combine the data from several detectors/other objects</li>
</ul></li>
<li>formula
<ul class="text"> 
<li>uses root's TFormula parser, equation must be in quotes</li>
<li>see the root <a class="text" href="http://root.cern.ch/root/html/TFormula.html">TFormula documentation</a> for details</li>
</ul></li>
<li>cut
<ul class="text"> 
<li>tcutg: loads root TCutG, first parameter is the file name, second the name of the cut</li>
<li>range cut: parameters: minimum  maximum</li>
<li>cuts can be added to objects by adding the keyword cut followed by the name of the cut. <br>Only events that passed the cut will be used by this object</li>
</ul></li>
</ul>
<p>
The input files are loaded from the menu: 'Configuration' &rarr; 'Formulas/Histograms'. This is multiple selection, just note that the objects are loaded in the correct order, i.e. that the inputs for an object are already defined when it is added. The objects that are added are also added to the list box with the detectors.<br>
The examples included in the source tarball are
</p>
<ul class="text"> 
<li>S1201
<ul class="text"> 
<li>formulas.tigsort</li>
<li>histos.tigsort</li>
</ul>
</li>
<li>S1107
<ul class="text"> 
<li>S1107-histos.tigsort</li>
</ul>
</li>
</ul>


<h4>2.5 Adding histograms</h4>
<p>
Adding histograms works the same way as adding sorters and formulas, they can be mixed in the same input file. The histograms are listed in the right-hand list box. The number of inputs defines whether the histogram is 1D or 2D. Beware that 2D histograms with large bin number take a lot of memory, and can cause the program to crash when trying to write the histograms to file.
</p>
<ul class="text"> 
<li>x/ybins parameters are:  number of bins - minimum - maximum</li>
<li>Waveforms
<ul class="text"> 
<li> there are two ways to define the address for a waveform histogram:								   
<ol class="text"> 
<li>the address in hexadecimal, similar to the detector signals</li>
<li>the input and channel of a detector</li>
</ol></li>
<li>only the most recent waveform is displayed</li>
</ul></li>
</ul>

<p class="centered">
<img src="images/TigSort_main_conf.png" width=400 alt="TigSort main window, configured" style="padding:10px 0 10px "><br>
TigSortGUI main window, detectors and histograms defined 
</p>

<h4>2.6 Fill root trees</h4>
<p>
Since the resulting root trees can get large, only either trees or histograms are filled. You can toggle between them using the radio buttons in the 'Fill trees or histograms' box. The default is to fill the trees. When filling the trees, you can also choose whether to create one output file for each selected input file, or whether to combine them all in one file (radio buttons in the 'RootTree output file' box). Especially for larger data files it is recommended to create separate files. These will be named using the prefix and postfix from the text boxes, and the run number.
</p>
<ul class="text"> 
<li>click 'Run' to start the analysis</li>
<li>the program will keep running until the last MIDAS file ends, or you click the 'Stop' button (formerly known as the 'Run' button)</li>
<li>the files with the trees are automatically written to disk (no overwrite warning!)</li>
</ul>
<p>
There is currently no way to write waveforms to a tree. This might change at some point.
You can choose which of the other detectors and modules you defined will be written to the tree on the tab with the tree name. Uncheck the 'Write' box for anythiing you don't want included.
</p>

<h4>2.7 Fill histograms</h4>
<ul class="text"> 
<li>set the 'Fill trees or histograms' to 'Fill Histograms'</li>
<li>Click 'Run' to start the analysis, 'Stop to end' </li>
<li>you can select the histogram you want displayed in the right-hand listbox</li>
<li>the histograms are not automatically written to file, click 'Save histos' to write them</li>
</ul>
<p>
Note that the bigger the histogram you are displaying, the slower the analysis; if you are impatient, unselect all histograms to fill them faster, then select one to check the progress.<br>
When filling histograms, you can also step through the data, rather than run it continuously. The analysis will run until one histogram is filled once, then stop. This is mainly useful to look at waveforms in detail.
</p>

<h4>2.8 Preferences</h4>
<p>
To avoid multithreading, the program has to occasionally interrupt the analysis to check for GUI interaction and update the currently displayed histogram. The frequencies with which those happen can be changed in the 'Preferences' menu. The numbers are in events before the GUI is checked/ the histogram updated.
</p>

<h3>3. TigSort (without GUI)</h3>
<p>
To quickly sort MIDAS data files into root trees, it is recommended to use the command line version: <br>
<?php 
$filename = 'code-archives/tigsort.tgz';
if (file_exists($filename)) {
  $path = explode("/",$filename);
   printf("<a class=\"text\" href=\"%s\">%s source tarball</a> (last updated %s).",$filename,$path[1], date ( "M d, Y, H:i (T)",filemtime($filename)));
 }
else printf("%s source tarball (which could not be found, contact me).",$filename);
?>
  Alternatively, you can download the source code from my <a class="text" href="https://github.com/uhager/TigSort">github page</a>.

<br>
It uses the same detector configuration files as the GUI version, described above in Section 2.1. Just run using
</p>
<pre class="quote">
tigsort -cConfigFile.tigsort runXYZ.mid
</pre>
<p>
or set the environment variable 'TIGSORT_INPUT'
</p>
<pre class="quote">
export TIGSORT_INPUT=/path/to/ConfigFile.tigsort
tigsort runXYZ.mid
</pre>
<p>
The output file will be asmXYZ.root.
</p>

<hr>
<div class="disclaimer"> Disclaimer <br>
  This program does not come with any warranties. This is an ongoing project, so functions and file formats may change at any time. If you find bugs, you may keep them (though please let me know where you found them). The program was developed and tested under Linux. It should also work under weirdo fringe operating systems like Windows or Mac OS, as long as you can install ROOT and ROOTANA, but this has not been checked, nor do I intend to check it. 
</div>

<!--
<ul class="text"> 
<li></li>
<li></li>
<li></li>
<li></li>
<li></li>
<li></li>
<li></li>
</ul>
-->
</div>
  </body>
</html>
