{if $logged_in}

    {include file="$template/header.tpl"}
    {include file="$template/navigation.tpl"}
    
    {* Location Links *}
    <span class="location">&nbsp;&nbsp;<a href="main.php">{$lang.admin_home}</a> / {$lang.suppsrv_title}</span>
    
    <br />
    
    {* Across-Page bar *}
    {include file="$template/bar.tpl"}
      
    <br />
    
    <center>
    <img src="templates/{$template}/img/icons/supported_games-64px.png" border="0" /><br />
    {$lang.defaultsrv_title}<br />
    <b>{$lang.defaultsrv_view}:</b> 
    
    {if $server_type eq "game"}
      <a href="defaultservers.php?type=game"><b>{$lang.game}</b></a> | <a href="defaultservers.php?type=voip">{$lang.voip}</a> | <a href="defaultservers.php?type=other">{$lang.other}</a>
    {elseif $server_type eq "voip"}
      <a href="defaultservers.php?type=game">{$lang.game}</a> | <a href="defaultservers.php?type=voip"><b>{$lang.voip}</b></a> | <a href="defaultservers.php?type=other">{$lang.other}</a>
    {else}
      <a href="defaultservers.php?type=game">{$lang.game}</a> | <a href="defaultservers.php?type=voip">{$lang.voip}</a> | <a href="defaultservers.php?type=other"><b>{$lang.other}</b></a>
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
    
    <table border="0" cellpadding="2" cellspacing="0" width="500" align="center" style="border-radius:8px;">
      <tr class="table_title" height="20">
        <td>&nbsp;</td>
        <td align="left">{$lang.defaultsrv_full_name}</td>
        <td align="center" width="80">{$lang.defaultsrv_installable}</td>
        <td width="50">&nbsp;</td>
      </tr>


    {if $supported_servers}
        {section name=db loop=$supported_servers}
          <tr class="normal" bgcolor={if $newid eq $supported_servers[db].id}orange{elseif $smarty.section.db.iteration is odd}#e1e1e1{else}#d7d7d7{/if} onClick="window.location='managesupportedserver.php?id={$supported_servers[db].id}'" onMouseOver="style.cursor='pointer' ; this.style.backgroundColor='#c3c3c3'" onMouseOut=style.cursor='auto';this.style.backgroundColor='{if $newid eq $supported_servers[db].id}orange{elseif $smarty.section.db.iteration is odd}#e1e1e1{else}#d7d7d7{/if}'>
            <td align="center" width="25"><img src="templates/{$template}/img/servers/small/{$supported_servers[db].short_name|default:'default'}.png" width="18" height="18" title="{$supported_servers[db].long_name|default:'&nbsp;'}" border="0" /></td>
            <td align="left">{$supported_servers[db].long_name|default:'&nbsp;'}</td>
            <td align="center">
              {if $supported_servers[db].status eq "complete"}
                <span style="font-weight:bold;color:green;">{$lang.yes}</span>
              {else}
                <span style="font-weight:normal;color:#333;">{$lang.no}</span>
              {/if}
            </td>
            <td align="center"><a href="editsupportedserver.php?id={$supported_servers[db].id}">{$lang.edit}</a></td>
          </tr>
        {/section}
    {else}
      <tr>
        <td colspan="5" align="center">{$lang.defaultsrv_no_srv}</td>
      </tr>
    {/if}
    </table>
    
    <br /><br />
    
    <table border="0" cellpadding="10" cellspacing="5" align="center" width="500" style="width:500px;table-layout:fixed;word-wrap:break-word;text-align:center">
      <tr align="center">
        <td align="center"><a href="addsupportedserver.php"><img style="padding-bottom:8px" src="templates/{$template}/img/icons/supported_games_add.png" name="icon_image" width="64" height="64" border="0"><br /><span class="icon_text">{$lang.suppsrv_icon_add}</span></a></td>
        
        <td align="center"><a href="xmlimport.php"><img style="padding-bottom:8px" src="templates/{$template}/img/icons/xmlfile.png" name="icon_image" width="64" height="64" border="0"><br /><span class="icon_text">{$lang.defaultsrv_import_xml}</span></a></td>
      </tr>
    </table>

    {include file="$template/footer.tpl"}

{/if}
