{if $logged_in}

    {include file="$template/header.tpl"}
    {include file="$template/navigation.tpl"}
    
    {* Location Links *}
    <span class="location">&nbsp;&nbsp;<a href="main.php">{$lang.admin_home}</a> / {$lang.clients_nav_client_accounts}</span>
    
    <br />
    
    {* Across-Page bar *}
    {include file="$template/bar.tpl"}
      
    <br />
    
    <center>
    <img src="templates/{$template}/img/icons/clients-64px.png" border="0" /><br />
    {$lang.clients_displaying_all_accounts}
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
        <td align="left"><a href="clients.php?order=username&sort={if $s_sort eq 'asc'}desc{else}asc{/if}&p={$page}" style="text-decoration:none;color:#FFF;">{$lang.username}</a></td>
        <td align="left" width="100"><a href="clients.php?order=first_name&sort={if $s_sort eq 'asc'}desc{else}asc{/if}&p={$page}" style="text-decoration:none;color:#FFF;">{$lang.first_name}</a></td>
        <td align="left" width="100"><a href="clients.php?order=last_name&sort={if $s_sort eq 'asc'}desc{else}asc{/if}&p={$page}" style="text-decoration:none;color:#FFF;">{$lang.last_name}</a></td>
        <td align="left"><a href="clients.php?order=email_address&sort={if $s_sort eq 'asc'}desc{else}asc{/if}&p={$page}" style="text-decoration:none;color:#FFF;">{$lang.email}</a></td>
        <td align="center"><a href="clients.php?order=status&sort={if $s_sort eq 'asc'}desc{else}asc{/if}&p={$page}" style="text-decoration:none;color:#FFF;">{$lang.status}</a></td>
      </tr>


    {if $users}
        {section name=db loop=$users}
          <tr class="normal" bgcolor={if $smarty.section.db.iteration is odd}#e1e1e1{else}#d7d7d7{/if} onClick="window.location='manageclient.php?id={$users[db].id}'" onMouseOver="style.cursor='pointer' ; this.style.backgroundColor='#c3c3c3'" onMouseOut=style.cursor='auto';this.style.backgroundColor='{if $smarty.section.db.iteration is odd}#e1e1e1{else}#d7d7d7{/if}'>
            <td align="left">{$users[db].username|default:'&nbsp;'}</td>
            <td align="left">{$users[db].first_name|default:'&nbsp;'}</td>
            <td align="left">{$users[db].last_name|default:'&nbsp;'}</td>
            <td align="left">{$users[db].email_address|default:'&nbsp;'}</td>
            <td align="center">
            {if $users[db].status eq "active"}
                <font color=green>{$lang.status_active}</font>
            {elseif $users[db].status eq "suspended"}
                <font color=red>{$lang.status_suspended}</font>
            {else}
                <font color=orange>{$lang.status_unknown}</font>
            {/if}
            &nbsp;</td>
          </tr>
        {/section}
    {else}
      <tr>
        <td colspan="5" align="center">{$lang.clients_no_client_accounts}</td>
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
                    <a href="clients.php?p={$smarty.section.foo.index}" style="text-decoration:none;font-weight:bold;">{$smarty.section.foo.index}</a> 
                {/if}
            {/section}
        <br /><br />
        {/if}        
        <span style="font-size:9pt;">Total {$lang.main_clients}: {$total_rows}</span>
        </div>
    </div>
    <!-- /PAGING -->
    
    <br />
    
    <table border="0" cellpadding="10" cellspacing="5" align="center" width="500" style="width:500px;table-layout:fixed;word-wrap:break-word;text-align:center">
      <tr align="center">
        <td align="center"><a href="addclient.php"><img style="padding-bottom:8px" src="templates/{$template}/img/icons/clients_add-64px.png" name="icon_image" width="64" height="64" border="0"><br /><span class="icon_text">{$lang.clients_icon_add_client}</span></a></td>
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
