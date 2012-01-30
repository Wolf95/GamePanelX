{if $logged_in}

    {include file="$template/header.tpl"}
    {include file="$template/navigation.tpl"}

    {section name=db loop=$cfg_addons}
    
    {* Location Links *}
    <span class="location">&nbsp;&nbsp;<a href="main.php">{$lang.admin_home}</a> / <a href="supportedservers.php">{$lang.suppsrv_title}</a> / <a href="managesupportedserver.php?id={$serverid}">{$lang.managesuppsrv_title}</a> / <a href="supportedserveraddons.php?id={$serverid}">{$lang.suppsrvaddons_title}</a> / {$lang.editsuppaddon_title}</span>
    
    <br />
    
    {* Across-Page bar *}
    {include file="$template/bar.tpl"}
      
      
    <br /><br />



    {literal}
    <script type="text/javascript">
    <!--
    function confirmation()
    {
        var answer = confirm("{/literal}{$lang.editsuppaddon_confirm_delete}{literal}")
        if (answer)
        {
            {/literal}
            window.location = "editsupportedaddon.php?id={$cfg_addons[db].id}&srvid={$serverid}&a=delete"
            {literal}
        }
    }
    //-->
    </script>
    {/literal}

    {if $infobox}
    <table border="0" cellpadding="0" cellspacing="0" width="600" align="center" class="infobox">
      <tr>
        <td align="center"><br />{$infobox}<br /><br /></td>
      </tr>
    </table><br />
    {/if}

    
    <br /><br />
    
    <form action="editsupportedaddon.php?id={$cfg_addons[db].id}" method="post">
    <table border="0" cellpadding="1" cellspacing="0" width="400" align="center" class="tablez">
      <tr class="table_title" height="20">
        <td align="center" colspan="2">{$lang.editsuppaddon_addon_details}</td>
      </tr>
      
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      
      <tr>
        <td align="right" width="40%"><b>{$lang.status}:</b>&nbsp;</td>
        <td align="left">
          {if $creation_status eq "complete"}
            <font color="green">{$lang.complete}</font>
          {elseif $creation_status eq "running"}
            <font color="blue">{$lang.creating} ...</font>
          {else}
            <font color="orange">{$lang.unknown}</font>
          {/if}
        </td>
      </tr>
      
      <tr>
        <td align="right" width="40%"><b>{$lang.network_server}:</b>&nbsp;</td>
        <td align="left">
          {if $cfg_addons[db].netdesc}
            {$cfg_addons[db].netdesc|default:'&nbsp;'|stripslashes|substr:0:35} ({$cfg_addons[db].ip})
          {else}
            {$cfg_addons[db].ip}
          {/if}
        </td>
      </tr>
      
      
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      
      
      <tr>
        <td align="right" class="description" width="120">{$lang.type}:&nbsp;</td>
        <td>
        
          <select name="type" style="width:170px">
            {if $cfg_addons[db].type eq "mappack"}
                <option value="mod">{$lang.addsupaddon_mod}</option>
                <option value="mappack" selected>{$lang.addsupaddon_map_pack}</option>
            {else}
                <option value="mod" selected>{$lang.addsupaddon_mod}</option>
                <option value="mappack">{$lang.addsupaddon_map_pack}</option>
            {/if}
          </select>
        
        </td>
      </tr>
      <tr>
        <td align="right" class="description">{$lang.available}:&nbsp;</td>
        <td>
        
          <select name="available" style="width:170px">
            {if $cfg_addons[db].available eq "Y"}
                <option value="Y" selected>{$lang.yes}</option>
                <option value="N">{$lang.no}</option>
            {else}
                <option value="Y">{$lang.yes}</option>
                <option value="N" selected>{$lang.no}</option>
            {/if}
          </select>
        
        </td>
      </tr>
      <tr>
        <td align="right" class="description">{$lang.editsuppaddon_addon_hash}:&nbsp;</td>
        <td><b>{$cfg_addons[db].addon_hash|stripslashes}</b></td>
      </tr>
      
      <tr>
        <td align="right" class="description">{$lang.name}:&nbsp;</td>
        <td><input type="text" name="name" class="textbox_normal" style="width:220px" value="{$cfg_addons[db].name|stripslashes}"></td>
      </tr>
      <tr>
        <td align="right" class="description">{$lang.target}:&nbsp;</td>
        <td><input type="text" name="target" class="textbox_normal" style="width:220px" value="{$cfg_addons[db].target|stripslashes}"></td>
      </tr>
      
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      
      <tr>
        <td align="center" colspan="2" class="description">
          {$lang.description}:<br />
          <textarea name="description" style="width:95%;height:100px">{$cfg_addons[db].description|stripslashes}</textarea>
        </td>
      </tr>
      
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      
      <tr>
        <td align="center" colspan="2" class="description">
          {$lang.private_notes}:<br />
          <textarea name="notes" style="width:95%;height:100px">{$cfg_addons[db].notes|stripslashes}</textarea>
        </td>
      </tr>
      
      
      
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      
      <tr>
        <td colspan="2" align="center"><input type="submit" name="update" value=" " class="button_save" /></td>
      </tr>
      
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      
      <tr>
        <td colspan="2" align="right"><a href="javascript:void(0)" onClick="javascript:confirmation()"><b>{$lang.delete}</b></a></td>
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
    {/section}
      


    {include file="$template/footer.tpl"}

{/if}
