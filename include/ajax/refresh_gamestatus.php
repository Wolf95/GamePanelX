<?php
// Gameserver Status refresh

// Get ID
$url_serverid = $_GET['id'];

// Check empty
if(empty($url_serverid))
{
    die('<b>Ajax:</b> No ID given');
}

// Safe-ify
$url_serverid = mysql_real_escape_string($url_serverid);

####################################################################

//
// Check if user owns this server
//
if(!isset($_SESSION['gpx_isadmin']))
{
    require_once('functions/servers.php');
    $this_clientid  = $_SESSION['gpx_userid'];
    $user_owns      = gpx_check_user_owns($url_serverid,$this_clientid);

    // Die if user doesn't own it
    if(!$user_owns)
    {
        die('<center><b>Error:</b> You do not have access to this server!</center>');
    }
}

####################################################################

//
// Get IP,Port and Query Name for this server ID
//
$query_qinfo  = "SELECT 
                    servers.ip,
                    servers.port,
                    cfg.query_name 
                  FROM servers 
                  LEFT JOIN cfg ON 
                    servers.server = cfg.short_name 
                  WHERE servers.id = '$url_serverid'";
$result_qinfo = @mysql_query($query_qinfo);

while($row_qinfo = mysql_fetch_array($result_qinfo))
{
    $q_ip   = $row_qinfo['ip'];
    $q_port = $row_qinfo['port'];
    $q_name = $row_qinfo['query_name'];
}

####################################################################

// Require language support
require_once(GPX_DOCROOT . '/languages/' . $config['Language'] . '.php');

//
// Get this server's status (online/offline), map info, etc
// Using the GameQ library
//
require(GPX_DOCROOT . '/include/query.php');

// Use server details from database
$server_ip    = $q_ip;
$server_port  = $q_port;

// Use the `query` table to swap game names from GameQ to GPX
$query_name   = $q_name;

// Check for empty server name swap
if(empty($query_name))
{
    //
    // Can't use GameQ, so try a generic query
    //
    require(GPX_DOCROOT . '/include/functions/query.php');
    
    if(gpx_query_generic($server_ip,$server_port))
    {
        die('<span style="font-family:Arial;font-size:10pt;font-weight:bold;color:green;vertical-align:top">' . $lang[status_online] . '.</span>');
    }
    else
    {
        die('<span style="font-family:Arial;font-size:10pt;font-weight:bold;color:red;vertical-align:top">' . $lang[status_offline] . '.</span></b>');
    }
}



// Get the default server query timeout from the `configuration` table
$result_timout = @mysql_query("SELECT value FROM configuration WHERE setting='ServerQueryTimeout'") or die('<center><b>Error:</b> <i>manageserver.php:</i> Failed to query the configuration table!</center>');

while($row_timeout = mysql_fetch_array($result_timout))
{
    $query_timeout = $row_timeout['value'];
}

//
// Setup server query
//

// Use IP:Port to describe this server for the array
$desc_server = $server_ip . ':' . $server_port;

// Create server array
$servers = array($desc_server => array($query_name, $server_ip, $server_port));

// Run GameQ specifics
$gq = new GameQ();
$gq->addServers($servers);

// You can optionally specify some settings
$gq->setOption('timeout', $query_timeout);


// You can optionally specify some output filters,
// these will be applied to the results obtained.
$gq->setFilter('normalise');
$gq->setFilter('sortplayers', 'gq_ping');

// Send requests, and parse the data
$results = $gq->requestData();

// Returned variables
$current_online     = $results[$desc_server]['gq_online'];

// Print the status
if($current_online)
{
    echo '<span style="font-family:Arial;font-size:10pt;font-weight:bold;color:green;vertical-align:top">' . $lang[status_online] . '</span>';
}
else
{
    echo '<span style="font-family:Arial;font-size:10pt;font-weight:bold;color:red;vertical-align:top">' . $lang[status_offline] . '</span></b>';
}

// Add refresh
//echo '&nbsp;<a href="javascript:void(0)" onClick="serverStatus()">(' . $lang[refresh] . ')</a>';

?>
