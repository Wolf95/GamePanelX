{if $logged_in}


    {literal}
    <style type="text/css">
    .tbledit
    {
        padding: 10px;
        border-radius: 6px;
    }
    .tbledit tr td
    {
        height: 30px;
        padding: 2px;
    }
    .textbox_normal
    {
        width: 250px;
        height: 30px;
        background: #FFF;
        color: #444;
        font-family: Arial;
        font-size: 14pt;
        font-weight: normal;
        border: 1px solid #689aff;
        
        border-radius: 4px;
        -moz-border-radius: 4px;
        -webkit-border-radius: 4px;
        
        box-shadow: 2px 2px 4px #CCC;
        -moz-box-shadow: 2px 2px 4px #CCC;
        -webkit-box-shadow: 2px 2px 4px #CCC;
    } 
    .select_normal
    {
        width: 255px;
        height: 30px;
        border: 1px solid #689aff;
        
        border-radius: 4px;
        -moz-border-radius: 4px;
        -webkit-border-radius: 4px;
        
        box-shadow: 2px 2px 4px #CCC;
        -moz-box-shadow: 2px 2px 4px #CCC;
        -webkit-box-shadow: 2px 2px 4px #CCC;
    }
    .description
    {
        font-family: Arial;
        font-size: 9pt;
        font-weight: bold;
        color: #444;
    }
    </style>
    {/literal}
    
    
    
    {section name=db loop=$server_details}
    
    <table border="0" cellpadding="0" cellspacing="0" width="500" align="center" class="tbledit">
      
      <tr>
        <td align="right" class="description" width="160">{$lang.delete}:&nbsp;</td>
        <td><span style="color:blue;font-weight:bold;cursor:pointer;" onClick="javascript:confirmDeleteServer({$server_details[db].serverid});">Click to delete This server</span></td>
      </tr>
      
      <tr>
        <td align="right" class="description" width="160">{$lang.edit_srv_owner}:&nbsp;</td>
        <td>
        
          <select id="srv_userid" class="select_normal">
            {section name=users loop=$user_list}
              
              {if $user_list[users].id eq $server_details[db].userid}
                <option value="{$user_list[users].id}" selected>{$user_list[users].username}</option>
              {else}
                <option value="{$user_list[users].id}">{$user_list[users].username}</option>
              {/if}
            
            {/section}
          </select>
        
        </td>
      </tr>
      <tr>
        <td align="right" class="description">{$lang.status}:&nbsp;</td>
        <td>
          <select id="srver_status" class="select_normal">
            {if $server_details[db].status eq "active"}
                <option value="active" selected>{$lang.status_active}</option>
                <option value="suspended">{$lang.status_suspended}</option>
                <option value="closed">{$lang.status_closed}</option>
            {elseif $server_details[db].status eq "suspended"}
                <option value="active">{$lang.status_active}</option>
                <option value="suspended" selected>{$lang.status_suspended}</option>
                <option value="closed">{$lang.status_closed}</option>
            {elseif $server_details[db].status eq "closed"}
                <option value="active">{$lang.status_active}</option>
                <option value="suspended">{$lang.status_suspended}</option>
                <option value="closed" selected>{$lang.status_closed}</option>
            {else}
                <option value=""></option>
                <option value="active" selected>{$lang.status_active}</option>
                <option value="suspended">{$lang.status_suspended}</option>
                <option value="closed">{$lang.status_closed}</option>
            {/if}
          </select>
        </td>
      </tr>
      
      <tr>
        <td align="right" class="description" width="160">{$lang.edit_srv_server_name}:&nbsp;</td>
        <td><b>{$server_details[db].long_name}</b></td>
      </tr>
      <tr>
        <td align="right" class="description">{$lang.edit_srv_date_created}:&nbsp;</td>
        <td>{$server_details[db].date_created}</td>
      </tr>
            
      <tr>
        <td align="right" class="description">{$lang.edit_srv_description}:&nbsp;</td>
        <td><input type="text" id="srv_description" class="textbox_normal" value="{$server_details[db].description|stripslashes}"></td>
      </tr>
      
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      
      <tr>
        <td align="right" class="description">Subdomain:&nbsp;</td>
        <td><input type="text" id="srv_subdomain" class="textbox_normal" value="{$server_details[db].subdomain|stripslashes}"></td>
      </tr>
      <tr>
        <td align="right" class="description">Domain:&nbsp;</td>
        <td>
          <select id="srv_domainid" class="select_normal">
              {if $domains}
                  <option value="">{$lang.none}</option>
                  {section name=dns loop=$domains}
                      {if $server_details[db].domainid eq $domains[dns].id}
                        <option value="{$domains[dns].id}" selected>{$domains[dns].domain}</option>
                      {else}
                        <option value="{$domains[dns].id}">{$domains[dns].domain}</option>
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
        <td align="right" class="description">{$lang.edit_srv_ip}:&nbsp;</td>
        <td>
          <select id="srv_ip" class="select_normal">
            {section name=ips loop=$network_ips}
              {if $network_ips[ips].id eq $server_details[db].networkid}
                <option value="{$network_ips[ips].id}" selected>{$network_ips[ips].ip} - {$network_ips[ips].description} ({$network_ips[ips].location})</option>
              {else}
                <option value="{$network_ips[ips].id}">{$network_ips[ips].ip} - {$network_ips[ips].description} ({$network_ips[ips].location})</option>
              {/if}
            {/section}
          </select>
        </td>
      </tr>
      
      <tr>
        <td align="right" class="description">{$lang.edit_srv_port}:&nbsp;</td>
        <td><input type="text" id="srv_port" class="textbox_normal" value="{$server_details[db].port}"></td>
      </tr>
      <tr>
        <td align="right" class="description">{$lang.edit_srv_log_file}:&nbsp;</td>
        <td><input type="text" id="srv_log_file" class="textbox_normal" value="{$server_details[db].log_file|stripslashes}"></td>
      </tr>
      <tr>
        <td align="right" class="description">{$lang.edit_srv_max_slots}:&nbsp;</td>
        <td><input type="text" id="srv_max_slots" class="textbox_normal" value="{$server_details[db].max_slots}"></td>
      </tr>
      <tr>
        <td align="right" class="description">{$lang.edit_srv_map}:&nbsp;</td>
        <td><input type="text" id="srv_map" class="textbox_normal" value="{$server_details[db].map|stripslashes}"></td>
      </tr>
      <tr>
        <td align="right" class="description">{$lang.edit_srv_exe}:&nbsp;</td>
        <td><input type="text" id="srv_executable" class="textbox_normal" value="{$server_details[db].executable|stripslashes}"></td>
      </tr>

      <tr>
        <td align="right" class="description">{$lang.edit_srv_working_dir}:&nbsp;</td>
        <td><input type="text" id="srv_working_dir" class="textbox_normal" value="{$server_details[db].working_dir|stripslashes}"></td>
      </tr>
      <tr>
        <td align="right" class="description">{$lang.edit_srv_setup_dir}:&nbsp;</td>
        <td><input type="text" id="srv_setup_dir" class="textbox_normal" value="{$server_details[db].setup_dir|stripslashes}"></td>
      </tr>
      <tr>
        <td align="right" class="description">Rcon Password:&nbsp;</td>
        <td><input type="text" id="srv_rcon" class="textbox_normal" value="{$server_details[db].rcon_password|stripslashes}"></td>
      </tr>
      
      
      
      <tr>
        <td align="right" class="description">{$lang.edit_srv_client_file_man}:&nbsp;</td>
        <td>
          <select id="srv_client_file_man" class="select_normal">
            {if $server_details[db].client_file_man eq "Y"}
                <option value="Y" selected>{$lang.yes}</option>
                <option value="N">{$lang.no}</option>
            {elseif $server_details[db].client_file_man eq "N"}
                <option value="Y">{$lang.yes}</option>
                <option value="N" selected>{$lang.no}</option>
            {/if}
          </select>
        </td>
      </tr>
      
      <tr>
        <td align="right" class="description">Enable Server Logging:&nbsp;</td>
        <td>
          <select id="srv_logging" class="select_normal">
            {if $server_details[db].logging eq "1"}
                <option value="1" selected>{$lang.yes}</option>
                <option value="0">{$lang.no}</option>
            {else}
                <option value="1">{$lang.yes}</option>
                <option value="0" selected>{$lang.no}</option>
            {/if}
          </select>
        </td>
      </tr>
      
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      
      <tr>
        <td align="center" colspan="2" class="description">
          Edit Raw Command-Line:<br />
          <textarea id="srv_cmd_line" style="width:95%;height:100px;border-radius:8px;-moz-border-radius:8px;-webkit-border-radius:8px;padding:8px;font-family:Arial;font-size:11pt;color:#444;">{$server_details[db].cmd_line|stripslashes}</textarea><br />
          <font color="red">WARNING: This command-line will be overwritten when the Startup Editor is saved.</font>
        </td>
      </tr>

      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      
      <tr>
        <td align="center" colspan="2" class="description">
          {$lang.private_notes}:<br />
          <textarea id="srv_notes" style="width:95%;height:100px;border-radius:8px;-moz-border-radius:8px;-webkit-border-radius:8px;padding:8px;font-family:Arial;font-size:11pt;color:#444;">{$server_details[db].notes|stripslashes}</textarea>
        </td>
      </tr>

      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      
      <tr>
        <td colspan="2" align="center"><input type="button" id="srv_button_update" value=" " class="button_save" onClick="javascript:saveClientServerDetails();" /></td>
      </tr>
      
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
    </table>
    <input type="hidden" id="serverid" value="{$server_details[db].serverid}">
    {/section}

{/if}
