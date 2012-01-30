{if $logged_in}

    {include file="$template/header.tpl"}
    {include file="$template/navigation.tpl"}

    {* Location Links *}
    <span class="location">&nbsp;&nbsp;<a href="main.php">{$lang.home}</a> / <a href="tickets.php">{$lang.tickets_title}</a> / {$lang.viewticket_title}</span>
    
    <br />
    
    {* Across-Page bar *}
    {include file="$template/bar.tpl"}
      
      
    <br /><br />


    {literal}
    <!-- <script type="text/javascript">
    /*
    function confirmDelete()
    {
        var answer = confirm("{/literal}{$lang.viewticket_confirm_delete}{literal}")
        if (answer)
        {
            {/literal}
            window.location = "viewticket.php?action=delete&id={$ticketid}"
            {literal}
        }
    }
    */
    </script> -->
    {/literal}
    
    
    {if $infobox}
    <table border="0" cellpadding="0" cellspacing="0" width="600" align="center" class="infobox">
      <tr>
        <td align="center"><br />{$infobox}<br /><br /></td>
      </tr>
    </table><br />
    {/if}

    <br />

    <table border="0" cellpadding="1" cellspacing="0" width="680" align="center" class="tablez">
      <tr class="table_title" height="20">
        <td align="center" width="150">{$lang.date}</td>
        <td align="center" width="120">{$lang.username}</td>
        <td align="center" width="100">{$lang.viewticket_from}</td>
        <td align="left">{$lang.viewticket_response}</td>
      </tr>

      {if $ticket_details}
        {section name=db loop=$ticket_details}
          <tr height="25" class="normal" bgcolor={if $smarty.section.db.iteration is odd}#e1e1e1{else}#d7d7d7{/if} onMouseOver="this.style.backgroundColor='#c3c3c3'" onMouseOut=this.style.backgroundColor='{if $smarty.section.db.iteration is odd}#e1e1e1{else}#d7d7d7{/if}'>
            <td align="center" valign="top">{$ticket_details[db].date_added|stripslashes}</td>
            <td align="center" valign="top">{$ticket_details[db].username|stripslashes}</td>
            <td align="center" valign="top">{$ticket_details[db].response_type|stripslashes|ucwords}</td>
            <td align="left" valign="top">
              {if strlen($ticket_details[db].ticket_text) > 100}
                <textarea style="width:100%;height: 100px;font-size:12px" readonly>{$ticket_details[db].ticket_text|stripslashes}</textarea>
              {else}
                {$ticket_details[db].ticket_text|stripslashes}
              {/if}
            </td>
          </tr>
        {/section}
      {/if}
      
      {if $ticket_orig}
          {section name=orig loop=$ticket_orig}
          <tr height="25" class="normal" bgcolor={if $smarty.section.db.iteration is odd}#e1e1e1{else}#d7d7d7{/if} onMouseOver="this.style.backgroundColor='#c3c3c3'" onMouseOut=this.style.backgroundColor='{if $smarty.section.db.iteration is odd}#e1e1e1{else}#d7d7d7{/if}'>
            <td align="center" valign="top"><b>{$ticket_orig[orig].date_added|stripslashes}</b></td>
            <td align="center" valign="top"><b>{$ticket_orig[orig].username|stripslashes}</b></td>
            <td align="center" valign="top"><b>{$ticket_orig[orig].response_type|stripslashes|ucwords}</b></td>
            <td align="left" valign="top"><b>
              {if strlen($ticket_orig[orig].ticket_text) > 100}
                <textarea style="width:100%;height: 100px;font-size:12px" readonly>{$ticket_orig[orig].ticket_text|stripslashes}</textarea>
              {else}
                {$ticket_orig[orig].ticket_text|stripslashes}
              {/if}
            </b></td>
          </tr>
          {/section}
          {/if}

    </table>

    <br /><br />

    <form method="post" action="viewticket.php?id={$ticketid}">
    <table border="0" cellpadding="2" cellspacing="0" width="550" align="center" class="tablez">
      <tr class="table_title" height="20">
        <td align="center" colspan="2">{$lang.viewticket_info}</td>
      </tr>

      {if $ticket_orig}
      {section name=orig loop=$ticket_orig}
        <tr>
          <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
          <td align="left" class="description" style="padding-left:10px">{$lang.username}:&nbsp;</td>
          <td><b>{$ticket_orig[orig].username|stripslashes}</b></td>
        </tr>
        <tr>
          <td align="left" class="description" style="padding-left:10px">{$lang.viewticket_date_opened}:&nbsp;</td>
          <td>{$ticket_orig[orig].date_added}</td>
        </tr>
        <tr>
          <td align="left" class="description" style="padding-left:10px">{$lang.viewticket_opened_by}:&nbsp;</td>
          <td>{$ticket_orig[orig].opened_by|ucwords}</td>
        </tr>
        <tr>
          <td align="left" class="description" style="padding-left:10px">{$lang.viewticket_priority}:&nbsp;</td>
          <td>
            <select name="priority" style="width:170px">
              {if $ticket_orig[orig].priority eq "low"}
                <option value="low" selected>{$lang.viewticket_low}</option>
                <option value="medium">{$lang.viewticket_medium}</option>
                <option value="high">{$lang.viewticket_high}</option>
              {elseif $ticket_orig[orig].priority eq "medium"}
                <option value="low">{$lang.viewticket_low}</option>
                <option value="medium" selected>{$lang.viewticket_medium}</option>
                <option value="high">{$lang.viewticket_high}</option>
              {else}              
                <option value="low">{$lang.viewticket_low}</option>
                <option value="medium">{$lang.viewticket_medium}</option>
                <option value="high" selected>{$lang.viewticket_high}</option>
              {/if}
            </select>
          </td>
        </tr>
        
        <tr>
          <td align="left" class="description" style="padding-left:10px">{$lang.viewticket_cat}:&nbsp;</td>
          <td>
            <select name="category" style="width:170px">
              {if $ticket_orig[orig].category eq "support"}
                <option value="support" selected>{$lang.viewticket_support}</option>
                <option value="billing">{$lang.viewticket_billing}</option>
                <option value="sales">{$lang.viewticket_sales}</option>
              {elseif $ticket_orig[orig].category eq "billing"}
                <option value="support">{$lang.viewticket_support}</option>
                <option value="billing" selected>{$lang.viewticket_billing}</option>
                <option value="sales">{$lang.viewticket_sales}</option>
              {else}              
                <option value="support">{$lang.viewticket_support}</option>
                <option value="billing">{$lang.viewticket_billing}</option>
                <option value="sales" selected>{$lang.viewticket_sales}</option>
              {/if}
            </select>
          </td>
        </tr>
        
        
        <tr>
          <td align="left" class="description" style="padding-left:10px">{$lang.viewticket_status}:&nbsp;</td>
          <td>
            <select name="status" style="width:170px">
              {if $ticket_orig[orig].status eq "open"}
                <option value="open" selected>{$lang.viewticket_open}</option>
                <option value="closed">{$lang.viewticket_closed}</option>
              {else}              
                <option value="open">{$lang.viewticket_open}</option>
                <option value="closed" selected>{$lang.viewticket_closed}</option>
              {/if}
            </select>
          </td>
        </tr>
        
        <tr>
          <td align="left" class="description" style="padding-left:10px">{$lang.tickets_subject}:&nbsp;</td>
          <td><b>{$ticket_orig[orig].subject|stripslashes}</b></td>
        </tr>
        

      {/section}
      {else}
        <tr>
          <td class="normal" colspan="2" align="center">{$lang.viewticket_no_info}</td>
        </tr>
      {/if}
      
      <tr>
        <td colspan="2" style="border-bottom:1px solid lightgrey">&nbsp;</td>
      </tr>
      
      
      {if $ticket_latest}
        {section name=lat loop=$ticket_latest}
        <tr>
          <td colspan="2">&nbsp;</td>
        </tr>
      
        <tr>
          <td colspan="2" align="left" style="padding-left:10px"><b>{$lang.viewticket_last_user_resp}</b> ({$ticket_latest[lat].date_added|default:'&nbsp;'}):</td>
        </tr>
        <tr>
          <td colspan="2" height="95" align="left" valign="top" style="padding-left:10px"><br />{$ticket_latest[lat].ticket_text|stripslashes}</td>
        </tr>
        {/section}
      {else}
      
        {if $ticket_orig}
        {section name=orig loop=$ticket_orig}
          <tr>
            <td colspan="2">&nbsp;</td>
          </tr>
      
          <tr>
            <td colspan="2" align="left" style="padding-left:10px"><b>{$lang.viewticket_orig_msg}:</b></td>
          </tr>
          
          <tr>
            <td colspan="2" align="left" width="550" height="95" valign="top" style="padding-left:10px"><br /><pre style="font-family:Arial;font-size:8pt;font-weight:normal;white-space: -moz-pre-wrap;white-space: -pre-wrap;white-space: -o-pre-wrap;white-space: pre-wrap;word-wrap: break-word;">{$ticket_orig[orig].ticket_text|stripslashes}</pre></td>
          </tr>
        {/section}
        {/if}
      
      {/if}

      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      
      <tr>
        <td colspan="2" align="center"><b>{$lang.viewticket_add}:</b></td>
      </tr>
      
      <tr>
        <td colspan="2" align="center" height="130"><textarea name="response" id="response" class="ticket_response"></textarea></td>
      </tr>
      
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      
      <tr height="25">
        <td colspan="2" align="center"><input type="submit" name="update" value=" " class="button_save" /></td>
      </tr>
      
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      
      <!-- <tr>
        <td colspan="2" align="right" style="padding-right:4px"><a href="javascript:void(0)" onClick="return confirmDelete()"><b>{$lang.delete}</b></a></td>
      </tr> -->
    </table>
    </form>

    <br /><br />

    {include file="$template/footer.tpl"}

{/if}
