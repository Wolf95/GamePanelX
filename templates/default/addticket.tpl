{if $logged_in}

    {include file="$template/header.tpl"}
    {include file="$template/navigation.tpl"}

    {* Location Links *}
    <span class="location">&nbsp;&nbsp;<a href="main.php">{$lang.admin_home}</a> / <a href="tickets.php">{$lang.tickets_title}</a> / {$lang.addticket_title}</span>
    
    <br />
    
    {* Across-Page bar *}
    {include file="$template/bar.tpl"}
      
    <br /><br />
    
    <form action="addticket.php" method="post" name="addclient">
    <table border="0" cellpadding="1" cellspacing="0" width="450" align="center" class="tablez">
      <tr class="table_title" height="20">
        <td align="center" colspan="2">{$lang.addticket_details}</td>
      </tr>
      
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      
      <tr>
        <td colspan="2" align="center"><img src="templates/{$template}/img/icons/tickets_add.png" border="0" /><br />{$lang.addticket_create}</td>
      </tr>
      
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      
      <tr>
        <td align="right" class="description">{$lang.addticket_priority}:&nbsp;</td>
        <td>
	  <select name="priority" style="width:210px">
	    <option value="low">{$lang.addticket_low}</option>
	    <option value="medium" selected>{$lang.addticket_medium}</option>
	    <option value="high">{$lang.addticket_high}</option>
	  </select>
        </td>
      </tr>
      
      <tr>
        <td align="right" class="description">{$lang.addticket_category}:&nbsp;</td>
        <td>
	  <select name="category" style="width:210px">
	    <option value="support" selected>{$lang.addticket_support}</option>
	    <option value="billing">{$lang.addticket_billing}</option>
	    <option value="sales">{$lang.addticket_sales}</option>
	  </select>
        </td>
      </tr>
      
      <tr>
        <td align="right" class="description">{$lang.addticket_subject}:&nbsp;</td>
        <td><input type="text" name="subject" class="textbox_normal" style="width:210px"></td>
      </tr>
            
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      
      <tr>
	<td colspan="2" align="center"><b>{$lang.addticket_text}:</b></td>
      </tr>
      <tr>
        <td height="150" colspan="2" align="center"><textarea name="ticket_text" class="ticket_response"></textarea></td>
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
    <input type="hidden" name="userid" value="{$client_id}" />
    </form>

    {include file="$template/footer.tpl"}

{/if}
