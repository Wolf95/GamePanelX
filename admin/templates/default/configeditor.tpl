{if $logged_in}

    {include file="$template/header.tpl"}
    {include file="$template/navigation.tpl"}

    {* Location Links *}
    <span class="location">&nbsp;&nbsp;<a href="main.php">{$lang.admin_home}</a> / <a href="servers.php">{$lang.servers}</a> / <a href="manageserver.php?id={$serverid}">{$lang.managesrv_nav_man_srv}</a> / <a href="filemanager.php?id={$serverid}&p={$coded_file_path}">{$lang.filemanager_title}</a> / {$lang.cfgeditor_title}
    
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
    
    <br /><br />
    
    {literal}
    <script language="JavaScript">
    document.configeditor.username.style.display = 'none';
    </script>
    {/literal}
    
    <form action="configeditor.php?id={$serverid}&p={$coded_file_path}&f={$coded_file_name}" method="post" name="addclient">
    <table border="0" cellpadding="1" cellspacing="0" width="600" align="center" class="tablez">
      <tr class="table_title" height="20">
        <td align="center" colspan="2">{$lang.cfgeditor_desc}</td>
      </tr>
      
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      
      <tr>
        <td colspan="2" align="center"><a href="javascript:void(0)" onClick="window.location='filemanager.php?id={$serverid}&p={$coded_file_path}'"><img src="templates/{$template}/img/fm/back-64.png" width="32" height="32" border="0" /></a><br /><a href="javascript:history.go(-1)">{$lang.back}</a></td>
      </tr>
      
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      
      <tr>
        <td colspan="2" align="center">{$lang.editing} <b>{$file_path|stripslashes|default:'&nbsp;'}</b></td>
      </tr>
      
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      
      <tr>
        <td colspan="2" align="center" height="300"><textarea name="file_contents" id="file_contents" style="width:100%;height:100%">{$file_contents|stripslashes}</textarea></td>
      </tr>
      
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      
      <tr>
        <td colspan="2" align="center"><input type="submit" name="update" value=" " class="button_save" /></td>
      </tr>
      
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
    </table>
    </form>

    {include file="$template/footer.tpl"}

{/if}
