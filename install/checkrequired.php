<html>
<head>
<title>GamePanelX Pro | Install</title>
<link rel="stylesheet" type="text/css" href="css/style.css" />
<script type="text/javascript" src="scripts/jquery.js"></script>
</head>

<body bgcolor="#F1F1F1" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">

<table border="0" cellpadding="0" cellspacing="0" align="center" width="750" style="border-left:1px solid #999999;border-right:1px solid #999999;border-bottom:1px solid black">
  <tr>
    <td align="center" height="30" colspan="6" background="img/logo_grad.png"><img src="img/logo.png"></td>
  </tr>
</table>


<table border="0" cellpadding="0" cellspacing="0" align="center" width="750" style="border-left:1px solid #999999;border-right:1px solid #999999;border-bottom:1px solid #999999">
  <tr>
    <td valign="top" class="center_table" colspan="6" style="border-top:1px solid black">


    <table border="0" cellpadding="0" cellspacing="0" align="center" width="100%" height="20">
      <tr align="center" class="navigation">
        <td align="left">&nbsp;</td>
        <td align="right">&nbsp;</td>
      </tr>
    </table>

<span class="location">&nbsp;&nbsp;<a href="main.php">Home</a> / Installation / Check Required</span>

<br />

<!-- Page Bar -->
<table border="0" cellpadding="2" cellspacing="0" width="100%" align="center" class="tablez">
  <tr class="table_title" height="20">
    <td align="left">&nbsp;</td>
  </tr>
</table>
<!-- /Page Bar -->
  
  
<br /><br />


<form action="index.php" method="post" name="reqcheck" id="reqcheck">
<table border="0" cellpadding="1" cellspacing="0" width="600" align="center" class="tablez">
  <tr class="table_title" height="20">
    <td align="center" colspan="2">Required Settings Check</td>
  </tr>
  
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>


  <tr>
    <td colspan="2" align="center"><b>Check System Requirements:</b></td>
  </tr>
  
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  
  
  <tr>
    <td valign="top" width="160" style="padding-left:5px" class="description">PHP Version 5 or newer:&nbsp;</td>
    <td>
        <?php
        //
        // Set the failed variable for later use:
        //
        $failed = "";
        
        
        // PHP Version
        $php_version  = phpversion();
        if($php_version < 5)
        {
            $failed=1;
            echo '<span style="font-weight:bold;color:red">Error:</span> Your PHP installation (version <b>' . $php_version . '</b>) is too old.  You must run at least <b>PHP 5</b> or newer.';
        }
        else
        {
            echo '<span style="color:green">Passed</span>';
        }
        ?>
    </td>
  </tr>
  <tr>
    <td valign="top" style="padding-left:5px" class="description">MySQL:&nbsp;</td>
    <td>
        <?php
        // Check for MySQL support
        if(!function_exists('mysql_connect'))
        {
            $failed=1;
            echo '<span style="font-weight:bold;color:red">Error:</span> You must have MySQL support built into PHP.  You can set it during PHP installation with "<i>--with-mysql</i>", or on Cpanel servers, enable it using the EasyApache feature in the PHP section.';
        }
        else
        {
            echo '<span style="color:green">Passed</span>';
        }
        ?>
    </td>
  </tr>
  
  <tr>
    <td valign="top" style="padding-left:5px" class="description">INI Files Support:&nbsp;</td>
    <td>
        <?php
        // Check for INI File support
        if(!function_exists('parse_ini_file'))
        {
            $failed=1;
            echo '<span style="font-weight:bold;color:red">Error:</span> You must have INI file parsing support in PHP (Function: <i>parse_ini_file</i>).';
        }
        else
        {
            echo '<span style="color:green">Passed</span>';
        }
        ?>
    </td>
  </tr>





  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  
  <tr>
    <td colspan="2" align="center"><b>Check Permissions:</b></td>
  </tr>
  
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  
  <tr>
    <td valign="top" style="padding-left:5px" class="description">DB File writable:</td>
    <td>
        <?php
        // Check if writable
        if(!is_writable('../include/db.php'))
        {
            $failed=1;
            echo '<span style="font-weight:bold;color:red">Error:</span> The "include/db.php" file must exist and be writable by the webserver.  Rename "include/db.php.new" to "include/db.php" and try again.  Otherwise, run <b>chmod 777 include/db.php</b>.';
        }
        else
        {
            echo '<span style="color:green">Passed</span>';
        }
        ?>
    </td>
  </tr>
  <tr>
    <td valign="top" style="padding-left:5px" class="description">User Templates folder:</td>
    <td>
        <?php
        // Check if writable
        if(!is_writable('../templates_c'))
        {
            $failed=1;
            echo '<span style="font-weight:bold;color:red">Error:</span> The "templates_c" directory must be writable by the webserver.  You should run <b>chmod 777 templates_c</b> from the root GamePanelX Pro directory.';
        }
        else
        {
            echo '<span style="color:green">Passed</span>';
        }
        ?>
    </td>
  </tr>
  <tr>
    <td valign="top" style="padding-left:5px" class="description">Admin Templates folder:</td>
    <td>
        <?php
        // Check if writable
        if(!is_writable('../admin/templates_c'))
        {
            $failed=1;
            echo '<span style="font-weight:bold;color:red">Error:</span> The "admin/templates_c" directory must be writable by the webserver.  You should run <b>chmod 777 admin/templates_c</b> from the root GamePanelX Pro directory.';
        }
        else
        {
            echo '<span style="color:green">Passed</span>';
        }
        ?>
    </td>
  </tr>
  <tr>
    <td valign="top" style="padding-left:5px" class="description">Temporary folder:</td>
    <td>
        <?php
        // Check if writable
        if(!is_writable('../tmp'))
        {
            $failed=1;
            echo '<span style="font-weight:bold;color:red">Error:</span> The "tmp" directory must be writable by the webserver.  You should run <b>chmod 777 tmp</b> from the root GamePanelX Pro directory.';
        }
        else
        {
            echo '<span style="color:green">Passed</span>';
        }
        ?>
    </td>
  </tr>
  
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  
  <tr>
    <td colspan="6" align="center">
    
    <?php
    // Failure
    if($failed)
    {
        echo 'The install cannot continue until you have met the installation requirements.';
    }
    else
    {
        echo '<input type="submit" name="checkreq" id="checkreq" value="Continue" style="width:170px" />';
    }
    ?>
    </td>
  </tr>
  
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
</table>





</td>
  </tr>
  
  <tr class="center_table">
    <td colspan="6">&nbsp;</td>
  </tr>
  
  <tr>
    <td align="center" height="20" valign="middle" class="footer_text" colspan="6">&copy; GamePanelX Pro 2010-2011</td>
  </tr>
  
</table>

</body>
</html>

