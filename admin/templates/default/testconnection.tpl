{if $logged_in}

    {include file="$template/header.tpl"}
    {include file="$template/navigation.tpl"}

    {* Location Links *}
    <span class="location">&nbsp;&nbsp;<a href="main.php">{$lang.admin_home}</a> / {$lang.testconn_title}
    
    <br />
    
    {* Across-Page bar *}
    {include file="$template/bar.tpl"}
      
    <br /><br />
    
    
    {literal}
    <script language="JavaScript">
    document.testconnection.username.style.display = 'none';
    </script>
    {/literal}
    
    <form action="testconnection.php" method="post" name="testconnection">
    <table border="0" cellpadding="1" cellspacing="0" width="500" align="center" class="tablez">
      <tr class="table_title" height="20">
        <td align="center" colspan="2">{$lang.testconn_title}</td>
      </tr>
      
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      
      <tr>
        <td colspan="2" align="center"><img src="templates/{$template}/img/icons/network.png" width="64" height="64" border="0" /></td>
      </tr>
      
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      
      <tr>
        <td align="right" class="description">{$lang.network_server}:&nbsp;</td>
        <td>
	  
	  <select name="network_server">
	    {if $network_servers}
	    {section name=db loop=$network_servers}
	      <option value="{$network_servers[db].id}">
          {if $network_servers[db].description}
              {$network_servers[db].description|stripslashes|substr:0:25} ({$network_servers[db].ip})</option>
          {else}
              {$network_servers[db].ip}
          {/if}
	    {/section}
	    {else}
	      <option value="">{$lang.none}</option>
	    {/if}
	  </select>
	
	</td>
      </tr>
      
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>

      <tr>
        <td colspan="2" align="center"><input type="submit" name="submit" value="{$lang.testconn_button_submit}" style="width:170px" /></td>
      </tr>
      
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
    </table>
    </form>

    {include file="$template/footer.tpl"}

{/if}
