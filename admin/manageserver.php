<?php
/*
 * GamePanelX Pro
 * Complete Game and Voice server management tool
 * 
 * Copyright(C) 2009-2010 GamePanelX Pro.  All Rights Reserved. 
 * 
 * Email: support@gamepanelx.com
 * Website: http://www.gamepanelx.com
 * 
 * This software is furnished under a license and may be used and copied
 * only  in  accordance  with  the  terms  of such  license and with the
 * inclusion of the above copyright notice.  This software  or any other
 * copies thereof may not be provided or otherwise made available to any
 * other person.  No title to and  ownership of the  software is  hereby
 * transferred.                                                         
 *                                                                      
 * You may not reverse  engineer, decompile, defeat  license  encryption
 * mechanisms, or  disassemble this software product or software product
 * license.  GamePanelX Pro may terminate this license if you don't
 * comply with any of the terms and conditions set forth in our end user
 * license agreement (EULA).  In such event,  licensee  agrees to return
 * licensor  or destroy  all copies of software  upon termination of the
 * license.                                                             
 *                                                                      
 * Please see the EULA file for the full End User License Agreement.    
*/

//
// Smarty
//
require '../libs/Smarty.class.php';
$smarty = new Smarty;
$smarty->compile_dir  = '../admin/templates_c/';

// Required Files
require('../include/auth.php');
require('../include/config.php');

// Page Title
$smarty->assign('pagetitle', 'Manage Server');


########################################################################

// Check license variable
if($gpxseckey_T2V1lmkWLli04Z7q3FT != 'F9hJt6up1h80qk9REDD2xyA89TfI185gwtLXJsSMhc61fWv5T33548rLqtW5MWGjkgFl8ISzsoF8491IT2V1lmkWLli04Z7q3FTls169B8PmTx0lRZet777Pr40p7R01FkQFymp1Z629GG5dEW8nI3')
{
    die('Invalid license');
}

########################################################################

// URL variables
$url_action = $_GET['action'];
$url_id     = $_GET['id'];

// Correct ID
if(empty($url_id) || !is_numeric($url_id))
{
    die('<center><b>Error:</b> Invalid id given!</center>');
}

// List of allowed actions
$allowed_actions = array('delete');

// Correct action
if(!empty($url_action) && !in_array($url_action, $allowed_actions))
{
    die('<center><b>Error:</b> <i>manageserver.php:</i> Invalid URL Parameters!</center>');
}


########################################################################

// Connect to the database
$db = @mysql_connect($config['sql_host'],$config['sql_user'],$config['sql_pass']) or die('<center><b>Error:</b> <i>manageserver.php</i>: Failed to connect to the database!</center>');
@mysql_select_db($config['sql_db']) or die('<center><b>Error:</b> <i>manageserver.php</i>: Failed to select the database!</center>');

// URL Server ID
$url_id = mysql_real_escape_string($url_id);

########################################################################


//
// URL Actions
//
if(!empty($url_action) && !empty($url_id))
{
    // Delete user
    if($url_action == 'delete')
    {
        // Get server type
        $result_type = @mysql_query("SELECT type FROM servers WHERE id = '$url_id'") or die('<center><b>Error:</b> <i>manageserver.php:</i> Failed to get server type!</center>');
        
        while($row_type = mysql_fetch_array($result_type))
        {
            $this_server_type = $row_type['type'];
        }
        
        ################################################################
        
        
        // Delete account from 'gameservers' table
        require('../include/functions/servers.php');
        
        if(!gpx_delete_server($url_id))
        {
            die('<center><b>Error:</b> <i>manageserver.php:</i> Failed to delete the server!</center>');
        }
        else
        {
            // Send to success page
            header("Location: servers.php?type=$this_server_type&info=deleted");
            exit;
        }
    }
}


########################################################################


