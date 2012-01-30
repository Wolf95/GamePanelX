{if $logged_in}

    {include file="$template/header.tpl"}
    {include file="$template/navigation.tpl"}

    {* Location Links *}
    <span class="location">&nbsp;&nbsp;<a href="main.php">{$lang.admin_home}</a> / <a href="network.php">{$lang.network}</a> / {$lang.managenetsrv_title}
    
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
        var answer = confirm("{/literal}{$lang.managenetsrv_confirm_delete}{literal}")
        if (answer)
        {
            {/literal}
            window.location = "managenetworkserver.php?action=delete&id={$server_details[db].id}"
            {literal}
        }
    }
    //-->
    </script>
    {/literal}
    
    <center>{$lang.managenetsrv_details}</b></center>
    
    <br />

    <table border="0" cellpadding="1" cellspacing="0" width="600" align="center" class="tablez">
      <tr class="table_title" height="20">
        <td align="center" colspan="2"><span style="font-weight:normal">{$server_details[db].ip}</span></td>
      </tr>
      
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>

      <tr>
        <td align="right" width="40%"><b>{$lang.ip_address}:</b>&nbsp;</td>
        <td align="left"><b>{$server_details[db].ip}</b></td>
      </tr>
      
      <tr>
        <td align="right" width="40%"><b>{$lang.date_added}:</b>&nbsp;</td>
        <td align="left">{$server_details[db].date_added|default:'&nbsp;'}</td>
      </tr>
      
      <tr>
        <td align="right" width="40%"><b>{$lang.available}:</b>&nbsp;</td>
        <td align="left">
          {if $server_details[db].available eq "Y"}
              <font color=green><b>{$lang.yes}</b></font>
          {elseif $server_details[db].available eq "N"}
              <font color=red><b>{$lang.no}</b></font>
          {else}
              <font color=orange><b>{$lang.status_unknown}</b></font>
          {/if}
        </td>
      </tr>
      
      <tr>
        <td align="right" width="40%"><b>FTP Accounts Directory:</b>&nbsp;</td>
        <td align="left">
            {if $server_details[db].accounts_dir eq "invalid"}
                <font color=red>Invalid accounts directory;  FTP will not work.</font>
            {elseif $server_details[db].accounts_dir eq "valid"}
                <font color=green>Correct</font>
            {elseif $server_details[db].accounts_dir eq "unknown"}
                <font color=orange>Unknown</font>
            {/if}
        </td>
      </tr>
      
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      
      <tr>
        <td align="right" width="40%"><b>{$lang.description}:</b>&nbsp;</td>
        <td align="left">{$server_details[db].description|stripslashes}</td>
      </tr>
      <tr>
        <td align="right" width="40%"><b>{$lang.location}:</b>&nbsp;</td>
        <td align="left">{$server_details[db].location|stripslashes}</td>
      </tr>
      <tr>
        <td align="right" width="40%"><b>{$lang.datacenter}:</b>&nbsp;</td>
        <td align="left">{$server_details[db].datacenter|stripslashes}</td>
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
        <td align="center"><a href="editnetworkserver.php?id={$server_details[db].id}"><img style="padding-bottom:8px" src="templates/{$template}/img/icons/network.png" name="icon_image" width="64" height="64" border="0"><br /><span class="icon_text">{$lang.managenetsrv_edit_netsrv}</span></a></td>
        <td align="center"><a href="remoteinfo.php?id={$server_details[db].id}"><img style="padding-bottom:8px" src="templates/{$template}/img/icons/info.png" name="icon_image" width="64" height="64" border="0"><br /><span class="icon_text">{$lang.managenetsrv_remote_info}</span></a></td>
      </tr>
    </table>
    
    {* All icons for this page and theme *}
    <table border="0" cellpadding="0" cellspacing="0" align="center">
      <tr>
        <td>
            {section name=page loop=$icons}
                <table border="0" cellpadding="12" cellspacing="5" align="left">
                  <tr>
                    <td align="center">
                        <a href="{$icons[page].href}?id={$server_details[db].id}"><img style="padding-bottom:8px" src="templates/{$template}/img/icons/{$icons[page].image}" name="icon_image" width="64" height="64" border="0"><br /><span class="icon_text">{$icons[page].image_text}</a></span>
                    </td>
                  </tr>
                </table>
            {/section}
        </td>
      </tr>
    </table>
    {/section}
      


    {include file="$template/footer.tpl"}

{/if}
