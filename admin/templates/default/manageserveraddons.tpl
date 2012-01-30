{if $logged_in}

    {include file="$template/header.tpl"}
    {include file="$template/navigation.tpl"}


    {* Location Links *}
        <span class="location">&nbsp;&nbsp;<a href="main.php">{$lang.admin_home}</a> / <a href="servers.php?type={$server_type}">

    {if $type == 'game'}
      {$lang.managesrv_nav_game_srv}
    {elseif $type == 'voice'}
      {$lang.managesrv_nav_voice_srv}
    {elseif $type == 'other'}
      {$lang.managesrv_nav_other_srv}
    {else}
      {$lang.managesrv_nav_all_srv}
    {/if}
    
    </a> / <a href="manageserver.php?id={$server_id}">
    
    {if $type == 'game'}
      {$lang.managesrv_nav_man_game_srv}
    {elseif $type == 'voice'}
      {$lang.managesrv_nav_man_voice_srv}
    {elseif $type == 'other'}
      {$lang.managesrv_nav_man_other_srv}
    {else}
      {$lang.managesrv_nav_man_srv}
    {/if}
    
    </a> / {$lang.manageaddon_title}
    
    </span>
    
    <br />
    
    {* Across-Page bar *}
    {include file="$template/bar.tpl"}
      
      
    <br /><br />

    <center>
    <img style="padding-bottom:8px" src="templates/{$template}/img/icons/edit_addons.png" name="icon_image" width="64" height="64" border="0"><br />
    {$lang.manageaddon_view_avail}
    
    {if $addon_type eq "mod"}
      {$lang.manageaddon_mods}
    {elseif $addon_type eq "mappack"}
      {$lang.manageaddon_mappacks}
    {else}
      {$lang.manageaddon_addons}
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


    <form action="manageserveraddons.php?id={$server_id}" name="server_details" method="post">
    <table border="0" cellpadding="1" cellspacing="0" width="450" align="center" class="tablez">
      <tr class="table_title" height="20">
        <td align="left">{$lang.manageaddon_tbl_name}</td>
        <td align="center" width="100">{$lang.managesrvaddons_installed}</td>
        <td align="center" width="100">&nbsp;</td>
      </tr>
      
      {if $addon_details}
        {section name=db loop=$addon_details}
        <tr class="normal" bgcolor={if $smarty.section.db.iteration is odd}#e1e1e1{else}#d7d7d7{/if} onMouseOver="style.cursor='pointer' ; this.style.backgroundColor='#c3c3c3'" onMouseOut=style.cursor='auto';this.style.backgroundColor='{if $smarty.section.db.iteration is odd}#e1e1e1{else}#d7d7d7{/if}'>
          <td align="left">{$addon_details[db].name}</td>
          <td align="center">
            {if $addon_details[db].installed eq "Y"}
              <font color="green"><b>{$lang.yes}</b></font>
            {else}
              {$lang.no}
            {/if}
          </td>
          <td align="center">
            {if $addon_details[db].installed eq "Y"}
              <input type="button" name="remove" value="{$lang.manageaddon_remove}" onclick="javascript:window.location='manageserveraddons.php?id={$server_id}&type={$addon_type}&addonid={$addon_details[db].addonid}&thisid={$addon_details[db].srv_addonid}&a=remove'" style="width:100%">
              </td>
            {else}
              <input type="button" name="install" value="{$lang.manageaddon_install}" onclick="javascript:window.location='manageserveraddons.php?id={$server_id}&type={$addon_type}&addonid={$addon_details[db].addonid}&a=install'" style="width:100%">
            {/if}
        </tr>
        {/section}
      {else}
        <tr>
          <td colspan="4" align="center">{$lang.manageaddon_no_addons}</td>
        </tr>
      {/if}
      
    </table>
    </form>
    
    <br /><br />
    
    {* All icons for this page and theme *}
    <table border="0" cellpadding="0" cellspacing="0" align="center">
      <tr>
        <td>
            {section name=page loop=$icons}
                <table border="0" cellpadding="12" cellspacing="5" align="left">
                  <tr>
                    <td align="center">
                        <a href="{$icons[page].href}?id={$addon_details[db].id}"><img style="padding-bottom:8px" src="templates/{$template}/img/icons/{$icons[page].image}" name="icon_image" width="64" height="64" border="0"><br /><span class="icon_text">{$icons[page].image_text}</a></span>
                    </td>
                  </tr>
                </table>
            {/section}
        </td>
      </tr>
    </table>


    {include file="$template/footer.tpl"}

{/if}
