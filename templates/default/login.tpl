{include file="$template/header.tpl"}

<br /><br />

<form method="post" action="login.php">
<table border="0" cellpadding="1" cellspacing="0" align="center" width="400" class="tablez">
  <tr class="table_title">
    <td align="center" height="20" colspan="2">{$lang.login_title}</td>
  </tr>
  
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  
  {if $error}
    <tr>
      <td colspan="2" align="center">{$error}</td>
    </tr>
    <tr>
      <td colspan="2">&nbsp;</td>
    </tr>
  {/if}
  
  <tr>
    <td align="right" width="40%" style="padding-right:10px">{$lang.login_username}:</td>
    <td align="left"><input type="text" name="gpx_username" class="textbox_important"></td>
  </tr>
  <tr>
    <td align="right" width="30%" style="padding-right:10px">{$lang.login_password}:</td>
    <td align="left"><input type="password" name="gpx_password" class="textbox_important"></td>
  </tr>
  
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  
  <tr>
    <td colspan="2" align="center"><input type="submit" name="login" value=" " class="button_login" /></td>
  </tr>
  
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
</table>
</form>

{include file="$template/footer.tpl"}
