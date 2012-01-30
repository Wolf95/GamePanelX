{if $logged_in}

    {include file="$template/header.tpl"}
    {include file="$template/navigation.tpl"}

    
    {section name=sd loop=$server_details}
    
    
    {* Location Links *}
    <span class="location">&nbsp;&nbsp;<a href="main.php">{$lang.admin_home}</a> / <a href="servers.php?type={$type}">

    {if $type == 'game'}
      {$lang.managesrv_nav_game_srv}
    {elseif $type == 'voice'}
      {$lang.managesrv_nav_voice_srv}
    {elseif $type == 'other'}
      {$lang.managesrv_nav_other_srv}
    {else}
      {$lang.managesrv_nav_all_srv}
    {/if}
    
    </a> / <a href="manageserver.php?id={$server_details[sd].id}">
    
    {if $type == 'game'}
      {$lang.managesrv_nav_man_game_srv}
    {elseif $type == 'voice'}
      {$lang.managesrv_nav_man_voice_srv}
    {elseif $type == 'other'}
      {$lang.managesrv_nav_man_other_srv}
    {else}
      {$lang.managesrv_nav_man_srv}
    {/if}
    
    </a> / {$lang.cur_status_title}
    </span>
    {/section}
    
    <br />

    {* Across-Page bar *}
    {include file="$template/bar.tpl"}
      
      
    <br /><br />

    <center>
    <img style="padding-bottom:8px" src="templates/{$template}/img/icons/currentstatus.png" name="icon_image" width="64" height="64" border="0"><br />
    {$lang.cur_status_image_text}
    </center>
    
    
    <br />
    
    {section name=db loop=$current_status}
    
    <table border="0" cellpadding="1" cellspacing="0" width="600" align="center" class="tablez">
      <tr class="table_title" height="20">
        <td align="center" colspan="2">{$lang.cur_status_tbl_title}</td>
      </tr>
      
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      
      {if $current_status[db].current_status eq "online"}
        <tr>
          <td align="right" width="40%"><b>{$lang.cur_status_hostname}:</b>&nbsp;</td>
          <td align="left">{$current_status[db].current_hostname}</td>
        </tr>
        <tr>
          <td align="right" width="40%"><b>{$lang.cur_status_map}:</b>&nbsp;</td>
          <td align="left">{$current_status[db].current_mapname}</td>
        </tr>
        <tr>
          <td align="right" width="40%"><b>{$lang.cur_status_players}:</b>&nbsp;</td>
          <td align="left">{$current_status[db].current_numplayers}/{$current_status[db].current_maxplayers}</td>
        </tr>
        <tr>
          <td align="right" width="40%"><b>{$lang.cur_status_dir}:</b>&nbsp;</td>
          <td align="left">{$current_status[db].current_game_dir}</td>
        </tr>
        <tr>
          <td align="right" width="40%"><b>{$lang.cur_status_mod}:</b>&nbsp;</td>
          <td align="left">{$current_status[db].current_mod}</td>
        </tr>
        <tr>
          <td align="right" width="40%"><b>{$lang.cur_status_password}:</b>&nbsp;</td>
          <td align="left">
            {if $current_status[db].current_has_pw eq "1"}
              {$lang.yes}
            {else}
              {$lang.no}
            {/if}
          </td>
        </tr>
        <tr>
          <td align="right" width="40%"><b>{$lang.cur_status_protocol}:</b>&nbsp;</td>
          <td align="left">{$current_status[db].current_protocol}</td>
        </tr>
        <tr>
          <td align="right" width="40%"><b>{$lang.cur_status_os}:</b>&nbsp;</td>
          <td align="left">{$current_status[db].current_os}</td>
        </tr>
        <tr>
          <td align="right" width="40%"><b>{$lang.cur_status_num_bots}:</b>&nbsp;</td>
          <td align="left">{$current_status[db].current_num_bots}</td>
        </tr>
      {else}
        <tr>
          <td colspan="2" align="center">{$lang.cur_status_srv_offline}</td>
        </tr>
      {/if}
      
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
    </table>
    
    <br /><br />
      
    <table border="0" cellpadding="1" cellspacing="0" width="600" align="center" class="tablez">
      <tr class="table_title" height="20">
        <td align="left" width="30">{$lang.cur_status_tbl_id}</td>
        <td align="left" width="350">{$lang.cur_status_tbl_ply_name}</td>
        <td align="left">{$lang.cur_status_tbl_time_conn}</td>
        <td align="center" width="50">{$lang.cur_status_tbl_score}</td>
      </tr>
      
      {if $current_status[db].current_players}
        {section name=curr_players loop=$current_status[db].current_players}
        <tr>
          <td align="left">{$current_status[db].current_players[curr_players].id}</td>
          <td align="left"><font color="blue">{$current_status[db].current_players[curr_players].name}</font></td>
          <td align="left">{$current_status[db].current_players[curr_players].time}</td>
          <td align="center">{$current_status[db].current_players[curr_players].gq_score}</td>
        </tr>
        {/section}
      {else}
        <tr>
          <td colspan="4" align="center">{$lang.cur_status_tbl_no_pl_conn}</td>
        </tr>
      {/if}

    </table>
    
    <br /><br />
    
    {* All icons for this page and theme *}
    <table border="0" cellpadding="0" cellspacing="0" align="center">
      <tr>
        <td>
            {section name=page loop=$icons}
                <table border="0" cellpadding="12" cellspacing="5" align="left">
                  <tr>
                    <td align="center">
                        <a href="{$icons[page].href}?id={$current_status[db].id}"><img style="padding-bottom:8px" src="templates/{$template}/img/icons/{$icons[page].image}" name="icon_image" width="64" height="64" border="0"><br /><span class="icon_text">{$icons[page].image_text}</a></span>
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
