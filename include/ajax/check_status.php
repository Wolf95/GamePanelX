<?php

// Server ID
$url_id = mysql_real_escape_string($_GET['id']);

####################################################################

//
// Check if user owns this server
//
if(!isset($_SESSION['gpx_isadmin']))
{
    require_once('functions/servers.php');
    $this_clientid  = $_SESSION['gpx_userid'];
    $user_owns      = gpx_check_user_owns($url_id,$this_clientid);

    // Die if user doesn't own it
    if(!$user_owns)
    {
        die('<center><b>Error:</b> You do not have access to this server!</center>');
    }
}

####################################################################

//
// Check if the server is busy doing anything
//
require(GPX_DOCROOT . '/include/functions/status.php');

// Require language support
require_once(GPX_DOCROOT . '/languages/' . $config['Language'] . '.php');

// Creation status
$creation_status  = gpx_getstatus_create_server($url_id);

// Server update status
$update_status    = gpx_getstatus_update_server($url_id);

####################################################################

// Creating Server
if($creation_status == 'running')
{
    // Server is being created
    echo '<b><font color="blue">' . $lang[managesrv_being_created] . ' ...</font></b>';
}

// Updating Server
elseif($update_status == 'running')
{
    // Server is being updated
    echo '<b><font color="blue">' . $lang[managesrv_being_updated] . ' ...</font></b>';
}

// Both are complete
elseif($creation_status == 'complete' && $update_status == 'complete')
{
    //
    // Get this server's status (online/offline), map info, etc
    // Using the GameQ library
    //
    require(GPX_DOCROOT . '/include/query.php');

    ################################################################
    
    // Get server details
    $query_info = "SELECT 
                      servers.ip,
                      servers.port,
                      cfg.query_name 
                   FROM servers 
                   LEFT JOIN cfg ON 
                      servers.server = cfg.short_name 
                   WHERE servers.id = '$url_id'";
    
    $result_info = @mysql_query($query_info) or die(mysql_error());
    
    while($row_info = mysql_fetch_array($result_info))
    {
        $server_ip    = $row_info['ip'];
        $server_port  = $row_info['port'];
        $query_name   = $row_info['query_name'];
    }

    ################################################################
    
    /*
    // Use server details from database
    $server_ip    = $value[0]['ip'];
    $server_port  = $value[0]['port'];

    // Use the `query` table to swap game names from GameQ to GPX
    $query_name   = $value[0]['query_name'];
    */

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
    $result_timout = @mysql_query("SELECT value FROM configuration WHERE setting='ServerQueryTimeout'") or die('<center><b>Error:</b> <i>ajax:</i> Failed to query the configuration table!</center>');

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
    $current_ip         = $results[$desc_server]['gq_address'];
    $current_port       = $results[$desc_server]['gq_port'];
    $current_online     = $results[$desc_server]['gq_online'];
    $current_hostname   = $results[$desc_server]['gq_hostname'];
    $current_map        = $results[$desc_server]['gq_mapname'];
    $current_max_slots  = $results[$desc_server]['gq_maxplayers'];
    $current_player_num = $results[$desc_server]['gq_numplayers'];
    $current_mod        = $results[$desc_server]['gq_mod'];

    // If online is true, set status to online
    if($current_online)
    {
        $current_status = 'online';
    }
    else
    {
        $current_status = 'offline';
    }
    
    
    
    //
    // Output current server online/offline status
    //
    
    // Status Online
    if($current_status == 'online')
    {
        //echo '<b><font color="green">' . $lang[status_online] . '</font></b>';
        echo '<span style="font-family:Arial;font-size:10pt;font-weight:bold;color:green;vertical-align:top">' . $lang[status_online] . '</span>';
    }
    // Status Offline
    elseif($current_status == 'offline')
    {
        echo '<span style="font-family:Arial;font-size:10pt;font-weight:bold;color:red;vertical-align:top">' . $lang[status_offline] . '</span></b>';
    }
    // Status unknown
    else
    {
        echo '<span style="font-family:Arial;font-size:10pt;font-weight:bold;color:orange;vertical-align:top">' . $lang[status_unknown] . '</span></b>';
    }


}

// Anything else
else
{
    // Unknown status
    echo '<b><font color="orange">' . $lang[status_unknown] . '</font></b>';
}


/*
 * OLD smarty stuff
 * 
{if $creation_status eq "running"}
    <b><font color="blue">{$lang.managesrv_being_created} ...</font></b>
{elseif $update_status eq "running"}
    <b><font color="blue">{$lang.managesrv_being_updated} ...</font></b>
    
{elseif $creation_status eq "complete" and $update_status eq "complete"}

    {if $current_online eq "online"}
        <b><font color="green">{$lang.status_online}</font></b>
    {elseif $current_online eq "offline"}
        <b><font color="red">{$lang.status_offline}</font></b>
    {else}
        <b><font color="orange">{$lang.status_unknown}</font></b>
    {/if}

{else}
    <b><font color="orange">{$lang.status_unknown}</font></b>
{/if}</div>

{if $creation_status eq "complete" and $update_status eq "complete"}
<div align="left">
<a href="javascript:void(0)" onClick="serverStatus(document.srvstatus.serverid.value);">({$lang.refresh})</a>
<input type="hidden" name="serverid" value="{$server_details[db].id}" />
</div>
{/if}
*/


exit;

?>
