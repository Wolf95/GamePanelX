{if $logged_in}

    {include file="$template/header.tpl"}
    {include file="$template/navigation.tpl"}

    {* Location Links *}
    <span class="location">&nbsp;&nbsp;<a href="main.php">{$lang.admin_home}</a> / <a href="defaultservers.php">{$lang.suppsrv_title}</a> / {$lang.xmlimport_title}</span>
    
    <br />
    
    {* Across-Page bar *}
    {include file="$template/bar.tpl"}
      
    <br /><br />
    
    <form action="xmlimport.php" method="post" name="xmlImport" enctype="multipart/form-data">
    <table border="0" cellpadding="1" cellspacing="0" width="400" align="center" class="tablez">
      <tr class="table_title" height="20">
        <td align="center" colspan="2">{$lang.xmlimport_title}</td>
      </tr>
      
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      
      <tr>
        <td colspan="2" align="center"><img src="templates/{$template}/img/icons/xmlfile.png" border="0" /></td>
      </tr>
      
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      
      <tr>
        <td align="right" class="description">{$lang.xmlimport_sys_type}:&nbsp;</td>
        <td>
          <select name="system_type" style="width:170px">
            <option value="gpx">GamePanelX</option>
          </select>
        </td>
      </tr>
      <tr>
        <td align="right" class="description">{$lang.xmlimport_upload}:&nbsp;</td>
        <td><input name="xml_file" type="file" /></td>
      </tr>
      
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      
      <tr>
        <td colspan="2" align="center">
            <input type="submit" name="create" id="create" value="{$lang.xmlimport_button_submit}" style="width:170px" />
        </td>
      </tr>
      
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
    </table>
    <input type="hidden" name="MAX_FILE_SIZE" value="1000000" />
    </form>

    {include file="$template/footer.tpl"}

{/if}
