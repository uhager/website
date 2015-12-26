<?php include("xhtmlNav.php") ?>

<div id="main">
 <!--[if !IE]>-->
      <object classid="java:TacticViewer.class" 
              type="application/x-java-applet"
              archive="FileTaclet.jar" 
              height="550" width="900" >
        <!-- Konqueror browser needs the following param -->
        <param name="archive" value="FileTaclet.jar" />
      <!--<![endif]-->
        <object classid="clsid:8AD9C840-044E-11D1-B3E9-00805F499D93" 
                height="550" width="900" > 
          <param name="code" value="TacticViewer.class" />
          <param name="archive" value="FileTaclet.jar" />
        </object> 
      <!--[if !IE]>-->
      </object>
      <!--<![endif]-->

 </div>
  </body>
</html>
