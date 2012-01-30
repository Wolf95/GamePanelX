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

<span class="location">&nbsp;&nbsp;<a href="main.php">Home</a> / Installation / Step 2</span>


<script type="text/javascript" src="scripts/validate.js"></script>
<script type="text/javascript"> 
$(document).ready(function() { 
    var validator = $("#step2").validate({ 
        rules: { 
            ip: "required", 
            conn_user: "required",
            conn_pass: "required",
            conn_port: "required" 
        }, 
        messages: { 
            ip: "<br /><font color=red>Please enter an IP Address</font>", 
            conn_user: "<br /><font color=red>Please enter a Connection User</font>",
            conn_pass: "<br /><font color=red>Please enter a Connection Password</font>",
            conn_port: "<br /><font color=red>Please enter a Connection Port</font>" 
        }, 
        errorPlacement: function(error, element) { 
            if ( element.is(":radio") ) 
                error.appendTo( element.parent().next().next() ); 
            else if ( element.is(":checkbox") ) 
                error.appendTo ( element.next() ); 
            else 
                error.insertAfter(element);
        },
        success: function(label) {
            label.html(" ").addClass("checked");
        }
    });
});
</script> 

<form action="index.php" method="post" name="step2" id="step2">
<table border="0" cellpadding="1" cellspacing="0" width="450" align="center" class="tablez">
  <tr class="table_title" height="20">
    <td align="center" colspan="2">Step 2 - Network Setup</td>
  </tr>

  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" align="center"><img src="img/icons/network.png" border="0" width="64" height="64" /></td>
  </tr>
  <tr>
    <td colspan="2" align="center">Enter your Remote Server's connection info here.<br />If you have more than one, you can set them up later.</td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  
  <tr>
    <td align="right" class="description">Operating System:&nbsp;</td>
    <td>
      <select name="os" class="dropdown" style="width:220px">
        <option value="linux" selected>Linux</option>
      </select>
    </td>
  </tr>
  
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  
  <tr>
    <td align="right" class="description">IP Address:&nbsp;</td>
    <td><input type="text" name="ip" id="ip" class="textbox_important" style="width:220px" /></td>
  </tr>
  
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>

  <tr>
    <td align="right" class="description">Description:&nbsp;</td>
    <td><input type="text" name="description" id="description" class="textbox_normal" style="width:220px" /></td>
  </tr>
  <tr>
    <td align="right" class="description">Location:&nbsp;</td>
    <td><input type="text" name="location" id="location" class="textbox_normal" style="width:220px" /></td>
  </tr>
  <tr>
    <td align="right" class="description">Datacenter:&nbsp;</td>
    <td><input type="text" name="datacenter" id="datacenter" class="textbox_normal" style="width:220px" /></td>
  </tr>


  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  
  <tr class="table_title" style="height:20px">
    <td align="center" colspan="2"><b>Connection Settings</b></td>
  </tr>

  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" align="center"><img src="img/icons/secure.png" border="0" width="64" height="64" /></td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  
  <tr>
    <td align="right" class="description">Connection Username:&nbsp;</td>
    <td><input type="text" name="conn_user" id="conn_user" class="textbox_important" style="width:220px" /></td>
  </tr>
  <tr>
    <td align="right" class="description">Connection Password:&nbsp;</td>
    <td><input type="password" name="conn_pass" id="conn_pass" class="textbox_important" style="width:220px" /></td>
  </tr>
  <tr>
    <td align="right" class="description">Connection Port:&nbsp;</td>
    <td><input type="text" name="conn_port" id="conn_port" class="textbox_important" style="width:220px" value="22" /></td>
  </tr>

  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  
  <tr>
    <td colspan="2" align="center"><input type="submit" name="step2" id="step2" value="Finish" style="width:170px" /></td>
  </tr>
  
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
</table>
<input type="hidden" name="install_dir" value="<?php echo $post_install_dir; ?>" />
<input type="hidden" name="db_host" value="<?php echo $post_db_host; ?>" />
<input type="hidden" name="db_name" value="<?php echo $post_db_name; ?>" />
<input type="hidden" name="db_user" value="<?php echo $post_db_user; ?>" />
<input type="hidden" name="db_pass" value="<?php echo $post_db_pass; ?>" />
<input type="hidden" name="admin_user" value="<?php echo $post_admin_user; ?>" />
<input type="hidden" name="admin_pass" value="<?php echo $post_admin_pass; ?>" />
<input type="hidden" name="admin_email" value="<?php echo $post_admin_email; ?>" />
<input type="hidden" name="language" value="<?php echo $post_language; ?>" />
</form>

<br />

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
