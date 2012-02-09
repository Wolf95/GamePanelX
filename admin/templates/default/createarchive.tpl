{if $logged_in}



{include file="$template/header.tpl"}
{include file="$template/navigation.tpl"}
{include file="$template/bar.tpl"}
  


{literal}
<style type="text/css">
#addtpl_step1
{
    width: 400px;
    height: 100px;
    padding: 8px;
    display: table;
    text-align: left;
}
#sel_net_srv
{
    width: 100%;
    height: 40px;
    display: table;
}
#createtpl_tbl
{
    width: 480px;
    padding: 4px;
}
#createtpl_tbl td
{
    height: 30px;
    font-size: 14pt;
    font-weight: bold;
    color: #777;
}
</style>

<script type="text/javascript">
$(document).ready(function(){
    // Click radio options
    // $('.addtpl_rad').click(function(){
    $('#startbtn').click(function(){
        //var thisID      = $(this).attr('id');
        var thisSrvID   = $('#netsrv').val();
        var serverType  = $('#game').val();
        var description = $('#description').val();
        
        if($('#rad_1').is(':checked'))
        {
            var thisID = 'rad_1';
        }
        else if($('#rad_2').is(':checked'))
        {
            var thisID = 'rad_2';
        }
        else
        {
            alert('You must select an install method!');
            return false;
        }
        
        if(thisSrvID == "")
        {
            alert("You must choose a Network Server");
            return false;
        }
        else if(serverType == "")
        {
            alert("You must choose a game/voice server");
            return false;
        }
        
        // -------------------------------------------------------------
        
        // Directory-based
        if(thisID == 'rad_1')
        {
            $('#addtpl_step1').hide();
            $('#files').show();
            filesLoadNet(thisSrvID,'','1');
        }
        // Supported Install
        else if(thisID == 'rad_2')
        {
            $('#addtpl_step1').hide();
            installSupportedServer();
        }
        else
        {
            $('#addtpl_step1').hide();
            $('#addtpl_auto').hide().html('Unknown option chosen').fadeIn();
        }
    });
});
</script>
{/literal}

<div align="center">
    <div id="srv_action_result" style="margin-bottom:20px;font-weight:bold;color:green;"></div>
</div>

<div align="center">
    
    <div id="sel_net_srv">
    
        <table border="0" cellpadding="0" cellspacing="0" align="center" id="createtpl_tbl">
        <tr>
          <td width="200">Network Server:</td>
          <td>
              <select id="netsrv" style="width:220px;height:30px;">
                <option value="">Select a network server</option>
                
                {section name=db loop=$network_servers}
                    <option value="{$network_servers[db].id}">{$network_servers[db].ip} ({$network_servers[db].description})</option>
                {/section}
                
              </select>
          </td>
        </tr>
        
        <tr>
          <td>Server:</td>
          <td>
              <select id="game" style="width:220px;height:30px;">
                <option value="">Select a server type</option>
                
                {section name=sv loop=$servers}
                    <option value="{$servers[sv].id}">{$servers[sv].long_name}</option>
                {/section}
                
              </select>
          </td>
        </tr>
        
        <tr>
          <td>Description:</td>
          <td><input type="text" id="description" class="textbox_normal" style="width:220px;" /></td>
        </tr>
        
        <tr>
          <td>Default:</td>
          <td style="font-size:9pt;font-weight:normal;color:#444;"><input type="checkbox" id="is_default" checked /> <label for="is_default">Set as the default archive for this server type</label></td>
        </tr>
        </table>
        
    </div>
    
    <div id="addtpl_step1">
        <input type="radio" name="choice" id="rad_1" class="addtpl_rad" /> <label for="rad_1" style="cursor:pointer;"><b>Create from Directory (faster)</b></label><br /><label for="rad_1" style="cursor:pointer;">Create the archive from a directory on the remote server. This directory should already have the server files.</label><br /><br />
        
        <input type="radio" name="choice" id="rad_2" class="addtpl_rad" /> <label for="rad_2" style="cursor:pointer;"><b>Automatic Creation (slower)</b></label><br /><label for="rad_2" style="cursor:pointer;">Currently for Steam&copy; games only.  This will use the Steam&copy; installer to auto-install the gameserver via the internet.</label>
    <br /><br />
    
    <div align="center">
    <input type="submit" id="startbtn" value=" " class="button_create" />
    </div>
    
    </div>    
    
    
    <div id="files" style="display:none;"></div>
    <div id="addtpl_auto" style="display:none;"></div>
</div>






{include file="$template/footer.tpl"}

{/if}
