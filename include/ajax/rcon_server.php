<?php
//
// Send Rcon commands to a server
//
$url_id         = mysql_real_escape_string($_GET['id']);
$url_rcon_cmd   = $_GET['cmd'];

if(empty($url_id) || !is_numeric($url_id))
{
    die('Rcon: Invalid server id: '.$url_id);
}
if(empty($url_rcon_cmd))
{
    die('No rcon command specified');
}

// No quitters
if($url_rcon_cmd == 'quit')
{
    die('Quit is not allowed; use the server stop function instead.');
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
// Run Rcon commands
//
require(GPX_DOCROOT.'/include/class/rcon.class.php');
$r = new rcon($server_ip,$server_port,$server_rcon);
$r->Auth();
$rcon_status  = $r->rconCommand($url_rcon_cmd);
#$status_arr   = explode("\n", $rcon_status);

// Convert newlines
$rcon_status  = preg_replace("/\n+/", '<br />', $rcon_status);

// Output
if(empty($rcon_status))
{
    echo 'Invalid rcon password or other error';
}
else echo $rcon_status;

/*
$count_line   = 0;

foreach($status_arr as $line)
{
    // Example: # userid name uniqueid connected ping loss state adr
    
    // Players in here
    if($count_line >= 8)
    {
        $arr_ply    = explode(' ', $ply_line);
        $plr_userid = $arr_ply[1];
        $plr_name   = $arr_ply[2];
        $plr_ip     = $arr_ply[4];
        $plr_ping   = $arr_ply[5];
        
        echo "Player userid: $plr_userid, name: $plr_name, IP: $plr_ip, Ping: $plr_ping<br>";
    }
    
    $count_line++;
}
*/

/*
echo '<pre>';
echo $rcon_result;
echo '</pre>';
*/

?>