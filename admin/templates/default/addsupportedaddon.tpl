{if $logged_in}

    {include file="$template/header.tpl"}
    {include file="$template/navigation.tpl"}

    {* Location Links *}
    <span class="location">&nbsp;&nbsp;<a href="main.php">{$lang.admin_home}</a> / <a href="supportedservers.php">{$lang.suppsrv_title}</a> / <a href="managesupportedserver.php?id={$serverid}">{$lang.managesuppsrv_title}</a> / <a href="supportedserveraddons.php?id={$serverid}">Supported Server Addons</a> / Add Supported Addon</span>
    
    <br />
    
    {* Across-Page bar *}
    {include file="$template/bar.tpl"}
      
      
    <br /><br />


    {if $infobox}
    <table border="0" cellpadding="0" cellspacing="0" width="600" align="center" class="infobox">
      <tr>
        <td align="center"><br />{$infobox}<br /><br /></td>
      </tr>
    </table><br />
    {/if}

    
    <br /><br />
    
    <form action="addsupportedaddon.php?id={$serverid}" method="post">
    <table border="0" cellpadding="1" cellspacing="0" width="500" align="center" class="tablez">
      <tr class="table_title" height="20">
        <td align="center" colspan="2">{$lang.addsupaddon_title}</td>
      </tr>
      
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      
      <tr>
        <td align="right" class="description" width="120">{$lang.type}:&nbsp;</td>
        <td>
        
          <select name="type" style="width:260px">
            <option value="mod" selected>{$lang.addsupaddon_mod}</option>
            <option value="mappack">{$lang.addsupaddon_map_pack}</option>
          </select>
        
        </td>
      </tr>
      <tr>
        <td align="right" class="description">{$lang.available}:&nbsp;</td>
        <td>
        
          <select name="available" style="width:260px">
            <option value="Y" selected>{$lang.yes}</option>
            <option value="N">{$lang.no}</option>
          </select>
        
        </td>
      </tr>
      <tr>
        <td align="right" class="description">{$lang.network_server}:&nbsp;</td>
        <td>
          <select name="networkid" style="width:260px">
            {if $network_servers}
            {section name=ns loop=$network_servers}
                <option value="{$network_servers[ns].id}">
                  {if $network_servers[ns].description}
                    {$network_servers[ns].description|default:'&nbsp;'|stripslashes|substr:0:35} ({$network_servers[ns].ip})
                  {else}
                    {$network_servers[ns].ip}
                  {/if}
                </option>
            {/section}
            {else}
                <option value="">{$lang.none}</option>
            {/if}
          </select>
        </td>
      </tr>

      <tr>
        <td align="right" class="description">{$lang.name}:&nbsp;</td>
        <td><input type="text" name="name" class="textbox_normal" style="width:260px"></td>
      </tr>
      <tr>
        <td align="right" class="description">{$lang.addsupaddon_file_path}:&nbsp;</td>
        <td><input type="text" name="file_path" class="textbox_normal" style="width:260px"></td>
      </tr>
      <tr>
        <td align="right" class="description">{$lang.addsupaddon_install_target}:&nbsp;</td>
        <td><input type="text" name="target" class="textbox_normal" style="width:260px"></td>
      </tr>
      
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      
      <tr>
        <td align="center" colspan="2" class="description">
          {$lang.description}:<br />
          <textarea name="description" style="width:95%;height:100px"></textarea>
        </td>
      </tr>
      
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      
      <tr>
        <td align="center" colspan="2" class="description">
          {$lang.private_notes}:<br />
          <textarea name="notes" style="width:95%;height:100px"></textarea>
        </td>
      </tr>
      
      
      
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      
      <tr>
        <td colspan="2" align="center"><input type="submit" name="create" value=" " class="button_create" /></td>
      </tr>
      
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
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
                        <a href="{$icons[page].href}?id={$cfg_addons[db].id}"><img style="padding-bottom:8px" src="templates/{$template}/img/icons/{$icons[page].image}" name="icon_image" width="64" height="64" border="0"><br /><span class="icon_text">{$icons[page].image_text}</a></span>
                    </td>
                  </tr>
                </table>
            {/section}
        </td>
      </tr>
    </table>


    {include file="$template/footer.tpl"}

{/if}
