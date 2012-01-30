{if $logged_in}

    {include file="$template/header.tpl"}
    {include file="$template/navigation.tpl"}

    {* Location Links *}
    <span class="location">&nbsp;&nbsp;<a href="main.php">{$lang.admin_home}</a> / <a href="servers.php">{$lang.servers}</a> / Steam Update</span>
    
    <br />

    {* Across-Page bar *}
    {include file="$template/bar.tpl"}
      
    <br /><br />
    
    {if $infobox}
    <table border="0" cellpadding="0" cellspacing="0" width="600" align="center" class="infobox">
      <tr>
        <td align="center"><br />{$infobox}<br /><br /></td>
      </tr>
    </table><br />
    {/if}

    <table border="0" cellpadding="1" cellspacing="0" width="600" align="center" class="tablez">
      <tr class="table_title" height="20">
        <td align="center" colspan="2">{$lang.remoteinfo_details}</td>
      </tr>
      
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>

      <tr>
        <td colspan="2" align="center">Steam Update successfully started.<br /><br /><a href="manageserver.php?id={$server_id}">Click here to go back</a></td>
      </tr>

      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
    </table>

    {include file="$template/footer.tpl"}

{/if}
