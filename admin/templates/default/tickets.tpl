{if $logged_in}

    {include file="$template/header.tpl"}
    {include file="$template/navigation.tpl"}
    
    {* Location Links *}
    <span class="location">&nbsp;&nbsp;<a href="main.php">{$lang.admin_home}</a> / {$lang.tickets_title}</span>
    
    <br />
    
    {* Across-Page bar *}
    {include file="$template/bar.tpl"}
      
    <br />
    
    <center>
    <img src="templates/{$template}/img/icons/tickets_64.png" border="0" /><br />
    <b>{$lang.tickets_view}:</b> 
    
    {if $ticket_status eq "open"}
      <a href="tickets.php?status=all">{$lang.tickets_all}</a> | <a href="tickets.php?status=open"><b>{$lang.tickets_open}</b></a> | <a href="tickets.php?status=closed">{$lang.tickets_closed}</a>
    {elseif $ticket_status eq "closed"}
      <a href="tickets.php?status=all">{$lang.tickets_all}</a> | <a href="tickets.php?status=open">{$lang.tickets_open}</a> | <a href="tickets.php?status=closed"><b>{$lang.tickets_closed}</b></a>
    {elseif $ticket_status eq "all"}
      <a href="tickets.php?status=all"><b>{$lang.tickets_all}</b></a> | <a href="tickets.php?status=open">{$lang.tickets_open}</a> | <a href="tickets.php?status=closed">{$lang.tickets_closed}</a>
    {else}
      <a href="tickets.php?status=all">{$lang.tickets_all}</a> | <a href="tickets.php?status=open">{$lang.tickets_open}</a> | <a href="tickets.php?status=closed">{$lang.tickets_closed}</a>
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
    
    <table border="0" cellpadding="2" cellspacing="0" width="650" align="center" class="tablez">
      <tr class="table_title" height="20">
        <td align="left" width="160">{$lang.tickets_date_created}</td>
        <td align="left" width="80">{$lang.tickets_priority}</td>
        <td align="left" width="80">{$lang.tickets_category}</td>
        <td align="left" width="130">{$lang.username}</td>
        <td align="left">{$lang.tickets_subject}</td>
      </tr>


    {if $tickets}
        {section name=db loop=$tickets}
          <tr height="35" bgcolor={if $smarty.section.db.iteration is odd}#e1e1e1{else}#d7d7d7{/if} onClick="window.location='viewticket.php?id={$tickets[db].id}'" onMouseOver="style.cursor='pointer' ; this.style.backgroundColor='#c3c3c3'" onMouseOut=style.cursor='auto';this.style.backgroundColor='{if $smarty.section.db.iteration is odd}#e1e1e1{else}#d7d7d7{/if}'>
            <td align="left">{$tickets[db].date_added|default:'&nbsp;'}</td>
            <td align="left">
              {if $tickets[db].priority eq "high"}
                <b>{$lang.tickets_high}</b>
              {else}
                {$tickets[db].priority|ucwords}
              {/if}
            </td>
            <td align="left">{$tickets[db].category|ucwords}</td>
            
            <td align="left">{$tickets[db].username|default:'&nbsp;'}</td>
            <td align="left"><b>
              {if strlen($tickets[db].subject) > 30}
                {$tickets[db].subject|stripslashes|substr:0:30}
              {else}
                {$tickets[db].subject|stripslashes}
              {/if}
            </b></td>
          </tr>
        {/section}
    {else}
      <tr>
        <td colspan="5" align="center">{$lang.tickets_none_view}</td>
      </tr>
    {/if}
    </table>
    
    <br /><br />
    
    <table border="0" cellpadding="10" cellspacing="5" align="center" width="500" style="width:500px;table-layout:fixed;word-wrap:break-word;text-align:center">
      <tr align="center">
        <td align="center"><a href="addticket.php"><img style="padding-bottom:8px" src="templates/{$template}/img/icons/tickets_add.png" name="icon_image" width="64" height="64" border="0"><br /><span class="icon_text">{$lang.tickets_icon_create}</span></a></td>
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
