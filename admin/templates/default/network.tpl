{if $logged_in}

    {include file="$template/header.tpl"}
    {include file="$template/navigation.tpl"}
    
    {* Location Links *}
    <span class="location">&nbsp;&nbsp;<a href="main.php">{$lang.admin_home}</a> / {$lang.network}</span>
    
    <br />
    
    {* Across-Page bar *}
    {include file="$template/bar.tpl"}
      
    <br />
    
    <center>
    <img src="templates/{$template}/img/icons/network.png" border="0" /><br />
    {$lang.network_title}
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
        <td align="center" width="35"><a href="network.php?order=os&sort={if $s_sort eq 'asc'}desc{else}asc{/if}&p={$page}" style="text-decoration:none;color:#FFF;">{$lang.network_os}</a></td>
        <td align="left" width="150"><a href="network.php?order=description&sort={if $s_sort eq 'asc'}desc{else}asc{/if}&p={$page}" style="text-decoration:none;color:#FFF;">{$lang.description}</a></td>
        <td align="left" width="140"><a href="network.php?order=ip&sort={if $s_sort eq 'asc'}desc{else}asc{/if}&p={$page}" style="text-decoration:none;color:#FFF;">{$lang.ip_address}</a></td>
        <td align="left" width="120"><a href="network.php?order=datacenter&sort={if $s_sort eq 'asc'}desc{else}asc{/if}&p={$page}" style="text-decoration:none;color:#FFF;">{$lang.datacenter}</a></td>
        <td align="left"><a href="network.php?order=location&sort={if $s_sort eq 'asc'}desc{else}asc{/if}&p={$page}" style="text-decoration:none;color:#FFF;">{$lang.location}</a></td>
        <td align="center">&nbsp;</td>
      </tr>


    {if $network}
        {section name=db loop=$network}
          <tr class="normal" bgcolor={if $smarty.section.db.iteration is odd}#e1e1e1{else}#d7d7d7{/if} onClick="window.location='managenetworkserver.php?id={$network[db].id}'" onMouseOver="style.cursor='pointer' ; this.style.backgroundColor='#c3c3c3'" onMouseOut=style.cursor='auto';this.style.backgroundColor='{if $smarty.section.db.iteration is odd}#e1e1e1{else}#d7d7d7{/if}'>
            <td align="center">
            
            {if $network[db].os eq "linux"}
                <img src="templates/{$template}/img/os/small/{$network[db].linux_flavor}.png" border="0" height="20" width="20" />
            {else}
                <img src="templates/{$template}/img/os/small/{$network[db].os}.png" border="0" height="20" width="20" />
            {/if}
            
            </td>
            <td align="left">{$network[db].description|default:'&nbsp;'|stripslashes|substr:0:35}</td>
            <td align="left"><b>{$network[db].ip|default:'&nbsp;'|stripslashes}</b></td>
            <td align="left">{$network[db].datacenter|default:'&nbsp;'|stripslashes}</td>
            <td align="left">{$network[db].location|default:'&nbsp;'|stripslashes}</td>
            <td align="center"><a href="managenetworkserver.php?id={$network[db].id}">{$lang.edit}</a></td>
          </tr>
        {/section}
    {else}
      <tr>
        <td colspan="5" align="center">{$lang.network_no_srv}</td>
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
                    <a href="network.php?p={$smarty.section.foo.index}" style="text-decoration:none;font-weight:bold;">{$smarty.section.foo.index}</a> 
                {/if}
            {/section}
        <br /><br />
        {/if}        
        <span style="font-size:9pt;">Total {$lang.servers}: {$total_rows}</span>
        </div>
    </div>
    <!-- /PAGING -->
    
    
    <br />
        
    <table border="0" cellpadding="10" cellspacing="5" align="center" width="500" style="width:500px;table-layout:fixed;word-wrap:break-word;text-align:center">
      <tr align="center">
        <td align="center"><a href="addnetworkserver.php"><img style="padding-bottom:8px" src="templates/{$template}/img/icons/network.png" name="icon_image" width="64" height="64" border="0"><br /><span class="icon_text">{$lang.network_add}</span></a></td>
      </tr>
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
