{if $logged_in}

    {include file="$template/header.tpl"}
    {include file="$template/navigation.tpl"}

<br /><br />
<center>
<img src="templates/{$template}/img/icons/template_64.png" border="0" /><br />
{$lang.templates_disp_all}
</center>

<br /><br />

<table border="0" cellpadding="2" cellspacing="0" width="700" align="center" class="tablez">
  <tr class="table_title" height="20">
    <td width="30">&nbsp;</td>
    <td align="left" width="200">{$lang.server}</td>
    <td width="200" align="left">{$lang.description}</td>
    <td align="left">{$lang.date_added}</td>
    <td align="center">{$lang.status}</td>
    <td align="center" width="60">{$lang.templates_default}</td>
  </tr>


  {if $archives}
  {section name=db loop=$archives}
  <tr height="30" bgcolor={if $smarty.section.db.iteration is odd}#e1e1e1{else}#d7d7d7{/if} onClick="window.location='managetemplate.php?id={$archives[db].id}'" onMouseOver="style.cursor='pointer' ; this.style.backgroundColor='#c3c3c3'" onMouseOut=style.cursor='auto';this.style.backgroundColor='{if $smarty.section.db.iteration is odd}#e1e1e1{else}#d7d7d7{/if}'>
    <td><img src="templates/{$template}/img/servers/small/{$archives[db].short_name}.png"></td>
    <td>{$archives[db].long_name|default:'&nbsp;'}</td>
    <td>{$archives[db].description|default:'&nbsp;'}</td>
    <td>{$archives[db].date_created}</td>
    <td align="center">
      {if $archives[db].status eq "complete"}
        <font color="green">{$lang.managetpl_complete}</font>
      {elseif $archives[db].status eq "running"}
        <font color="blue">{$lang.managetpl_installing}</font>
      {else}
        <font color="red">{$lang.status_unknown}</font>
      {/if}
    </td>
    <td align="center">
      {if $archives[db].is_default}
        <b>{$lang.yes}</b>
      {else}
        {$lang.no}
      {/if}
    </td>
  </tr>
  {/section}
  {else}
    <tr>
      <td colspan="4" align="center">{$lang.templates_none_found}</td>
    </tr>
  {/if}
</table>

<br /><br />




<table border="0" cellpadding="2" cellspacing="5" align="center" width="500" style="width:500px;table-layout:fixed;word-wrap:break-word;text-align:center">
  <tr>
    <td align="center"><a href="createarchive.php"><img style="padding-bottom:8px" src="templates/{$template}/img/icons/supported_games_add.png" name="icon_image" width="64" height="64" border="0"><br /><span class="icon_text">{$lang.templates_add}</span></a></td>
  </tr>
</table>





{include file="$template/footer.tpl"}


{/if}
