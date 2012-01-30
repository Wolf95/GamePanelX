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
        <td align="right" class="description">{$lang.edit_srv_map}:&nbsp;</td>
        <td><input type="text" id="srv_map" class="textbox_normal" value="{$server_details[db].map|stripslashes}"></td>
      </tr>
      <tr>
        <td align="right" class="description">Rcon Password:&nbsp;</td>
        <td><input type="text" id="srv_rcon" class="textbox_normal" value="{$server_details[db].rcon_password|stripslashes}"></td>
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
