<?php
//
// Send Rcon commands to a server
//
$url_id = mysql_real_escape_string($_GET['id']);

if(empty($url_id) || !is_numeric($url_id))
{
    die('Rcon: Invalid server id: '.$url_id);
}

########################################################################

// Get IP/Port/Rcon Pass of this server
$query_info   = "SELECT 
                    servers.port,
                    servers.rcon_password,
                    network.ip 
                 FROM servers 
                 LEFT JOIN network ON 
                    servers.networkid = network.id 
                 WHERE 
                    servers.id = '$url_id'";

$result_info  = @mysql_query($query_info);
$row_info     = mysql_fetch_row($result_info);
$server_port  = $row_info[0];
$server_rcon  = $row_info[1];
$server_ip    = $row_info[2];

if(empty($server_port) || empty($server_ip))
{
    die('No ip/port found for that server');
}
if(empty($server_rcon))
{
    die('No rcon password set.  Set it in the Edit Server tab.');
}

########################################################################

//
// Rcon `status` command
//
require(GPX_DOCROOT.'/include/class/rcon.class.php');
$r = new rcon($server_ip,$server_port,$server_rcon);
$r->Auth();
$rcon_status  = $r->rconCommand('status');
$status_arr   = explode("\n", $rcon_status);
#$players      = array();
#$players_cnt  = 0;

echo '<table border="0" cellpadding="4" cellspacing="0" width="400" align="center">
      <tr>
        <td>&nbsp;</td>
        <td>Name</td>
        <td>Steam ID</td>
        <td>IP Address</td>
      </tr>';

foreach($status_arr as $line)
{
    // Example: 
    // # 44 "devianT" STEAM_0:0:7524691 06:08 60 0 active 98.255.38.185:27005
    if(preg_match("/^\#\ \d+\ \"/", $line))
    {
        // Separate by spaces
        $player = explode(' ', $line);
        
        /*
        // Add to players array for Smarty
        $players[$players_cnt]['id']      = $player[1];
        $players[$players_cnt]['name']    = str_replace('"', '', $player[2]);
        $players[$players_cnt]['steamid'] = $player[3];
        $players[$players_cnt]['ip']      = preg_replace("/\:\d+$/", '', $player[8]);
        */
        
        $ply_id       = $player[1];
        $ply_name     = str_replace('"', '', $player[2]);
        $ply_steamid  = $player[3];
        $ply_ip       = preg_replace("/\:\d+$/", '', $player[8]);
        
        #$players_cnt++;
        
        // Output
        echo '<tr>
                <td><input type="checkbox" name="playerid[]" value="' . $ply_id . '" /></td>
                <td><b>' . $ply_name . '</b></td>
                <td>' . $ply_steamid . '</td>
                <td>' . $ply_ip . '</td>
              </tr>';
    }
}

echo '<tr>
        <td colspan="4">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="4" align="center"><input type="button" value="Kick Selected" style="width:120px;" onClick="javascript:rconKickCS(' . $url_id . ');" /></td>
      </tr>
      </table>';

?>