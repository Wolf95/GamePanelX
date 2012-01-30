{if $logged_in}

    {include file="$template/header.tpl"}
    {include file="$template/navigation.tpl"}

    {* Location Links *}
    <span class="location">&nbsp;&nbsp;<a href="main.php">{$lang.admin_home}</a> / <a href="network.php">{$lang.network}</a> / <a href="managenetworkserver.php?id={$network_id}">{$lang.managenetsrv_title}</a> / {$lang.remoteinfo_title}
    
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
        <td align="right" class="description" width="160"><img src="templates/{$template}/img/icons/cpu_24.png" border="0" width="16" height="16" />&nbsp;{$lang.remoteinfo_load_avg}:&nbsp;</td>
        <td>{$remote_load_avg}</td>
      </tr>
      
      <tr>
        <td align="right" class="description"><img src="templates/{$template}/img/icons/cpu_24.png" border="0" width="16" height="16" />&nbsp;{$lang.remoteinfo_cpu_info}:&nbsp;</td>
        <td>{$remote_cpu_type}</td>
      </tr>
      
      <tr>
        <td align="right" class="description"><img src="templates/{$template}/img/icons/cpu_24.png" border="0" width="16" height="16" />&nbsp;{$lang.remoteinfo_total_cpus}:&nbsp;</td>
        <td>{$remote_cpu_total}</td>
      </tr>
      
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      
      <tr>
        <td align="right" class="description"><img src="templates/{$template}/img/icons/memory_24.png" border="0" width="16" height="16" />&nbsp;{$lang.remoteinfo_mem_total} (Mb):&nbsp;</td>
        <td>{$remote_mem_total}</td>
      </tr>
      
      <tr>
        <td align="right" class="description"><img src="templates/{$template}/img/icons/memory_24.png" border="0" width="16" height="16" />&nbsp;{$lang.remoteinfo_mem_used} (Mb):&nbsp;</td>
        <td>{$remote_mem_used}</td>
      </tr>
      
      <tr>
        <td align="right" class="description"><img src="templates/{$template}/img/icons/memory_24.png" border="0" width="16" height="16" />&nbsp;{$lang.remoteinfo_mem_free} (Mb):&nbsp;</td>
        <td>{$remote_mem_free}</td>
      </tr>
      
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      
      <tr>
        <td colspan="2" align="left" style="padding-left:40px" class="description"><img src="templates/{$template}/img/icons/partition_24.png" border="0" width="16" height="16" />&nbsp;{$lang.remoteinfo_disk_usage}</td>
      </tr>
      
      <tr>
        <td colspan="2" align="left" style="padding-left:50px"><pre>{$remote_disk_usage}</pre></td>
      </tr>
      
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>

      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
    </table>

    {include file="$template/footer.tpl"}

{/if}