//
// Get Server Info
//
$server_query = "SELECT 
                    servers.id,
                    DATE_FORMAT(servers.date_created, '%c/%e/%Y %H:%i') AS date_created,
                    servers.type,
                    servers.status,
                    servers.server,
                    servers.port,
                    servers.description,
                    servers.creation_status,
                    servers.update_status,
                    servers.subdomain,
                    servers.notes,
                    clients.username,
                    cfg.short_name,
                    cfg.long_name,
                    cfg.is_steam,
                    domains.domain,
                    network.ip 
                 FROM servers 
                 LEFT JOIN clients ON 
                    servers.userid = clients.id 
                 LEFT JOIN cfg ON 
                    servers.server = cfg.short_name 
                 LEFT JOIN domains ON 
                    servers.domainid = domains.id 
                 LEFT JOIN network ON 
                    servers.networkid = network.id 
                 WHERE 
                    servers.id = '$url_id'";

$result = @mysql_query($server_query) or die('<center><b>Error:</b> <i>manageserver.php:</i> Failed to get game server information!</center>');

// Get the values
while ($line = mysql_fetch_assoc($result))
{
    $value[] = $line;
}

// Current Server Creation status
$creation_status = $value[0]['creation_status'];
$creation_status = $value[0]['update_status'];

// Smarty mysql loop
$smarty->assign('server_details', $value);

// Server Type
$this_type = $value[0][type];
$smarty->assign('type', $this_type);


########################################################################

/*
 * DEPRECATED in favor of Ajax
 * 
//
// Check if the server is busy doing anything
//
require(GPX_DOCROOT . '/include/functions/status.php');


// Creation status
$creation_status  = gpx_getstatus_create_server($url_id);

// Server update status
$update_status    = gpx_getstatus_update_server($url_id);


// Assign statuses to Smarty
$smarty->assign('creation_status', $creation_status);
$smarty->assign('update_status', $update_status);
*/

########################################################################

/*
//
// Get this server's status (online/offline), map info, etc
// Using the GameQ library
//
require('../include/query.php');

// Use server details from database
$server_ip    = $value[0]['ip'];
$server_port  = $value[0]['port'];

// Use the `query` table to swap game names from GameQ to GPX
$query_name   = $value[0]['query_name'];

// Check for empty server name swap
if(empty($query_name))
{
    die('<center><b>Error:</b> <i>manageserver.php:</i> No query engine name found for this server!</center>');
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

/*

DEBUG::

echo '<pre>';
var_dump($results);
echo '</pre>';



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


// Assign these to smarty
$smarty->assign('current_ip', $current_ip);
$smarty->assign('current_port', $current_port);
$smarty->assign('current_online', $current_status);
$smarty->assign('current_hostname', $current_hostname);
$smarty->assign('current_map', $current_map);
$smarty->assign('current_max_slots', $current_max_slots);
$smarty->assign('current_player_num', $current_player_num);
$smarty->assign('current_mod', $current_mod);

*/

########################################################################


// Current Template
$template = $config['Template'];

//
// Get all icons for this page
//
$result_icons = @mysql_query("SELECT href,image,image_text,popup_text FROM pages WHERE page='manageserver.php' AND template='$template' ORDER BY sort_order") or die('<center><b>Error:</b> <i>manageserver.php:</i> Failed to get icon order!</center>');

while ($line_icons = mysql_fetch_assoc($result_icons))
{
    $value_icons[] = $line_icons;
}

// Smarty array
$smarty->assign('icons', $value_icons);


########################################################################


// Infobox from the URL
$url_info = $_GET['info'];

// Allowed info
$allowed_info = array('created');

if(!empty($url_info))
{
    if(in_array($url_info, $allowed_info))
    {
        // Server created
        if($url_info == 'created')
        {
            $info_msg = 'Server successfully created!';
            $smarty->assign('infobox', $info_msg);
        }
    }
}




########################################################################

// Set user's language
require('../include/functions/language.php');
$lang = gpx_language_get();
$smarty->assign('lang', $lang);

########################################################################

// First Page
#if(!isset($_POST['status']))
#{
    // Display HTML Page
    $smarty->display($config['Template'] . '/manageserver.tpl');
#}

/*
// Action page
elseif(isset($_POST['status']))
{
    $post_action = $_POST['action'];
    
    if($post_action == 'restart')
    {
        header("Location: action.php?id=$url_id&a=restart");
        exit;
    }
    elseif($post_action == 'stop')
    {
        header("Location: action.php?id=$url_id&a=stop");
        exit;
    }
}
*/
