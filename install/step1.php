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

<span class="location">&nbsp;&nbsp;<a href="main.php">Home</a> / Installation / Step 1</span>

<br />

<!-- Page Bar -->
<table border="0" cellpadding="2" cellspacing="0" width="100%" align="center" class="tablez">
  <tr class="table_title" height="20">
    <td align="left">&nbsp;</td>
  </tr>
</table>
<!-- /Page Bar -->
  
  
<br /><br />


<script type="text/javascript" src="scripts/validate.js"></script>
<script type="text/javascript"> 
$(document).ready(function() { 
    var validator = $("#step1").validate({ 
        rules: { 
            install_dir: "required", 
            db_host: "required",
            db_name: "required",
            db_user: "required",
            db_pass: "required",
            admin_user: "required",
            admin_email: {
              required: true,
              email: true,
            },
            admin_pass: "required"
        }, 
        messages: { 
            install_dir: "<br /><font color=red>Please enter an install directory</font>", 
            db_host: "<br /><font color=red>Please enter a Database Host</font>",
            db_name: "<br /><font color=red>Please enter a Database Name</font>",
            db_user: "<br /><font color=red>Please enter a Database User</font>",
            db_pass: "<br /><font color=red>Please enter a Database Password</font>",
            admin_user: "<br /><font color=red>Please enter an Admin Username</font>",
            admin_pass: "<br /><font color=red>Please enter an Admin Password</font>",
            admin_email: "<br /><font color=red>Please enter a valid Admin Email Address</font>" 
        }, 
        errorPlacement: function(error, element) { 
            if ( element.is(":radio") ) 
                error.appendTo( element.parent().next().next() ); 
            else if ( element.is(":checkbox") ) 
                error.appendTo ( element.next() ); 
            else 
                //error.appendTo( element.parent().next() );
                error.insertAfter(element);
        },
        success: function(label) {
            label.html(" ").addClass("checked");
        }
    });
});
</script> 

<form action="index.php" method="post" name="step1" id="step1">
<table border="0" cellpadding="1" cellspacing="0" width="450" align="center" class="tablez">
  <tr class="table_title" height="20">
    <td align="center" colspan="2">Step 1 - Installation Settings</td>
  </tr>
  
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>

  <tr>
    <td align="right" class="description">Install Directory:&nbsp;</td>
    <td><input type="text" name="install_dir" id="install_dir" class="textbox_normal" style="width:220px" value="<?php echo $current_dir; ?>" /></td>
  </tr>

  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  
  <tr class="table_title" style="height:20px">
    <td align="center" colspan="2"><b>Database Settings</b></td>
  </tr>
  
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  
  <tr>
    <td align="right" class="description">Database Host:&nbsp;</td>
    <td><input type="text" name="db_host" id="db_host" class="textbox_normal" style="width:220px" value="localhost" /></td>
  </tr>
  <tr>
    <td align="right" class="description">Database Name:&nbsp;</td>
    <td><input type="text" name="db_name" id="db_name" class="textbox_normal" style="width:220px" /></td>
  </tr>
  <tr>
    <td align="right" class="description">Database Username:&nbsp;</td>
    <td><input type="text" name="db_user" id="db_user" class="textbox_normal" style="width:220px" /></td>
  </tr>
  <tr>
    <td align="right" class="description">Database Password:&nbsp;</td>
    <td><input type="password" name="db_pass" id="db_pass" class="textbox_normal" style="width:220px" /></td>
  </tr>
  
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>

  <tr class="table_title" style="height:20px">
    <td align="center" colspan="2"><b>Administrator User</b></td>
  </tr>
  
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>

  
  <tr>
    <td align="right" class="description">Admin Username:&nbsp;</td>
    <td><input type="text" name="admin_user" id="admin_user" class="textbox_normal" style="width:220px" /></td>
  </tr>
  <tr>
    <td align="right" class="description">Admin Password:&nbsp;</td>
    <td><input type="password" name="admin_pass" id="admin_pass" class="textbox_normal" style="width:220px" /></td>
  </tr>
  <tr>
    <td align="right" class="description">Admin Email Address:&nbsp;</td>
    <td><input type="text" name="admin_email" id="admin_email" class="textbox_normal" style="width:220px" /></td>
  </tr>
  
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  
  <tr class="table_title" style="height:20px">
    <td align="center" colspan="2"><b>Miscellaneous</b></td>
  </tr>
  
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  
  <tr>
    <td align="right" class="description">Language:&nbsp;</td>
    <td>
        <select name="language" style="width:170px">
        <?php
        // Get list of languages
        $dir = '../languages/';
        if(is_dir($dir))
        {
            if ($dh = opendir($dir))
            {
                while (($file = readdir($dh)) !== false)
                {
                    if(preg_match("/\.php$/", $file) && $file != '.' && $file != '..' && $file != 'index.php')
                    {
                        $lang_name  = strtolower(str_replace('.php', '', $file));
                        
                        if($lang_name == 'english')
                        {
                            echo '<option value="' . $lang_name . '" selected>' . ucwords($lang_name) . '</option>';
                        }
                        else
                        {
                            echo '<option value="' . $lang_name . '">' . ucwords($lang_name) . '</option>';
                        }
                    }
                }
                closedir($dh);
            }
        }
        ?>
        </select>
      
    </td>
  </tr>

  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" align="center"><input type="submit" name="step1" id="step1" value="Continue" style="width:170px" /></td>
  </tr>
  
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
</table>
</form>

<br /><br />


</td>
  </tr>
  
  <tr class="center_table">
    <td colspan="6">&nbsp;</td>
  </tr>
  
  <tr>
    <td align="center" height="20" valign="middle" class="footer_text" colspan="6">&copy; GamePanelX Pro 2007-2012</td>
  </tr>
  
</table>

</body>
</html>

