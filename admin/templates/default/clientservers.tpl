{if $logged_in}

    {include file="$template/header.tpl"}
    {include file="$template/navigation.tpl"}
    
    {* Location Links *}
    <span class="location">&nbsp;&nbsp;<a href="main.php">{$lang.admin_home}</a> / {$lang.clsrv_client_srv}</span>
    
    <br />
    
    {* Across-Page bar *}
    {include file="$template/bar.tpl"}
      
    <br /><br />
    
    <center>
      {if $type == 'game'}
          <img style="padding-bottom:8px" src="templates/{$template}/img/icons/game_server.png" name="icon_image" width="64" height="64" border="0"><br />
          {$lang.clsrv_displ_game_srv}
      {elseif $type == 'voice'}
          <img style="padding-bottom:8px" src="templates/{$template}/img/icons/voice_server.png" name="icon_image" width="64" height="64" border="0"><br />
          {$lang.clsrv_displ_voice_srv}
      {elseif $type == 'other'}
          {$lang.clsrv_displ_other_srv}
      {else}
          {$lang.clsrv_displ_all_srv}
      {/if}
    </center>
    
    
    <br /><br />
    
    
    {if $infobox}
    <table border="0" cellpadding="0" cellspacing="0" width="600" align="center" class="infobox">
      <tr>
        <td align="center"><br />{$infobox}<br /><br /></td>
      </tr>
    </table><br />
    {/if}
    
    <table border="0" cellpadding="2" cellspacing="0" width="95%" align="center" class="tablez">
      <tr class="table_title" height="20">
        <td align="left" width="170">{$lang.server}</td>
        <td align="left" width="90">{$lang.username}</td>
        <td align="left" width="150">{$lang.clsrv_conn_info}</td>
        <td align="left" width="160">{$lang.description}</td>
        <td align="center" width="60">{$lang.status}</td>
        <td align="center">&nbsp;</td>
      </tr>
  

    {if $settings}
        {section name=db loop=$settings}
          <tr title="View Game Server Info" class="normal" bgcolor={if $smarty.section.db.iteration is odd}#e1e1e1{else}#d7d7d7{/if} onClick="window.location='manageclientserver.php?id={$settings[db].id}'" onMouseOver="style.cursor='pointer' ; this.style.backgroundColor='#c3c3c3'" onMouseOut=style.cursor='auto';this.style.backgroundColor='{if $smarty.section.db.iteration is odd}#e1e1e1{else}#d7d7d7{/if}'>
            <td>{$settings[db].long_name|stripslashes}</td>
            <td align="left"><a href="manageclient.php?id={$settings[db].userid}" title="View User Info">{$settings[db].username|stripslashes|default:'<i>Unknown</i>'}</a></td>
            <td align="left"><font color="blue">{$settings[db].ip|default:'&nbsp;'}<b>:</b>{$settings[db].port|default:'&nbsp;'}</font></td>
            <td align="left">
            {if strlen($settings[db].description) > 20}
                {$settings[db].description|stripslashes|substr:0:20} <i>...</i>
            {else}
                {$settings[db].description|stripslashes}
            {/if}
            </td>
            <td align="center">
              {if $settings[db].current_status eq "online"}
                  <font color="green"><b>{$lang.status_online}</b></font>
              {elseif $settings[db].current_status eq "offline"}
                  <font color="red"><b>{$lang.status_offline}</b></font>
              {else}
                  <font color="orange"><b>{$lang.status_unknown}</b></font>
              {/if}
            </td>
            <td align="center"><a href="manageclientserver.php?id={$settings[db].id}">{$lang.edit}</a></td>
          </tr>
        {/section}
    {else}
      <tr>
        <td colspan="5" align="center">{$lang.clsrv_displ_no_srv}</td>
      </tr>
    {/if}
    </table>
    
    <br /><br />
    
    <table border="0" cellpadding="10" cellspacing="5" align="center" width="500" style="width:500px;table-layout:fixed;word-wrap:break-word;text-align:center">
      <tr align="center">
        <td align="center"><a href="addserver.php?type=game"><img style="padding-bottom:8px" src="templates/{$template}/img/icons/add_gameserver.png" name="icon_image" width="64" height="64" border="0"><br /><span class="icon_text">{$lang.clsrv_create_srv}</span></a></td>
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
