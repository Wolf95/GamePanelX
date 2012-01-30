{if $logged_in}

    {include file="$template/header.tpl"}
    {include file="$template/navigation.tpl"}
    
    {* Location Links *}
    <span class="location">&nbsp;&nbsp;<a href="main.php">{$lang.admin_home}</a> / {$lang.templates_title}
    
    <br />
    
    {* Across-Page bar *}
    {include file="$template/bar.tpl"}
      
    <br />
    
    <center>
    <img src="templates/{$template}/img/icons/template_64.png" border="0" /><br />
    {$lang.templates_disp_all}
    </center>
    
    <br /><br />
    
    {if $infobox}
    <table border="0" cellpadding="0" cellspacing="0" width="600" align="center" class="infobox">
      <tr>
        <td align="center"><br />{$infobox}<br /><br /></td>
      </tr>
    </table><br />
    {/if}
    
    
    
    {if $automag_avail}
    <table border="0" cellpadding="2" cellspacing="0" width="600" align="center" class="tablez">
      <tr class="table_title" style="height:25px">
        <td colspan="1" width="22"><img src="templates/{$template}/img/icons/info-28px.png" width="20" height="20" border="0" /></td>
        <td colspan="4" align="center"><b>{$lang.templates_installs_waiting}</b></td>
      </tr>

        {section name=am loop=$automag_avail}
        <tr class="normal" bgcolor={if $smarty.section.am.iteration is odd}#e1e1e1{else}#d7d7d7{/if} onClick="window.location='managetemplate.php?id={$automag_avail[am].id}'" onMouseOver="style.cursor='pointer' ; this.style.backgroundColor='#c3c3c3'" onMouseOut=style.cursor='auto';this.style.backgroundColor='{if $smarty.section.am.iteration is odd}#e1e1e1{else}#d7d7d7{/if}'>
          <td width="22"><img src="templates/{$template}/img/servers/small/{$automag_avail[am].server|default:'default'}.png" width="20" height="20" border="0" /></td>
          <td width="120">{$automag_avail[am].date_created|strtolower}</td>
          <td><b>{$automag_avail[am].long_name}</b></td>
          <td>{$automag_avail[am].ip}</td>
          <td width="100"><a href="managetemplate.php?id={$automag_avail[am].id}">{$lang.templates_view_status}</a></td>
        </tr>
        {/section}
    
    </table>
    <br /><br /><center>--</center><br /><br />
    {/if}
    
    
    
    <table border="0" cellpadding="2" cellspacing="0" width="600" align="center" class="tablez">
      <tr class="table_title" height="20">
        <td align="left" width="200">{$lang.server}</td>
        <td align="left">{$lang.description}</td>
        <td align="center" width="60">{$lang.available}</td>
        <td align="center" width="60">{$lang.templates_default}</td>
      </tr>


      {if $server_templates}
      {section name=db loop=$server_templates}
      <tr height="30" bgcolor={if $smarty.section.db.iteration is odd}#e1e1e1{else}#d7d7d7{/if} onClick="window.location='managetemplate.php?id={$server_templates[db].id}'" onMouseOver="style.cursor='pointer' ; this.style.backgroundColor='#c3c3c3'" onMouseOut=style.cursor='auto';this.style.backgroundColor='{if $smarty.section.db.iteration is odd}#e1e1e1{else}#d7d7d7{/if}'>
        <td><b>{$server_templates[db].long_name|default:'&nbsp;'}</b></td>
        <td>{$server_templates[db].description|default:'&nbsp;'}</td>
        <td align="center">
          {if $server_templates[db].available eq "Y"}
            {$lang.yes}
          {else}
            {$lang.no}
          {/if}
        </td>
        <td align="center">
          {if $server_templates[db].is_default eq "Y"}
            {$lang.yes}
          {else}
            {$lang.no}
          {/if}
        </td>
      </tr>
      {/section}
      {else}
        <tr>
          <td colspan="4" align="center">{$lang.templates_none_found}</td>
        </tr>
      {/if}
    </table>
    
    <br /><br />
    
    <table border="0" cellpadding="10" cellspacing="5" align="center" width="500" style="width:500px;table-layout:fixed;word-wrap:break-word;text-align:center">
      <tr align="center">
        <td align="center"><a href="supportedserverinstall.php"><img style="padding-bottom:8px" src="templates/{$template}/img/icons/supported_games_add.png" name="icon_image" width="64" height="64" border="0"><br /><span class="icon_text">{$lang.templates_icon_install}</span></a></td>
        <td align="center"><a href="addtemplate.php"><img style="padding-bottom:8px" src="templates/{$template}/img/icons/template_add_64.png" name="icon_image" width="64" height="64" border="0"><br /><span class="icon_text">{$lang.templates_add}</span></a></td>
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
