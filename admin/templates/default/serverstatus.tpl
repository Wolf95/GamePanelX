{if $logged_in}


{if $cur_numplayers}
    {literal}
    <script type="text/javascript">
    $(document).ready(function(){
        rconStatus({/literal}{$srvid}{literal});
    });
    </script>
    {/literal}
{/if}


{literal}
<script type="text/javascript">
function rconCMD(serverID)
{
    var rconcmd = $('#rcon_cmd').val();
    
    if(serverID)
    {
        $.ajax({
          type: "GET",
          url: "../include/ajdb.php",
          data: "a=rcon_server&id="+serverID+"&cmd="+rconcmd,
          beforeSend:function(){
              $('#rcon_result').hide().html('<i>Running ...</i>').fadeIn();
          },
          success: function(html){
              $('#rcon_result').hide().html(html).fadeIn();
          },
          error: function(jqXHR, textStatus, errorThrown){
              alert("Rcon Error: "+errorThrown);
          }
      });
    }
    else
    {
        alert("No serverid provided");
    }
}
function rconStatus(serverID)
{
    if(serverID)
    {
        $.ajax({
            type: "GET",
            url: "../include/ajdb.php",
            data: "a=rcon_status&id="+serverID,
            beforeSend:function(){
                $('#rcon_players').hide().html('<i>Fetching player listing ...</i>').fadeIn();
            },
            success: function(html){
                $('#rcon_players').hide().html(html).fadeIn();
            },
            error: function(jqXHR, textStatus, errorThrown){
                alert("Rcon Error: "+errorThrown);
            }
        });
    }
    else
    {
        alert("No serverid provided");
    }
}

function rconKickCS(serverID)
{
    //var playerList  = $('input:checkbox[name=playerid]:checked')); 
    var data = { 'playerid[]' : []};
    $(":checked").each(function() {
      data['playerid[]'].push($(this).val());
    });
    
    $.ajax({
        type: "GET",
        url: "../include/ajdb.php",
        data: "a=rcon_kick&id="+serverID+data;
        success: function(html){
            alert("Kicked: "+html);
        },
        error: function(jqXHR, textStatus, errorThrown){
            alert("Rcon Error: "+errorThrown);
        }
    });
}
</script>

<style type="text/css">
#rcon_result
{
    width: 500px;
    height: 40px;
    font-family: Verdana;
    font-size: 9pt;
    color: #333;
    padding: 8px;
    background: #DDD;
    border: 1px solid #999;
    display: table;
    margin-top: 10px;
    margin-bottom: 5px;
    text-align: left;
    
    border-radius: 8px;
    -moz-border-radius: 8px;
    -webkit-border-radius: 8px;
}
#rcon_players
{
    width: 500px;
    height: 40px;
    font-family: Verdana;
    font-size: 9pt;
    color: #333;
    padding: 8px;
    /* background: #D5D5D5;
    border: 1px solid #999; */
    display: table;
    margin-top: 10px;
    margin-bottom: 5px;
    text-align: left;
    
    border-radius: 8px;
    -moz-border-radius: 8px;
    -webkit-border-radius: 8px;
}
#steam_update_btn
{
    width: 200px;
    height: 30px;
    cursor: pointer;
}
</style>
{/literal}

<div align="center">
    <div id="info" style="display:none;"></div>
</div>

<table border="0" cellpadding="0" cellspacing="0" align="center" width="500" style="margin-top:10px;">
  
  {if $is_steam eq "Y"}
  <tr>
    <td><b>Update Server:</b></td>
    <td><input type="button" id="steam_update_btn" value="{$lang.update}" onClick="javascript:confirmSteamUpdate('{$lang.managesrv_confirm_update}',{$srvid});" /></td>
  </tr>
  
  {if $update_status eq "running"}
  <tr>
    <td><b>Update Status:</b></td>
    <td>{$steam_update}</td>
  </tr>
  {/if}
  
  {/if}
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  
  <tr>
    <td colspan="2"><b>Current Server Status</b></td>
  </tr>  
  
  <tr>
    <td>Status:</td>
    <td>
      {if $cur_status eq "online"}
          <span style="font-weight:bold;color:green;">Online</span>
      {elseif $cur_status eq "offline"}
          <span style="font-weight:bold;color:red;">Offline</span>
      {else}
          <span style="font-weight:bold;color:orange;">Unknown</span>
      {/if}
    </td>
  </tr>
  {if $cur_status eq "online"}
  <tr>
    <td>Hostname:</td>
    <td>{$cur_hostname}</td>
  </tr>
  <tr>
    <td>Players:</td>
    <td>{if !$cur_numplayers}0{else}{$cur_numplayers}{/if} / {$cur_maxplayers}</td>
  </tr>
  
  {if $type eq "game"}
  <tr>
    <td>Map:</td>
    <td>{$cur_map}</td>
  </tr>
  {/if}
  
  <tr>
    <td>OS:</td>
    <td>{$cur_os}</td>
  </tr>
  <tr>
    <td>Game Protocol:</td>
    <td>{$cur_protocol}</td>
  </tr>
  <tr>
    <td>Has Password:</td>
    <td>
      {if $cur_has_pass}
          Yes
      {else}
          No
      {/if}
    </td>
  </tr>
  {/if}
</table>

{* CURRENT PLAYERS *}
{if $cur_players}
<table border="0" cellpadding="0" cellspacing="0" align="center" width="500" style="margin-top:10px;">
<tr>
  <td width="220"><b>Player Name</b></td>
  <td><b>Frags</b></td>
</tr>
    {section name=pl loop=$cur_players}
      <tr>
        <td>{$cur_players[pl].gq_name}</td>
        <td>{$cur_players[pl].gq_score}</td>
      </tr>
    {/section}
{/if}




{if $srv_name eq "cs_16" || $srv_name eq "cs_cz" || $srv_name eq "cs_s"}
    {if $cur_status eq "online"}
    <table border="0" cellpadding="0" cellspacing="0" align="center" width="500" style="margin-top:20px;">
    <tr>
      <td colspan="2"><b>Rcon Commands</b></td>
    </tr>
    <tr>
      <td>Rcon Command:</td>
      <td><input type="text" id="rcon_cmd" class="textbox_normal" /></td>
    </tr>

    <tr>
      <td colspan="2" align="center"><input type="button" value="Run" onClick="javascript:rconCMD({$srvid});" /></td>
    </tr>
    </table>

    <div align="center">
        <div id="rcon_result" style="display:none;"></div>
        <div id="rcon_players" style="display:none;"></div>
    </div>
    {/if}
{/if}



{/if}
