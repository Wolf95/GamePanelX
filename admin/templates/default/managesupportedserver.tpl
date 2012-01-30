{if $logged_in}

    {include file="$template/header.tpl"}
    {include file="$template/navigation.tpl"}

    {* Location Links *}
    <span class="location">&nbsp;&nbsp;<a href="main.php">{$lang.admin_home}</a> / <a href="defaultservers.php">{$lang.suppsrv_title}</a> / {$lang.managesuppsrv_title}</span>
    
    <br />
    
    {* Across-Page bar *}
    {include file="$template/bar.tpl"}
      
      
    <br /><br />
    
    {section name=db loop=$server_details}
    
    
    
    {literal}
    <script type="text/javascript">
    <!--
    function confirmation()
    {
        var answer = confirm("{/literal}{$lang.managesuppsrv_confirm_delete}{literal}")
        if (answer)
        {
            {/literal}
            window.location = "managesupportedserver.php?action=delete&id={$server_details[db].id}"
            {literal}
        }
    }
    //-->
    </script>
    {/literal}
    
    <br />

    <table border="0" cellpadding="1" cellspacing="0" width="600" align="center">
      <tr height="20">
        <td align="center" colspan="2">{$server_details[db].long_name|stripslashes|default:'&nbsp;'}</td>
      </tr>
      
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      
      <tr>
        <td colspan="2" align="center"><img src="templates/{$template}/img/servers/medium/{$server_details[db].short_name|default:'default'}.png" width="64" height="64" border="0" /></td>
      </tr>

      <tr>
        <td align="right" width="40%"><b>{$lang.managesuppsrv_full_name}:</b>&nbsp;</td>
        <td align="left">{$server_details[db].long_name|default:'&nbsp;'}</td>
      </tr>
      <tr>
        <td align="right" width="40%"><b>{$lang.managesuppsrv_short_name}:</b>&nbsp;</td>
        <td align="left">{$server_details[db].short_name|stripslashes|default:'&nbsp;'}</td>
      </tr>
      <tr>
        <td align="right" width="40%"><b>{$lang.type}:</b>&nbsp;</td>
        <td align="left">{$server_details[db].type|ucwords|default:'&nbsp;'}</td>
      </tr>
      <tr>
        <td align="right" width="40%"><b>{$lang.available}:</b>&nbsp;</td>
        <td align="left">
          {if $server_details[db].available eq "Y"}
            <font color="green"><b>{$lang.yes}</b></font>
          {else}
            <font color="orange"><b>{$lang.no}</b></font>
          {/if}
        </td>
      </tr>
      <tr>
        <td align="right" width="40%"><b>{$lang.description}:</b>&nbsp;</td>
        <td align="left">{$server_details[db].description|stripslashes|default:'&nbsp;'}</td>
      </tr>
      <tr>
        <td align="right" width="40%"><b>{$lang.notes}:</b>&nbsp;</td>
        <td align="left">{$server_details[db].notes|stripslashes|default:'&nbsp;'}</td>
      </tr>

      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      
      <tr>
        <td colspan="2" align="center"><input type="submit" name="confirm" id="confirm" value=" " onClick="javascript:confirmation()" class="button_delete"></td>
      </tr>
      
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
    </table>
    
    <br /><br />
    
    <table border="0" cellpadding="10" cellspacing="5" align="center" width="500" style="width:500px;table-layout:fixed;word-wrap:break-word;text-align:center">
      <tr align="center">
        <td align="center"><a href="editsupportedserver.php?id={$server_details[db].id}"><img style="padding-bottom:8px" src="templates/{$template}/img/icons/edituser.png" name="icon_image" width="64" height="64" border="0"><br /><span class="icon_text">{$lang.managesuppsrv_icon_edit_suppsrv}</span></a></td>
        
        <td align="center"><a href="editsupportedcmdline.php?id={$server_details[db].id}"><img style="padding-bottom:8px" src="templates/{$template}/img/icons/edit_cmdline-64px.png" name="icon_image" width="64" height="64" border="0"><br /><span class="icon_text">{$lang.managesuppsrv_icon_edit_cmd}</span></a></td>
        
        <td align="center"><a href="xmlexport.php?id={$server_details[db].id}"><img style="padding-bottom:8px" src="templates/{$template}/img/icons/xmlfile.png" name="icon_image" width="64" height="64" border="0"><br /><span class="icon_text">Export to XML</span></a></td>
      </tr>
    </table>
    
    {/section}
      


    {include file="$template/footer.tpl"}

{/if}
