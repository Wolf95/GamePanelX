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

// Check logged-in
if(!isset($_SESSION['gpx_username']))
{
    die('<center><b>Error:</b> You must be logged-in as an administrator to view this page.</center>');
}
$this_userid  = $_SESSION['gpx_userid'];

// Check if admin
if($_SESSION['gpx_isadmin'] == 1) $is_admin = true;
else $is_admin  = false;

########################################################################

//
// Startup CMD Line: Save simple editor
//
$url_id       = mysql_real_escape_string($_GET['id']);
//$url_ip       = mysql_real_escape_string($_GET['ip']);
//$url_port     = mysql_real_escape_string($_GET['port']);
$url_map      = mysql_real_escape_string($_GET['map']);
$url_maxslots = mysql_real_escape_string($_GET['maxslots']);

// Make sure this user has access to this server
if(!$is_admin)
{
    $this_userid  = $_SESSION['gpx_userid'];
    $result_ac  = @mysql_query("SELECT id FROM servers WHERE id = '$url_id' AND userid = '$this_userid'");
    $row_ac     = mysql_fetch_row($result_ac);
    
    if(!$row_ac[0]) die('You do not have access to this server.  Please contact your host.');
}

########################################################################

/*
 * Simple ID's:
 * 
 * 1 - IP Address
 * 2 - Port
 * 3 - Max Slots
 * 4 - Map
 * 
*/

//
// Get Item ID's of each of these
//
$query_ids  = "SELECT 
                  cfg_items.id,
                  cfg_items.simpleid 
               FROM cfg_items 
               LEFT JOIN cfg ON 
                  cfg_items.srvid = cfg.id 
               LEFT JOIN servers ON 
                  cfg.short_name = servers.server 
               WHERE 
                  cfg_items.simpleid IN (3,4) 
                  AND servers.id = '$url_id' 
               ORDER BY 
                  cfg_items.simpleid ASC";

$result_ids = @mysql_query($query_ids);

while($row_ids  = mysql_fetch_array($result_ids))
{
    $item_id    = $row_ids['id'];
    $item_smpid = $row_ids['simpleid'];
    
    /*
    // IP Address
    if($item_smpid == 1)
    {
        $smpid_ip = $item_id;
    }
    // Port
    elseif($item_smpid == 2)
    {
        $smpid_port = $item_id;
    }
    */
    // Max Slots
    if($item_smpid == 3)
    {
        $smpid_maxslots = $item_id;
    }
    // Map
    elseif($item_smpid == 4)
    {
        $smpid_map  = $item_id;
    }
}

########################################################################

//
// Run individual updates
//

/*
// IP Address
if(!empty($url_ip))
{
    @mysql_query("UPDATE servers_cfg SET item_value = '$url_ip' WHERE itemid = '$smpid_ip' AND srvid = '$url_id'") or die('Failed to update the IP Address');
}
// Port
if(!empty($url_port))
{
    @mysql_query("UPDATE servers_cfg SET item_value = '$url_port' WHERE itemid = '$smpid_port' AND srvid = '$url_id'") or die('Failed to update the Port');
}
*/

// NEW! Simple config items are all stored in `servers`, NOT `servers_cfg`.
$update_query = "UPDATE servers SET ";

// Max Slots
if(!empty($url_maxslots) && is_numeric($url_maxslots))
{
    $update_query .= "max_slots = '$url_maxslots',";
    //@mysql_query("UPDATE servers_cfg SET item_value = '$url_maxslots' WHERE itemid = '$smpid_maxslots' AND srvid = '$url_id'") or die('Failed to update the Max Slots');
}
// Map
if(!empty($url_map))
{
    $update_query .= "map = '$url_map',";
    //@mysql_query("UPDATE servers_cfg SET item_value = '$url_map' WHERE itemid = '$smpid_map' AND srvid = '$url_id'") or die('Failed to update the Map');
}

// Remove trailing comma
$update_query = substr($update_query, 0, -1);


// Finish query / Run it
$update_query .= " WHERE id = '$url_id'";
@mysql_query($update_query);


########################################################################

//
// Update server CMD-Line
//

// Get gameid for this server
$query_id   = "SELECT 
                  cfg.id,
                  servers.executable 
               FROM servers 
               LEFT JOIN cfg ON 
                  servers.server = cfg.short_name 
               WHERE 
                  servers.id = '$url_id'";

$result_id  = @mysql_query($query_id);
$row_id     = mysql_fetch_row($result_id);
$cfg_id     = $row_id[0];
$srv_exe    = $row_id[1];

########

// Get IP Address from this NetworkID
$result_ip  = @mysql_query("SELECT 
                              network.ip,
                              servers.port 
                            FROM network 
                            LEFT JOIN servers ON 
                              network.id = servers.networkid 
                            WHERE 
                              servers.id = '$url_id'") or die('Failed to query for IP Address');

$row_ip       = mysql_fetch_row($result_ip);
$network_ip   = $row_ip[0];
$server_port  = $row_ip[1];

########

$cmd_line = './' . $srv_exe;

// Get this all server's current config items
$server_query = "SELECT 
                    servers_cfg.item_value,
                    cfg_items.simpleid,
                    cfg_items.name 
                 FROM servers_cfg 
                 LEFT JOIN cfg_items ON 
                    servers_cfg.itemid = cfg_items.id 
                 WHERE 
                    servers_cfg.srvid = '$url_id' 
                    AND servers_cfg.deleted = '0' 
                 ORDER BY 
                    servers_cfg.item_order ASC";

$result_srv = @mysql_query($server_query) or die('Failed to get current adv config items');

while($row_srv  = mysql_fetch_array($result_srv))
{
    $cfg_name     = $row_srv['name'];
    $cfg_simpleid = $row_srv['simpleid'];
    $cfg_val      = $row_srv['item_value'];
    
    // IP Address
    if($cfg_simpleid == 1)
    {
        $cfg_val  = $network_ip;
    }
    // Port
    elseif($cfg_simpleid == 2)
    {
        $cfg_val  = $server_port;
    }
    // Max Slots
    elseif($cfg_simpleid == 3)
    {
        $cfg_val  = $url_maxslots;
    }
    // Map
    elseif($cfg_simpleid == 4)
    {
        $cfg_val  = $url_map;
    }
    
    
    // Add to command-line
    if(!empty($cfg_name) && !empty($cfg_val))
    {
        $cmd_line .= ' ' . $cfg_name . ' ' . $cfg_val;
    }
}

// Save new cmd line
@mysql_query("UPDATE servers SET cmd_line = '$cmd_line' WHERE id = '$url_id'");

########################################################################

// Log this action (9: Update server details)
require(GPX_DOCROOT.'/include/class/log.php');
$Log = new Log;
$Log->addlog('10',$this_userid,$url_id);

########################################################################

// Output
echo 'success';

?>
