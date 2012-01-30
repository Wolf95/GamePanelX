{if $logged_in}

    {include file="$template/header.tpl"}
    {include file="$template/navigation.tpl"}
    
    {* Location Links *}
    <span class="location">&nbsp;&nbsp;<a href="main.php">{$lang.admin_home}</a> / {$lang.domains_title}</span>
    
    <br />
    
    {* Across-Page bar *}
    {include file="$template/bar.tpl"}
      
    <br />
    
    <center>
    <img src="templates/{$template}/img/icons/domains-64px.png" border="0" /><br />
    {$lang.domains_disp_all}
    </center>
    
    <br /><br />
    
    {if $infobox}
    <table border="0" cellpadding="0" cellspacing="0" width="600" align="center" class="infobox">
      <tr>
        <td align="center"><br />{$infobox}<br /><br /></td>
      </tr>
    </table><br />
    {/if}
    
    <table border="0" cellpadding="2" cellspacing="0" width="500" align="center" class="tablez">
      <tr class="table_title" height="20">
        <td>{$lang.domain}</td>
        <td>{$lang.domains_last_updated}</td>
        <td width="60">&nbsp;</td>
      </tr>


    {if $domains}
        {section name=db loop=$domains}
          <tr class="normal" bgcolor={if $smarty.section.db.iteration is odd}#e1e1e1{else}#d7d7d7{/if} onClick="window.location='editdomain.php?id={$domains[db].id|default:'&nbsp;'}'" onMouseOver="style.cursor='pointer' ; this.style.backgroundColor='#c3c3c3'" onMouseOut=style.cursor='auto';this.style.backgroundColor='{if $smarty.section.db.iteration is odd}#e1e1e1{else}#d7d7d7{/if}'>
            <td>{$domains[db].domain|default:'&nbsp;'}</td>
            <td>{$domains[db].last_updated|default:'&nbsp;'}</td>
            <td><a href="editdomain.php?id={$domains[db].id|default:'&nbsp;'}">{$lang.edit}</a></td>
          </tr>
        {/section}
    {else}
      <tr>
        <td colspan="5" align="center">{$lang.domains_none}</td>
      </tr>
    {/if}
    </table>
    
    <br /><br />
    
    <table border="0" cellpadding="10" cellspacing="5" align="center" width="500" style="width:500px;table-layout:fixed;word-wrap:break-word;text-align:center">
      <tr align="center">
        <td align="center"><a href="adddomain.php"><img style="padding-bottom:8px" src="templates/{$template}/img/icons/domains-64px.png" name="icon_image" width="64" height="64" border="0"><br /><span class="icon_text">{$lang.adddom_add_domain}</span></a></td>
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
