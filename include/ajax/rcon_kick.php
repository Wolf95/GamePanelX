<?php
//
// Use Rcon to kick player(s) from a server
//
$url_id = mysql_real_escape_string($_GET['id']);

if(empty($url_id) || !is_numeric($url_id))
{
    die('Rcon: Invalid server id: '.$url_id);
}


var_dump($_POST);

/*
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


// Run kick
$r->rconCommand('kickid 22 ; ......');
*/

?>