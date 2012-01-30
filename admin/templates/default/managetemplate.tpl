{if $logged_in}

    {include file="$template/header.tpl"}
    {include file="$template/navigation.tpl"}
    
    <div align="center">
        <div id="info" style="display:none;"></div>
    </div>
    
    {section name=db loop=$template_details}
    
        
    {literal}
    <script type="text/javascript">
    function confirmDeleteArchive(archiveID)
    {
        var answer = confirm({/literal}"{$lang.managetpl_confirm_delete}"{literal})
        if (answer)
        {
            archiveDelete(archiveID);
        }
    }
    function archiveDelete(archiveID)
    {
        if(archiveID == "")
        {
            alert("No archive specified!");
            return false;
        }
        
        $.ajax({
            type: "GET",
            url: "../include/ajdb.php",
            data: "a=archive_delete&id="+archiveID,
            success: function(html){
                if(html == 'success')
                {
                    window.location = 'archives.php';
                    return false;
                }
                else
                {
                    $('#info').hide().html('An error occured: '+html).fadeIn();
                }
                
            },
            error: function(jqXHR, textStatus, errorThrown){
                alert("Ajax Error: "+errorThrown);
            }
        });
    }
    
    function arcEdit()
    {
        $('.arc_txt').toggle();
        $('.arc_input').toggle();
        $('#savearchive').toggle();
    }
    
    function archiveSave(archiveID)
    {
        var netID     = $('#netsrv').val();
        var cfgID     = $('#game').val();
        var desc      = $('#description').val();
                
        if($('#is_default').is(':checked'))
        {
            var isDefault = '1';
        }
        else
        {
            var isDefault = '0';
        }
          
        
        if(archiveID == "")
        {
            alert("No archive specified!  Please try again");
            return false;
        }
        else if(netID == "" || cfgID == "")
        {
            alert("You must select a Network Server and a Server Type!");
            return false;
        }
        else if(desc == "")
        {
            var desc = "";
        }
        
        $.ajax({
            type: "GET",
            url: "../include/ajdb.php",
            data: "a=archive_save&id="+archiveID+"&cfgid="+cfgID+"&networkid="+netID+"&description="+desc+"&is_default="+isDefault,
            success: function(html){
                if(html == 'success')
                {
                    $('#info').hide().html('Saved!').fadeIn();
                }
                else
                {
                    $('#info').hide().html('An error occured: '+html).fadeIn();
                }
                
            },
            error: function(jqXHR, textStatus, errorThrown){
                alert("Ajax Error: "+errorThrown);
            }
        });
    }
    
    function archiveStatus(archiveID)
    {
        if(archiveID == "")
        {
            alert("No archive specified");
            return false;
        }
        
        $.ajax({
            type: "GET",
            url: "../include/ajdb.php",
            data: "a=archive_status&id="+archiveID,
            beforeSend:function(){
                $('#arc_status').html('<i>Checking ...</i>').fadeIn();
            },
            success: function(html){
                
                // Archives
                if(html == 'archive_running')
                {
                    $('#arc_status').html('<font color="orange"><b>Step 2/2:</b> Archive is still creating.</font>').fadeIn();
                }
                else if(html == 'archive_complete')
                {
                    $('#arc_status').html('<font color="green"><b>Step 2/2:</b> Archive is complete!</font>').fadeIn();
                }
                
                // Installations
                else if(html == 'install_running')
                {
                    $('#arc_status').html('<font color="orange"><b>Step 1/2:</b> Installer is still running ...</font>').fadeIn();
                }
                else if(html == 'install_complete')
                {
                    $('#arc_status').html('<font color="green"><b>Step 1/2:</b> Installer is complete!</font>').fadeIn();
                }
                
                
                else
                {
                    //$('#info').hide().html('An error occured: '+html).fadeIn();
                    $('#arc_status').html('<span style="font-size:9pt;font-weight:bold;color:blue;">'+html+'</span>').fadeIn();
                }
                
            },
            error: function(jqXHR, textStatus, errorThrown){
                alert("Ajax Error: "+errorThrown);
            }
        });
    }
    
    function archiveInfo(archiveID)
    {
        $.ajax({
            type: "GET",
            url: "../include/ajdb.php",
            data: "a=template_info&id="+archiveID,
            success: function(html){
                if(html == 'no')
                {
                    $('#arc_status').html('<font color="red"><b>Not found on remote server!</b></font>');
                }
                else $('#arc_status').append(' ('+html+')');
            },
            error: function(jqXHR, textStatus, errorThrown){
                alert("Ajax Error: "+errorThrown);
            }
        });
    }
    {/literal}
    
    {if $template_details[db].status ne "complete" || $template_details[db].installation_status eq "running"}    
        {literal}
        $(document).ready(function(){
            setTimeout("archiveStatus({/literal}{$archiveid}{literal})", 1000);
        });
        {/literal}
    {else}
        {literal}
        $(document).ready(function(){
            archiveInfo({/literal}{$archiveid}{literal});
        });
        {/literal}
    {/if}
    </script>
    
    <br /><br />
    
    <div align="center">{$lang.managetpl_details}</div>
        
    <br />
        
    
    <table border="0" cellpadding="1" cellspacing="0" width="600" align="center">
      <tr>
        <td colspan="2" align="center"><img src="templates/{$template}/img/servers/medium/{$template_details[db].short_name}.png"><br /><span style="font-size:12px"><b>{$template_details[db].long_name}</b></span><br /><br /></td>
      </tr>
            
      
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      
      <tr>
        <td align="right" width="40%"><b>Date Created:</b>&nbsp;</td>
        <td align="left">
            <span>{$template_details[db].date_created|default:'&nbsp;'}</span>
        </td>
      </tr>
      
      <tr>
        <td align="right" width="40%"><b>{$lang.status}:</b>&nbsp;</td>
        <td align="left">
            <span id="arc_status">
                {if $template_details[db].status eq "complete"}
                    <font color="green">Complete</font>
                {/if}
            </span>
        </td>
      </tr>
      
      <tr>
        <td align="right" width="40%"><b>{$lang.description}:</b>&nbsp;</td>
        <td align="left">
            <span class="arc_txt">{$template_details[db].description|stripslashes|default:'&nbsp;'}</span>
            <span class="arc_input" style="display:none;"><input type="text" class="textbox_normal" id="description" value="{$template_details[db].description|stripslashes}" /></span>
        </td>
      </tr>
      
      <tr>
        <td align="right" width="40%"><b>{$lang.managetpl_net_srv}:</b>&nbsp;</td>
        <td align="left">
            <span class="arc_txt">
            {if $template_details[db].networkdesc}
                {$template_details[db].networkdesc|stripslashes|default:'&nbsp;'} ({$template_details[db].ip|default:'&nbsp;'})
            {else}
                {$template_details[db].ip|default:'&nbsp;'}
            {/if}
            </span>
            <span class="arc_input" style="display:none;">
              <select id="netsrv" style="width:220px;height:30px;">
                {section name=nt loop=$network_servers}
                    {if $template_details[db].networkid eq $network_servers[nt].id}
                    <option value="{$network_servers[nt].id}" selected>{$network_servers[nt].ip} ({$network_servers[nt].description})</option>
                    {else}                    
                    <option value="{$network_servers[nt].id}">{$network_servers[nt].ip} ({$network_servers[nt].description})</option>
                    {/if}
                {/section}
                
              </select>
            </span>
        </td>
      </tr>
      <tr>
        <td align="right" width="40%"><b>{$lang.server}:</b>&nbsp;</td>
        <td align="left">
            <span class="arc_txt">{$template_details[db].long_name|default:'&nbsp;'}</span>
            <span class="arc_input" style="display:none;">
              <select id="game" style="width:220px;height:30px;">
                <option value="">Choose a server</option>
                
                {section name=sv loop=$servers}
                    {if $template_details[db].cfgid eq $servers[sv].id}
                    <option value="{$servers[sv].id}" selected>{$servers[sv].long_name}</option>
                    {else}
                    <option value="{$servers[sv].id}">{$servers[sv].long_name}</option>
                    {/if}
                {/section}
                
              </select>
            </span>
        </td>
      </tr>
      
      <tr>
        <td align="right" width="40%"><b>Created from:</b>&nbsp;</td>
        <td align="left">
            <span>{$template_details[db].file_path|default:'&nbsp;'}</span>
        </td>
      </tr>
      
      <tr>
        <td align="right" width="40%"><b>Default for this server type:</b>&nbsp;</td>
        <td align="left">
            <span class="arc_txt">
            {if $template_details[db].is_default}
              <font color="green">Yes</font>
            {else}
              <font color="red">No</font>
            {/if}
            </span>
            <span class="arc_input" style="display:none;">
                {if $template_details[db].is_default}
                  <input type="checkbox" id="is_default" value="1" checked />
                {else}
                  <input type="checkbox" id="is_default" value="1" />
                {/if}
                
                <label for="is_default">Set as default for this server type</label></span>
        </td>
      </tr>
      
      
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      
      <tr>
        <td colspan="2" align="center"><input type="button" id="savearchive" class="button_save" style="display:none;" onClick="javascript:archiveSave({$archiveid});" /></td>
      </tr>
      
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
    </table>
    {/section}
    
    <br />
    
    <div align="center" style="cursor:pointer;color:blue;" onClick="javascript:arcEdit();">Click to edit template values</div><br />
    <div align="center" style="cursor:pointer;color:blue;" onClick="javascript:confirmDeleteArchive({$archiveid});">Delete this Archive</div>
    
    
    <br />    


    {include file="$template/footer.tpl"}

{/if}
