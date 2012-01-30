{include file="$template/header.tpl"}

<br /><br />

<table border="0" cellpadding="1" cellspacing="0" align="center" width="400" class="tablez">
  <tr class="table_title">
    <td align="center" height="20" colspan="2">{$lang.error_occured}</td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
    
  {if $error != ""}
    <tr>
      <td colspan="2" align="center"><b>{$lang.error}:</b> {$error}</td>
    </tr>
    <tr>
      <td colspan="2">&nbsp;</td>
    </tr>
  {/if}
  
  {if $url_back != ""}
    <tr>
      <td colspan="2" align="center"><a href="{$url_back}">{$lang.error_go_back}</a></td>
    </tr>
    <tr>
      <td colspan="2">&nbsp;</td>
    </tr>
  {/if}
</table>

{include file="$template/footer.tpl"}
