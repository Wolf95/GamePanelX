{if $logged_in}

    {include file="$template/header.tpl"}
    {include file="$template/navigation.tpl"}

    {* Location Links *}
    <span class="location">&nbsp;&nbsp;<a href="main.php">{$lang.admin_home}</a> / <a href="clients.php">{$lang.clients_nav_client_accounts}</a> / {$lang.manageclient_nav_manage_client}</span>
    
    <br />
    
    {* Across-Page bar *}
    {include file="$template/bar.tpl"}
      
      
    <br /><br />
    
    {section name=db loop=$user_details}
    
    
    
    {literal}
    <script type="text/javascript">
    <!--
    function confirmation()
    {
        var answer = confirm("{/literal}{$lang.manageclient_confirm_delete}{literal}")
        if (answer)
        {
            {/literal}
            window.location = "manageclient.php?action=delete&id={$user_details[db].id}"
            {literal}
        }
    }
    //-->
    </script>
    {/literal}
    
    <center>{$lang.manageclient_header}</b></center>
    
    <br />

    <table border="0" cellpadding="1" cellspacing="0" width="600" align="center" class="tablez">
      <tr class="table_title" height="20">
        <td align="center" colspan="2"><span style="font-weight:normal">{$lang.manageclient_title}: </span>{$user_details[db].username|default:'&nbsp;'}</td>
      </tr>
      
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>

      <tr>
        <td align="right" width="40%"><b>{$lang.username}:</b>&nbsp;</td>
        <td align="left"><b>{$user_details[db].username|default:'&nbsp;'}</b></td>
      </tr>
      
      <tr>
        <td align="right" width="40%"><b>{$lang.date_added}:</b>&nbsp;</td>
        <td align="left">{$user_details[db].date_added|default:'&nbsp;'}</td>
      </tr>
      
      <tr>
        <td align="right" width="40%"><b>{$lang.status}:</b>&nbsp;</td>
        <td align="left">
          {if $user_details[db].status eq "active"}
              <font color=green><b>{$lang.status_active}</b></font>
          {elseif $user_details[db].status eq "suspended"}
              <font color=red><b>{$lang.status_suspended}</b></font>
          {elseif $user_details[db].status eq "closed"}
              <font color=maroon><b>{$lang.status_closed}</b></font>
          {else}
              <font color=orange><b>{$lang.status_unknown}</b></font>
          {/if}
        </td>
      </tr>

      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      
      <tr>
        <td align="right" width="40%"><b>{$lang.full_name}:</b>&nbsp;</td>
        <td align="left">{$user_details[db].first_name} {$user_details[db].middle_name} {$user_details[db].last_name}</td>
      </tr>
      <tr>
        <td align="right" width="40%"><b>{$lang.email}:</b>&nbsp;</td>
        <td align="left"><a href="mailto:{$user_details[db].email_address}">{$user_details[db].email_address}</a></td>
      </tr>
      <tr>
        <td align="right" width="40%"><b>{$lang.phone}:</b>&nbsp;</td>
        <td align="left">{$user_details[db].phone_number|default:'&nbsp;'}</td>
      </tr>
      <tr>
        <td align="right" width="40%"><b>{$lang.company}:</b>&nbsp;</td>
        <td align="left">{$user_details[db].company|default:'&nbsp;'}</td>
      </tr>
      <tr>
        <td align="right" width="40%"><b>{$lang.location}:</b>&nbsp;</td>
        <td align="left">{if $user_details[db].city && $user_details[db].state}{$user_details[db].city}, {$user_details[db].state} {/if}{$user_details[db].country} {$user_details[db].zip_code}</td>
      </tr>
      <tr>
        <td align="right" width="40%"><b>{$lang.client_notes}:</b>&nbsp;</td>
        <td align="left">
          <i>
            {if strlen($user_details[db].notes) > 35}
                {$user_details[db].notes|stripslashes|substr:0:35} ...
            {else}
                {$user_details[db].notes|stripslashes}
            {/if}
          </i>
        </td>
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
        <td align="center"><a href="editclient.php?id={$user_details[db].id}"><img style="padding-bottom:8px" src="templates/{$template}/img/icons/edituser.png" name="icon_image" width="64" height="64" border="0"><br /><span class="icon_text">{$lang.manageclient_icon_edit_details}</span></a></td>
        <td align="center"><a href="servers.php?id={$user_details[db].id}&type=game"><img style="padding-bottom:8px" src="templates/{$template}/img/icons/game_server.png" name="icon_image" width="64" height="64" border="0"><br /><span class="icon_text">{$lang.manageclient_icon_game_servers}</span></a></td>
        <td align="center"><a href="servers.php?id={$user_details[db].id}&type=voice"><img style="padding-bottom:8px" src="templates/{$template}/img/icons/voice_server.png" name="icon_image" width="64" height="64" border="0"><br /><span class="icon_text">{$lang.manageclient_icon_voice_servers}</span></a></td>
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
                        <a href="{$icons[page].href}?id={$user_details[db].id}"><img style="padding-bottom:8px" src="templates/{$template}/img/icons/{$icons[page].image}" name="icon_image" width="64" height="64" border="0"><br /><span class="icon_text">{$icons[page].image_text}</a></span>
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
