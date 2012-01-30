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
    die('<center><b>Error:</b> You must be logged-in to view this page.</center>');
}

// Check if admin
if($_SESSION['gpx_isadmin'] == 1) $is_admin = true;
else $is_admin  = false;

if(empty($_GET['id']))
{
    die('<center><b>Error:</b> No server ID given!</center>');
}

$url_id = mysql_real_escape_string($_GET['id']);

// Make sure this user has access to this server
if(!$is_admin)
{
    $this_userid  = $_SESSION['gpx_userid'];
    $result_ac  = @mysql_query("SELECT id FROM servers WHERE id = '$url_id' AND userid = '$this_userid'");
    $row_ac     = mysql_fetch_row($result_ac);
    
    if(!$row_ac[0]) die('You do not have access to this server.  Please contact your host.');
}

########################################################################

//
// Startup CMD Line: Save simple editor
//

// Get basics first
$url_networkid  = mysql_real_escape_string($_GET['adv_netid']);
$url_port       = mysql_real_escape_string($_GET['adv_port']);
$url_map        = mysql_real_escape_string($_GET['adv_map']);
$url_maxslots   = mysql_real_escape_string($_GET['adv_maxslots']);

// Only update "client-editable" if admin
if($is_admin)
{
    // Figure out the row for the map (simpleid 4)
    $result_mp  = @mysql_query("SELECT 
                                  cfg_items.id 
                                FROM cfg_items 
                                LEFT JOIN cfg ON 
                                  cfg_items.srvid = cfg.id 
                                LEFT JOIN servers ON 
                                  servers.server = cfg.short_name 
                                WHERE 
                                  cfg_items.simpleid = '4' 
                                  AND servers.id = '$url_id'");

    $row_mp = mysql_fetch_row($result_mp);
    $map_itemid   = $row_mp[0];
    $cfg_cled_map = mysql_real_escape_string($_GET['cfgcled_map']);

    // Update client-editable map
    @mysql_query("UPDATE servers_cfg SET client_edit = '$cfg_cled_map' WHERE itemid = '$map_itemid' AND srvid = '$url_id'");
}

########################################################################

//
// Simple Config Items (ip,port,maxslots,map)
//

// Setup simple update
$simple_upd = "UPDATE servers SET ";

// Update simple id's
if(!empty($url_networkid))
{
    $simple_upd .= "networkid = '$url_networkid',";
}
if(!empty($url_port))
{
    $simple_upd .= "port = '$url_port',";
}
if(!empty($url_maxslots))
{
    $simple_upd .= "max_slots = '$url_maxslots',";
}
if(!empty($url_map))
{
    $simple_upd .= "map = '$url_map',";
}

// Remove trailing comma / Finish query
$simple_upd = substr($simple_upd, 0, -1);
$simple_upd .= "  WHERE id = '$url_id'";

// Run update query
if($is_admin) @mysql_query($simple_upd) or die('Failed to update main values');
else @mysql_query("UPDATE servers SET map = '$url_map' WHERE id = '$url_id'") or die('Failed to update main values');


########################################################################

// Get some current info for this server
$query_id   = "SELECT 
                  cfg.id,
                  servers.executable,
                  servers.port,
                  servers.max_slots,
                  servers.map,
                  network.ip 
               FROM servers 
               LEFT JOIN cfg ON 
                  servers.server = cfg.short_name 
               LEFT JOIN network ON 
                  servers.networkid = network.id 
               WHERE 
                  servers.id = '$url_id'";

$result_id      = @mysql_query($query_id);
$row_id         = mysql_fetch_row($result_id);
$cfg_id         = $row_id[0];
$srv_exe        = $row_id[1];
$srv_port       = $row_id[2];
$srv_max_slots  = $row_id[3];
$srv_map        = $row_id[4];
$srv_ip         = $row_id[5];

########################################################################

//
// Advanced Config Items (cfg_x id's)
//

// Array of client-editable items
#$cl_ed_arr  = array();

// Run through cfg_x items
foreach($_GET as $cfg => $cfg_val)
{
    /*
    // Client-Editable
    if(preg_match("/^cfgcled_\d+$/", $cfg))
    {
        $cfg  = str_replace('cfgcled_', '', $cfg);
        
        // Add this item to update array
        if($_GET['cfgcled_'.$cfg] == '1')
        {
            $cl_ed_arr[$cfg]  = '1';
        }
    }
    */
    
    // Current cfg_x items only
    if(preg_match("/^cfg_\d+$/", $cfg))
    {
        // Remove "cfg_", to get a valid ItemID
        $cfg  = str_replace('cfg_', '', $cfg);
        
        // Update this ID
        if(is_numeric($cfg))
        {
            // Client-Editable
            /*
            if(in_array($cfg, $cl_ed_arr))
            {
                $cfg_client_ed  = '1';
            }
            else $cfg_client_ed = '0';
            */
            
            if($_GET['cfgcled_'.$cfg])
            {
                $cfg_client_ed  = '1';
            }
            else $cfg_client_ed = '0';
            
            if($is_admin) $sql_add = ',client_edit = \'' . $cfg_client_ed . '\' ';
            else $sql_add = '';
            
            @mysql_query('UPDATE servers_cfg SET item_value = \'' . $cfg_val . '\'' . $sql_add . 'WHERE itemid = \'' . $cfg . '\'') or die('Failed to update config id '.$cfg);
        }
    }
    
    
    // Add new config items
    //
    // - Key id: addcfg_x
    // - Value id: addcfgval_x
    elseif(preg_match("/^addcfg_\d+$/", $cfg))
    {
        // Remove "addcfg_", to get a valid ItemID
        $cfg  = str_replace('addcfg_', '', $cfg);
        
        if(is_numeric($cfg))
        {
            $this_key = $_GET['addcfg_'.$cfg];
            $this_val = $_GET['addcfgval_'.$cfg];
            
            // && !empty($this_val)
            if(!empty($this_key))
            {
                $this_key = mysql_real_escape_string($this_key);
                $this_val = mysql_real_escape_string($this_val);
                
                // Insert global row for this new item (only admins)
                if($is_admin)
                {
                    @mysql_query("INSERT INTO cfg_items (srvid,usr_def,cmd_line,name,default_value) VALUES('$cfg_id','1','1','$this_key','$this_val')");
                    $new_item_id  = mysql_insert_id();
                    
                    // Insert unique server row
                    @mysql_query("INSERT INTO servers_cfg (srvid,itemid,item_value) VALUES('$url_id','$new_item_id','$this_val')");
                }
            }
        }
    }
}

########################################################################

// Get IP Address from this NetworkID
$result_ip  = @mysql_query("SELECT ip FROM network WHERE id = '$url_networkid'") or die('Failed to query for IP');
$row_ip     = mysql_fetch_row($result_ip);
$network_ip = $row_ip[0];

//
// Update CMD-Line
//
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

/*
$srv_port       = $row_id[2];
$srv_max_slots  = $row_id[3];
$srv_map        = $row_id[4];
$srv_ip         = $row_id[5];
*/

while($row_srv  = mysql_fetch_array($result_srv))
{
    $cfg_name     = $row_srv['name'];
    $cfg_simpleid = $row_srv['simpleid'];
    $cfg_val      = $row_srv['item_value'];
    
    // IP Address
    if($cfg_simpleid == 1)
    {
        if(!empty($network_ip)) $cfg_val  = $network_ip;
        else $cfg_val  = $srv_ip;
    }
    // Port
    elseif($cfg_simpleid == 2)
    {
        if(!empty($url_port)) $cfg_val  = $url_port;
        else $cfg_val = $srv_port;
    }
    // Max Slots
    elseif($cfg_simpleid == 3)
    {
        if(!empty($url_maxslots)) $cfg_val  = $url_maxslots;
        else $cfg_val = $srv_max_slots;
    }
    // Map
    elseif($cfg_simpleid == 4)
    {
        if(!empty($url_map)) $cfg_val  = $url_map;
        else $cfg_val = $srv_map;
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
$Log->addlog('11',$this_userid,$url_id);

########################################################################

// Output
echo 'success';

?>
