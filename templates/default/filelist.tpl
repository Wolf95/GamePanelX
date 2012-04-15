{if $logged_in}

{literal}
<style type="text/css">
#adddir
{
    width: 400px;
    height: 30px;
    background: #E0E0E0;
    border: 1px solid #999;
    padding: 8px;
    margin-top: 10px;
    margin-bottom: 10px;
    
    border-radius: 6px;
    -moz-border-radius: 6px;
    -webkit-border-radius: 6px;
}
.savebtn
{
    width: 100px;
    height: 30px;
    line-height: 30px;
    font-weight: bold;
    background: #CCC;
    border: 1px solid #666;
    cursor: pointer;
    
    border-radius: 6px;
    -moz-border-radius: 6px;
    -webkit-border-radius: 6px;
}
</style>
{/literal}


<table border="0" cellpadding="0" cellspacing="0" width="250" align="center">
<tr>
  <td align="center">
    {if $srvroot}
      &nbsp;
    {else}
      <span style="cursor:pointer;" onClick="javascript:filesLoad('{$srvid}','','0');"><img src="templates/{$template}/img/fm/back-64.png" width="32" height="32" border="0" /></span>
    {/if}
  </td>
  <td align="center">&nbsp;</td>
</tr>
<tr>
  <td align="center" class="normal">
    {if $srvroot}
      &nbsp;
    {else}
      <span style="cursor:pointer;" onClick="javascript:filesLoad('{$srvid}','','0');">{$lang.back}</span>
    {/if}
  </td>
  <td align="center" class="normal">&nbsp;</td>
</tr>
</table>

<table border="0" cellpadding="1" cellspacing="0" width="550" align="center" class="tablez">
<tr class="table_title" height="20">
  <td width="30">&nbsp;</td>
  <td align="left">{$lang.filemanager_name}</td>
  <td align="left" width="40">{$lang.filemanager_size}</td>
  <td align="left">{$lang.filemanager_last_mod}</td>
  <td align="left">{$lang.filemanager_perms}</td>
  <td width="30" align="center">&nbsp;</td>
</tr>

{if $file_list}
{section name=db loop=$file_list}
<tr class="normal" id="del_{$file_list[db].keyid}">
  <td><img src="templates/{$template}/img/fm/{$file_list[db].file_icon}" width="20" height="20" border="0" /></td>
  <td><span style="font-size:9pt;color:red;cursor:pointer;" onClick="javascript:filesLoad('{$srvid}','{$file_list[db].file_enc_name}','0');">{$file_list[db].file_name}</span></td>
  <td>
    {if !$file_list[db].file_is_dir}
      {$file_list[db].file_size}
    {else}
      &nbsp;
    {/if}
  </td>
  <td>{$file_list[db].file_date}</td>
  <td>{$file_list[db].file_perms}</td>
  <td align="center">
    {if !$file_list[db].file_is_dir}
        <img src="templates/{$template}/img/fm/delete.png" title="Delete File" width="16" height="16" border="0" onClick="javascript:confirmDeleteFile('{$srvid}','{$prev_dir}','{$file_list[db].file_enc_name}','{$file_list[db].keyid}');" style="cursor:pointer;" />
    {else}
      &nbsp;
    {/if}
  </td>
</tr>
{/section}
{else}
  <tr>
    <td colspan="6" align="center">{$lang.filemanager_no_files}</td>
  </tr>
{/if}

<tr>
  <td colspan="2">&nbsp;</td>
</tr>
</table>
</form>


{/if}
