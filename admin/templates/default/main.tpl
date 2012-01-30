{if $logged_in}

    {include file="$template/header.tpl"}
    {include file="$template/navigation.tpl"}
    
    {* Location Links *}
    <span class="location">&nbsp;&nbsp;Admin Home</span>
    
    <br />
    
    
    <table border="0" cellpadding="10" cellspacing="5" align="center" width="500" style="width:500px;table-layout:fixed;word-wrap:break-word;text-align:center">
      <tr align="center">
        <td align="center"><a href="servers.php?type=game"><img style="padding-bottom:8px" src="templates/{$template}/img/icons/game-64px.png" name="icon_image" width="64" height="64" border="0"><br /><span class="icon_text">{$lang.main_game_servers}</span></a></td>
        <td align="center"><a href="servers.php?type=voip"><img style="padding-bottom:8px" src="templates/{$template}/img/icons/voip-64px.png" name="icon_image" width="64" height="64" border="0"><br /><span class="icon_text">{$lang.main_voice_servers}</span></a></td>
        <td align="center"><a href="clients.php"><img style="padding-bottom:8px" src="templates/{$template}/img/icons/clients-64px.png" name="icon_image" width="64" height="64" border="0"><br /><span class="icon_text">{$lang.main_clients}</span></a></td>
        <td align="center"><a href="admins.php"><img style="padding-bottom:8px" src="templates/{$template}/img/icons/admin-64px.png" name="icon_image" width="64" height="64" border="0"><br /><span class="icon_text">{$lang.main_admins}</span></a></td>
        <td align="center"><a href="tickets.php"><img style="padding-bottom:8px" src="templates/{$template}/img/icons/tickets_64.png" name="icon_image" width="64" height="64" border="0"><br /><span class="icon_text">{$lang.main_tickets}</span></a></td>
      </tr>
      <tr align="center">
        <td align="center"><a href="configuration.php"><img style="padding-bottom:8px" src="templates/{$template}/img/icons/server.png" name="icon_image" width="64" height="64" border="0"><br /><span class="icon_text">{$lang.main_gp_settings}</span></a></td>
        <td align="center"><a href="archives.php"><img style="padding-bottom:8px" src="templates/{$template}/img/icons/template_64.png" name="icon_image" width="64" height="64" border="0"><br /><span class="icon_text">{$lang.main_templates}</span></a></td>
        <td align="center"><a href="defaultservers.php"><img style="padding-bottom:8px" src="templates/{$template}/img/icons/supported_games-64px.png" name="icon_image" width="64" height="64" border="0"><br /><span class="icon_text">{$lang.suppsrv_icon_default_srv}</span></a></td>
        <td align="center"><a href="network.php"><img style="padding-bottom:8px" src="templates/{$template}/img/icons/network2.png" name="icon_image" width="64" height="64" border="0"><br /><span class="icon_text">{$lang.main_network}</span></a></td>
        <td align="center"><a href="editadmin.php?id={$userid}"><img style="padding-bottom:8px" src="templates/{$template}/img/icons/admin-64px.png" name="icon_image" width="64" height="64" border="0"><br /><span class="icon_text">{$lang.main_settings}</span></a></td>
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
                        <a href="{$icons[page].href}"><img style="padding-bottom:8px" src="templates/{$template}/img/icons/{$icons[page].image}" name="icon_image" width="64" height="64" border="0"><br /><span class="icon_text">{$icons[page].image_text}</a></span>
                    </td>
                  </tr>
                </table>
            {/section}
        </td>
      </tr>
    </table>

    {include file="$template/footer.tpl"}

{/if}
