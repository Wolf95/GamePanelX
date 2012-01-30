{if $logged_in}

    {include file="$template/header.tpl"}
    {include file="$template/navigation.tpl"}
    
    {* Location Links *}
    <span class="location">&nbsp;&nbsp;<a href="main.php">{$lang.admin_home}</a> / <a href="supportedservers.php">{$lang.suppsrv_title}</a> / <a href="managesupportedserver.php?id={$serverid}">{$lang.managesuppsrv_title}</a></span> / {$lang.suppsrvconf_title}
    
    <br />
    
    {* Across-Page bar *}
    {include file="$template/bar.tpl"}
      
    <br />
    
    <center>
    <img src="templates/{$template}/img/icons/tickets_64.png" border="0" /><br />
    {$lang.suppsrvconf_config_files}
    </center>
    
    <br /><br />
    
    {if $infobox}
    <table border="0" cellpadding="0" cellspacing="0" width="600" align="center" class="infobox">
      <tr>
        <td align="center"><br />{$infobox}<br /><br /></td>
      </tr>
    </table><br />
    {/if}
    
    <table border="0" cellpadding="2" cellspacing="0" width="600" align="center" class="tablez">
      <tr class="table_title" height="20">
        <td>&nbsp;</td>
        <td align="left" width="220">{$lang.filename}</td>
        <td align="left">{$lang.description}</td>
        <td align="center">&nbsp;</td>
      </tr>

    {if $cfg_configs}
        {section name=db loop=$cfg_configs}
          <tr class="normal" bgcolor={if $smarty.section.db.iteration is odd}#e1e1e1{else}#d7d7d7{/if} onClick="window.location='editsupportedconfig.php?id={$cfg_configs[db].id}&srvid={$serverid}'" onMouseOver="style.cursor='pointer' ; this.style.backgroundColor='#c3c3c3'" onMouseOut=style.cursor='auto';this.style.backgroundColor='{if $smarty.section.db.iteration is odd}#e1e1e1{else}#d7d7d7{/if}'>
            
            <td align="center" width="30"><img src="templates/{$template}/img/nav/transparent/setup.png" border="0" /></td>
            <td align="left">{$cfg_configs[db].name|stripslashes|default:'&nbsp;'}</td>
            <td align="left">{$cfg_configs[db].description|stripslashes|default:'&nbsp;'}</td>
            <td align="center" width="40"><a href="editsupportedconfig.php?id={$cfg_configs[db].id}&srvid={$serverid}">{$lang.edit}</a></td>
          </tr>
        {/section}
    {else}
      <tr>
        <td colspan="5" align="center">{$lang.clients_no_client_accounts}</td>
      </tr>
    {/if}
    </table>
    
    <br /><br />
    
    <table border="0" cellpadding="10" cellspacing="5" align="center" width="500" style="width:500px;table-layout:fixed;word-wrap:break-word;text-align:center">
      <tr align="center">
        <td align="center"><a href="addserverconfig.php?id={$serverid}"><img style="padding-bottom:8px" src="templates/{$template}/img/icons/add_gameserver.png" name="icon_image" width="64" height="64" border="0"><br /><span class="icon_text">{$lang.suppsrvconf_add_config}</span></a></td>
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
