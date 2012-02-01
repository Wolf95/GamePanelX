{if $logged_in}

    {include file="$template/header.tpl"}
    {include file="$template/navigation.tpl"}
        
    <center>
    <b>
      {if $type == 'game'}
          <img style="padding-bottom:8px" src="templates/{$template}/img/icons/game-64px.png" name="icon_image" width="64" height="64" border="0"><br />
          {$lang.clsrv_displ_game_srv}
      {elseif $type == 'voip'}
          <img style="padding-bottom:8px" src="templates/{$template}/img/icons/voice_server.png" name="icon_image" width="64" height="64" border="0"><br />
          {$lang.clsrv_displ_voice_srv}
      {elseif $type == 'other'}
          {$lang.clsrv_displ_other_srv}
      {else}
          {$lang.clsrv_displ_all_srv}
      {/if}
    </b>
    </center>
    
    
    <br /><br />
    
    
    {if $infobox}
    <table border="0" cellpadding="0" cellspacing="0" width="600" align="center" class="infobox">
      <tr>
        <td align="center"><br />{$infobox}<br /><br /></td>
      </tr>
    </table><br />
    {/if}
    
    <div align="center">
        <div id="srv_action_result"></div>
    </div>
    
    <table border="0" cellpadding="2" cellspacing="0" width="95%" align="center" class="tablez" style="border-radius:6px;">
      <tr class="table_title" height="30">
        <td align="left" width="20">&nbsp;</td>
        <td align="left" width="170"><a href="servers.php?type={$type}&order=server&sort={if $s_sort eq 'asc'}desc{else}asc{/if}&p={$page}" style="text-decoration:none;color:#FFF;">{$lang.server}</a></td>
        <td align="left" width="90"><a href="servers.php?type={$type}&order=username&sort={if $s_sort eq 'asc'}desc{else}asc{/if}&p={$page}" style="text-decoration:none;color:#FFF;">{$lang.username}</a></td>
        <td align="left" width="150"><a href="servers.php?type={$type}&order=info&sort={if $s_sort eq 'asc'}desc{else}asc{/if}&p={$page}" style="text-decoration:none;color:#FFF;">{$lang.clsrv_conn_info}</a></td>
        <td align="left" width="130"><a href="servers.php?type={$type}&order=description&sort={if $s_sort eq 'asc'}desc{else}asc{/if}&p={$page}" style="text-decoration:none;color:#FFF;">{$lang.description}</a></td>
        <td align="center" width="60">{$lang.status}</td>
        <td align="center" width="35">&nbsp;</td>
        <td align="center" width="40">&nbsp;</td>
      </tr>
  

    {if $settings}
        {section name=db loop=$settings}
          
          {* Highlight newly created server *}
          {if $highlight eq $settings[db].id}
            <tr title="{$lang.srv_view_info}" class="normal" style="background:url('templates/{$template}/img/highlight_bg.png');" onMouseOver="style.cursor='pointer' ; this.style.backgroundColor='#c3c3c3'" onMouseOut=style.cursor='auto';this.style.backgroundColor='#fbfbef'>
          {else}
          <tr title="{$lang.srv_view_info}" class="normal" bgcolor={if $smarty.section.db.iteration is odd}#e1e1e1{else}#d7d7d7{/if} onMouseOver="style.cursor='pointer' ; this.style.backgroundColor='#c3c3c3'" onMouseOut=style.cursor='auto';this.style.backgroundColor='{if $smarty.section.db.iteration is odd}#e1e1e1{else}#d7d7d7{/if}'>
          {/if}
            <td onClick="window.location='manageserver.php?id={$settings[db].id}'"><img src="templates/{$template}/img/servers/small/{$settings[db].server}.png" border="0" width="18" height="18" /></td>
            <td onClick="window.location='manageserver.php?id={$settings[db].id}'">{$settings[db].long_name|stripslashes}</td>
            <td align="left"><a href="manageclient.php?id={$settings[db].userid}" title="View User Info">{$settings[db].username|stripslashes|default:'<i>Unknown</i>'}</a></td>
            <td onClick="window.location='manageserver.php?id={$settings[db].id}'" align="left" ><font color="blue">{$settings[db].ip|default:'&nbsp;'}<b>:</b>{$settings[db].port|default:'&nbsp;'}</font></td>
            <td onClick="window.location='manageserver.php?id={$settings[db].id}'" align="left">
            {if strlen($settings[db].description) > 15}
                {$settings[db].description|stripslashes|substr:0:15} <i>...</i>
            {else}
                {$settings[db].description|stripslashes}
            {/if}
            </td>
            
            {if $settings[db].creation_status eq "complete"}
                <td onClick="window.location='manageserver.php?id={$settings[db].id}'" align="center">
                      {if $settings[db].current_status eq "online"}
                          <font color="green"><b>{$lang.status_online}</b></font>
                      {elseif $settings[db].current_status eq "offline"}
                          <font color="red"><b>{$lang.status_offline}</b></font>
                      {else}
                          <font color="orange">{$lang.status_unknown}</font>
                      {/if}
                </td>
                <td onClick="window.location='manageserver.php?id={$settings[db].id}'" align="center">
                  {if $settings[db].current_maxplayers}
                    {$settings[db].current_numplayers}/{$settings[db].current_maxplayers}
                  {else}
                    &nbsp;
                  {/if}
                </td>
                <td align="center">
                    <span style="cursor:pointer;" onClick="javascript:confirmStopServer({$settings[db].id});" title="{$lang.srv_stop}">
                        <img src="templates/{$template}/img/manage/stop.png" border="0" width="18" height="18" />
                    </span>&nbsp;
                    <span style="cursor:pointer;" onClick="javascript:confirmRestartServer({$settings[db].id});" title="{$lang.srv_restart}">
                        <img src="templates/{$template}/img/manage/restart.png" border="0" width="18" height="18" />
                    </span>
                </td>
            {else}
                <td onClick="window.location='manageserver.php?id={$settings[db].id}'" colspan="3" style="border-radius:6px;"><font color="blue">{$lang.srv_creating} ...</font></td>
            {/if}
          </tr>
        {/section}
    {else}
      <tr>
        <td colspan="8" align="center">{$lang.clsrv_displ_no_srv}</td>
      </tr>
    {/if}
    </table>
    
    <br />
    
    
    <!-- PAGING -->
    <div align="center">
        <div style="width:90%;height:50px;text-align:center;">
        {if $total_pages gt 1}
        Page: 
            {section name=foo start=1 loop=$total_pages}
                {if $page eq $smarty.section.foo.index}
                    {$smarty.section.foo.index}
                {else}
                    <a href="servers.php?type={$type}&p={$smarty.section.foo.index}" style="text-decoration:none;font-weight:bold;">{$smarty.section.foo.index}</a> 
                {/if}
            {/section}
        <br /><br />
        {/if}        
        <span style="font-size:9pt;">Total Servers: {$total_rows}</span>
        </div>
    </div>
    <!-- /PAGING -->
    
    
    <br />
    
    <table border="0" cellpadding="10" cellspacing="5" align="center" width="500" style="width:500px;table-layout:fixed;word-wrap:break-word;text-align:center">
      <tr align="center">
        <td align="center"><a href="createserver.php?{if $server_userid}id={$server_userid}&{/if}type={$type}"><img style="padding-bottom:8px" src="templates/{$template}/img/icons/add_{$type}server.png" name="icon_image" width="64" height="64" border="0"><br /><span class="icon_text">{$lang.clsrv_create_srv}</span></a></td>
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
