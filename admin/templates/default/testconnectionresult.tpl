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
    <table border="0" cellpadding="1" cellspacing="0" width="400" align="center" class="tablez">
      <tr class="table_title" height="20">
        <td align="center" colspan="2">{$lang.testconnresult_title}</td>
      </tr>
      
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      
      <tr>
        <td colspan="2" align="center">
	
	{if $conn_result eq "success"}
	  <font color="green"><b>{$lang.testconnresult_success}!</b><br />{$lang.testconnresult_success_msg}.</font>
	{else}
	  <font color="red">{$lang.testconnresult_failed}:</font><br /><br />{$conn_result}
	{/if}
	
	</td>
      </tr>
      
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
    </table>
    </form>

    {include file="$template/footer.tpl"}

{/if}
