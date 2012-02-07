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



/***********************************************************************
 * 
 *                     Game/Voip Server Functions
 *
 *********************************************************************** 
*/

//
// Update a server
//
function gpx_update_server($id,$userid,$status,$description,$ip,$port,$log_file,$max_slots,$map,$executable,$working_dir,$setup_dir,$client_file_man,$subdomain,$domainid,$notes)
{
    // Escape all given values
    $safe_id            = mysql_real_escape_string($id);
    $safe_userid        = mysql_real_escape_string($userid);
    $safe_status        = mysql_real_escape_string($status);
    $safe_desc          = mysql_real_escape_string($description);
    $safe_ip            = mysql_real_escape_string($ip);
    $safe_port          = mysql_real_escape_string($port);
    $safe_log_file      = mysql_real_escape_string($log_file);
    $safe_max_slots     = mysql_real_escape_string($max_slots);
    $safe_map           = mysql_real_escape_string($map);
    $safe_exe           = mysql_real_escape_string($executable);
    $safe_working_dir   = mysql_real_escape_string($working_dir);
    $safe_setup_dir     = mysql_real_escape_string($setup_dir);
    $safe_file_man      = mysql_real_escape_string($client_file_man);
    $safe_notes         = mysql_real_escape_string($notes);    
    $safe_subdomain     = mysql_real_escape_string($subdomain);
    $safe_domainid      = mysql_real_escape_string($domainid);
    
    ####################################################################
    
    // Get current IP Address
    if(!$result_ip = @mysql_query("SELECT ip,port FROM servers WHERE id = '$safe_id'"))
    {
        return 'FAILURE: Failed to get current IP Address';
    }
    
    while($row_ip = mysql_fetch_array($result_ip))
    {
        $this_ip    = $row_ip['ip'];
        $this_port  = $row_ip['port'];
    }
    
    ####################################################################
    
    // If the new IP Address or Port is different from the old one, move the old directory to the new IP/Port
    if($this_ip != $safe_ip || $this_port != $safe_port)
    {
        require(GPX_DOCROOT . '/include/functions/remote.php');
        if(!gpx_remote_server_move_local($safe_id,$safe_userid,$safe_ip,$safe_port))
        {
            return 'FAILURE: Failed to move the account on the Remote Server';
        }
    }
    
    ####################################################################
    
    // Check if a zone rebuild was needed
    $result_rb  = @mysql_query("SELECT domainid,subdomain FROM servers WHERE id = '$safe_id'");
    
    while($row_rb = mysql_fetch_array($result_rb))
    {
        $cur_domainid   = $row_rb['domainid'];
        $cur_subdomain  = $row_rb['subdomain'];
    }
    
    // Whether to rebuild both the new domainid and old, or just rebuild current unchanged domain id
    if($cur_domainid != $safe_domainid)
    {
        $rebuild_domains  = 1;
    }
    
    // Whether to rebuild based on subdomain change
    elseif($cur_subdomain != $safe_subdomain)
    {
        $rebuild_sub      = 1;
    }
    
    ####################################################################
    
    //
    // Update Query
    //
    $update_query = "UPDATE servers SET 
                         userid           = '$safe_userid',
                         domainid         = '$safe_domainid',
                         status           = '$safe_status',
                         description      = '$safe_desc',
                         ip               = '$safe_ip',
                         port             = '$safe_port',
                         log_file         = '$safe_log_file',
                         max_slots        = '$safe_max_slots',
                         map              = '$safe_map',
                         executable       = '$safe_exe',
                         subdomain        = '$safe_subdomain',
                         working_dir      = '$safe_working_dir',
                         setup_dir        = '$safe_setup_dir',
                         client_file_man  = '$safe_file_man',
                         notes            = '$safe_notes' 
                     WHERE id = '$safe_id'";
    
    // Run the update query
    @mysql_query($update_query);
    
    ####################################################################

    //
    // Rebuild DNS if needed
    //
    if($rebuild_domains)
    {
        require(GPX_DOCROOT . '/include/functions/domains.php');

        // Rebuild both domains
        gpx_dns_rebuild_zone($cur_domainid);
        gpx_dns_rebuild_zone($safe_domainid);
        
        // Reload the zones
        gpx_dns_reload_zone($cur_domainid);
        gpx_dns_reload_zone($safe_domainid);
    }
    // Just 1
    elseif($rebuild_sub)
    {
        require(GPX_DOCROOT . '/include/functions/domains.php');
        
        // Rebuild the zone
        $result_rebuild = gpx_dns_rebuild_zone($cur_domainid);
        if($result_rebuild != 'success')
        {
            die($result_rebuild);
        }
      
        // Reload the zone
        $result_reload  = gpx_dns_reload_zone($cur_domainid);
        if($result_reload != 'success')
        {
            die($result_reload);
        }
    }

    ####################################################################
    
    // Finish
    return true;
}




//
// Update a server (regular users)
//
function gpx_update_user_server($id,$userid,$description,$log_file,$map)
{
    // Escape all given values
    $safe_id          = mysql_real_escape_string($id);
    $safe_userid      = mysql_real_escape_string($userid);
    $safe_desc        = mysql_real_escape_string($description);
    $safe_log_file    = mysql_real_escape_string($log_file);
    $safe_map         = mysql_real_escape_string($map);

    ####################################################################
    
    // Update Query
    $update_query = "UPDATE servers SET 
                       description  = '$safe_desc',
                       log_file     = '$safe_log_file',
                       map          = '$safe_map' 
                     WHERE 
                       id = '$safe_id' 
                       AND userid = '$safe_userid'";
    
    // Run the update query
    if(!mysql_query($update_query))
    {
        return 'FAILURE: Failed to update the servers table';
    }
    else
    {
        // Finish
        return true;
    }
}










//
// Create Server
// (All values are normal, except '$config_array' takes an array of all 10 config options
// 
function gpx_create_server($type,$server,$userid,$status,$description,$ip,$port,$log_file,$max_slots,$map,$executable,$working_dir,$setup_dir,$cmd_line,$show_cmd_line,$config_file,$config_array)
{
    // For API Usage
    if(!defined('GPX_DOCROOT'))
    {
        define('GPX_DOCROOT', '../');
    }
    
    // Escape all given values
    $safe_type            = mysql_real_escape_string($type);
    $safe_server          = mysql_real_escape_string($server);
    $safe_userid          = mysql_real_escape_string($userid);
    $safe_status          = mysql_real_escape_string($status);
    $safe_desc            = mysql_real_escape_string($description);
    $safe_ip              = mysql_real_escape_string($ip);
    $safe_port            = mysql_real_escape_string($port);
    $safe_log_file        = mysql_real_escape_string($log_file);
    $safe_max_slots       = mysql_real_escape_string($max_slots);
    $safe_map             = mysql_real_escape_string($map);
    $safe_exe             = mysql_real_escape_string($executable);
    $safe_working_dir     = mysql_real_escape_string($working_dir);
    $safe_setup_dir       = mysql_real_escape_string($setup_dir);
    $safe_cmd_line        = mysql_real_escape_string($cmd_line);
    $safe_show_cmd_line   = mysql_real_escape_string($show_cmd_line);
    $safe_notes           = mysql_real_escape_string($notes);
    $safe_config_file     = mysql_real_escape_string($config_file);


    //
    // Safety first
    //
    if(!empty($config_array) && !is_array($config_array))
    {
        return 'FAILURE: Config options were not given in an array';
    }
    
    // Make sure type is correct
    $allowed_types = array('game','voip','other');
    
    if(!in_array($safe_type, $allowed_types))
    {
        #return 'FAILURE: Invalid type given';
        
        // Default to game
        $safe_type  = 'game';
    }
    
    // Probably coming from the API
    if(!defined('GPX_DOCROOT'))
    {
        require('../include/functions/remote.php');
    }
    // Normal pages
    else
    {
        require(GPX_DOCROOT . '/include/functions/remote.php');
    }
    
    // Check if we should start the server after creation
    if(!defined('GPX_CFG_START_SRV_AFTER_CREATE'))
    {
        $start_server = true;
    }
    else
    {
        if(GPX_CFG_START_SRV_AFTER_CREATE == 'Y')
        {
            $start_server = true;
        }
        else
        {
            $start_server = false;
        }
    }

    ####################################################################
    
    // Get server defaults
    $result_df  = @mysql_query("SELECT id,port,type,short_name,log_file,map,executable,working_dir,setup_dir FROM cfg WHERE short_name = '$safe_server'");

    while($row_df = mysql_fetch_array($result_df))
    {
        $cfg_id       = $row_df['id'];
        $def_port     = $row_df['port'];
        $def_type     = $row_df['type'];
        $def_short_nm = $row_df['short_name'];
        $def_log_file = $row_df['log_file'];
        $def_exe      = $row_df['executable'];
        $def_work_dir = $row_df['working_dir'];
        $def_set_dir  = $row_df['setup_dir'];
        $def_map      = $row_df['map'];
    }
    
    
    
    // Get cfgid's for each of the 4 simple id's
    $result_spid  = @mysql_query("SELECT id,simpleid FROM cfg_items WHERE srvid = '$cfg_id' AND simpleid IN (1,2,3,4) ORDER BY simpleid ASC");

    while($row_spid = mysql_fetch_array($result_spid))
    {
        $this_cfgid = $row_spid['id'];
        $this_smpid = $row_spid['simpleid'];
        
        // IP Address
        if($this_smpid == 1)
        {
            $cfgid_ip = $this_cfgid;
        }
        // Port
        elseif($this_smpid == 2)
        {
            $cfgid_port = $this_cfgid;
        }
        // Max Slots
        elseif($this_smpid == 3)
        {
            $cfgid_maxslots = $this_cfgid;
        }
        // Map
        elseif($this_smpid == 4)
        {
            $cfgid_map  = $this_cfgid;
        }
    }

    ########################################################################
    
    // Get network ID for this IP
    $result_net = @mysql_query("SELECT id FROM network WHERE ip = '$ip'");
    $row_net    = mysql_fetch_row($result_net);
    $network_id = $row_net[0];
    
    ########################################################################
    
    // Array for config inserts
    #$config_arr = array();
    
    
    
    /*
    // Run through cfg_x items
    foreach($_GET as $cfg => $cfg_val)
    {
        // Simple ID's
        if(preg_match("/^smptxt_\d+$/", $cfg))
        {
            // Remove "cfg_", to get a valid ItemID
            $cfg  = str_replace('smptxt_', '', $cfg);
            
            // Port
            if($cfg == '2')
            {
                $server_port = $cfg_val;
                
                // Add to insert array (There is no value in `servers_cfg` for these, use respective `servers` values for SimpleID's
                #$config_arr[] = "INSERT INTO servers_cfg (srvid,itemid) VALUES('%SRVID%','$cfgid_port')";
                $insert_cfg .= "('%SRVID%','$cfgid_port',''),";
            }
            // Max Slots
            elseif($cfg == '3')
            {
                $server_max_slots = $cfg_val;
                
                // Add to insert array
                #$config_arr[] = "INSERT INTO servers_cfg (srvid,itemid) VALUES('%SRVID%','$cfgid_maxslots')";
                $insert_cfg .= "('%SRVID%','$cfgid_maxslots',''),";
            }
            // Map
            elseif($cfg == '4')
            {
                $server_map = $cfg_val;
                
                // Add to insert array
                #$config_arr[] = "INSERT INTO servers_cfg (srvid,itemid) VALUES('%SRVID%','$cfgid_map')";
                $insert_cfg .= "('%SRVID%','$cfgid_map',''),";
            }
        }
        
        
        // Generic cfg_x items only
        elseif(preg_match("/^cfg_\d+$/", $cfg))
        {
            // Remove "cfg_", to get a valid ItemID
            $cfg  = str_replace('cfg_', '', $cfg);
            
            // Only if there's both an ID and a value
            if(!empty($cfg) && !empty($cfg_val))
            {
                // Add to insert array; later, foreach through it and mysql_query it
                #$config_arr[] = "INSERT INTO servers_cfg (srvid,itemid,item_value) VALUES('%SRVID%','$cfg','$cfg_val')";
                $insert_cfg .= "('%SRVID%','$cfg','$cfg_val'),";
            }
        }
    }
    
    // Add IP into the mix
    #$config_arr[] = "INSERT INTO servers_cfg (srvid,itemid) VALUES('%SRVID%','$cfgid_ip')";
    $insert_cfg .= "('%SRVID%','$cfgid_ip',''),";
    */
    
    ########################################################################
    
    // Make sure IP:Port combo isn't in use
    $result_used1 = @mysql_query("SELECT id FROM servers WHERE networkid = '$network_id' AND port = '$port' LIMIT 1");
    $row_used1    = mysql_fetch_row($result_used1);
    $port_used1   = $row_used1[0];
    
    if(!empty($port_used1)) die('That IP/Port combination is already in use!');
    
    // Setup variables
    $server_port      = $port;
    $server_max_slots = $max_slots;
    
    if(!empty($map)) 
        $server_map  = $map;
    else 
        $server_map  = $def_map;
    
    // Check required
    if($def_type == 'game' && empty($server_map)) die('The Map field was empty.  Please double-check and try again (server: ' . $def_short_nm . ', map: ' . $server_map . ', def map: ' . $def_map . ')');
    
    // Insert server
    @mysql_query("INSERT INTO servers 
                    (userid,networkid,port,max_slots,date_created,type,server,description,map,log_file,executable,working_dir,setup_dir) 
                  VALUES('$safe_userid','$network_id','$server_port','$server_max_slots',NOW(),'$def_type','$def_short_nm','$safe_desc','$server_map','$def_log_file','$def_exe','$def_work_dir','$def_set_dir')") or die('Failed to insert the server!');
    
    $this_serverid  = mysql_insert_id();

    if(empty($this_serverid)) die('Didnt find a server ID!  Bailing out!');

    ########################################################################
    
    //
    // Server cfg items
    //
    
    // Setup string for multi-insert
    $insert_cfg = 'INSERT INTO servers_cfg (srvid,itemid,item_value) VALUES ';
    
    // Insert the 4 important types (ip/port/maxslots/map)
    $insert_cfg .= "('$this_serverid','$cfgid_ip',''),";        // IP
    $insert_cfg .= "('$this_serverid','$cfgid_port',''),";      // Port
    $insert_cfg .= "('$this_serverid','$cfgid_maxslots',''),";  // Max Slots
    $insert_cfg .= "('$this_serverid','$cfgid_map',''),";       // Map
    
    // Also add all other generic items (not simple id's)
    $result_spid  = @mysql_query("SELECT id,default_value FROM cfg_items WHERE srvid = '$cfg_id' AND simpleid = '0' AND deleted = '0' ORDER BY id ASC");

    while($row_spid = mysql_fetch_array($result_spid))
    {
        $this_itemid  = $row_spid['id'];
        $this_val     = $row_spid['default_value'];
        
        // Generic Item
        $insert_cfg .= "('$this_serverid','$this_itemid','$this_val'),";
    }
    
    // Lose the ending comma
    $insert_cfg = substr($insert_cfg, 0, -1);
    
    // Run a multi-insert for server cfg items
    @mysql_query($insert_cfg) or die('Failed to insert the config items: '.mysql_error());
    
    /*
    // Insert each config value as a new row
    foreach($config_arr as $query)
    {
        $query  = str_replace('%SRVID%', $this_serverid, $query);
        
        // Run the insert query
        @mysql_query($query) or die('Failed to insert the config item: '.mysql_error());
    }
    
    // Replace server ID var
    $insert_cfg = str_replace('%SRVID%', $this_serverid, $insert_cfg);
    */
    
    ########################################################################
    
    // Begin CMD Line
    $cmd_line = './' . $def_exe;

    // Get this all server's current config items
    $server_query = "SELECT 
                        servers_cfg.item_value,
                        cfg_items.simpleid,
                        cfg_items.name 
                     FROM servers_cfg 
                     LEFT JOIN cfg_items ON 
                        servers_cfg.itemid = cfg_items.id 
                     WHERE 
                        servers_cfg.srvid = '$this_serverid' 
                        AND servers_cfg.deleted = '0' 
                     ORDER BY 
                        cfg_items.simpleid ASC,
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
            $cfg_val  = $ip;
        }
        // Port
        elseif($cfg_simpleid == 2)
        {
            $cfg_val  = $server_port;
        }
        // Max Slots
        elseif($cfg_simpleid == 3)
        {
            $cfg_val  = $server_max_slots;
        }
        // Map
        elseif($cfg_simpleid == 4)
        {
            $cfg_val  = $server_map;
        }
        
        
        // Add to command-line
        if(!empty($cfg_name) && !empty($cfg_val))
        {
            $cmd_line .= ' ' . $cfg_name . ' ' . $cfg_val;
        }
    }

    // Save new cmd line
    @mysql_query("UPDATE servers SET cmd_line = '$cmd_line' WHERE id = '$this_serverid'");

    ########################################################################

    // Create server
    $result_create  = gpx_remote_create_server($this_serverid,false);

    // Return result
    if($result_create == 'success')
    {
        return $this_serverid;
    }
    else return 'ERROR: ' . $result_create;
}








//
// Delete Server
// 
function gpx_delete_server($id)
{
    // Escape all given values
    $safe_id = mysql_real_escape_string($id);

    //
    // Safety first
    //
    if(empty($safe_id))
    {
        return 'FAILURE: No server ID was given';
    }
    elseif(!empty($safe_id) && !is_numeric($safe_id))
    {
        return 'FAILURE: Invalid ID given';
    }
    
    ####################################################################
    
    // For API Usage
    if(!defined('GPX_DOCROOT'))
    {
        define('GPX_DOCROOT', '../');
    }
    
    ####################################################################
    
    //
    // Delete on Remote Server
    //
    require(GPX_DOCROOT . '/include/functions/remote.php');

    if(!gpx_remote_delete_server($safe_id))
    {
        return 'FAILURE: Failed to delete on the Remote Server';
    }

    ####################################################################
    
    
    //
    // Delete the server
    //

    // `servers`
    if(!@mysql_query("DELETE FROM servers WHERE id='$safe_id'"))
    {
        return 'FAILURE: Failed to delete the server from the database(1)';
    }
    
    // `servers_options`
    if(!@mysql_query("DELETE FROM servers_options WHERE srvid='$safe_id'"))
    {
        return 'FAILURE: Failed to delete the server from the database(2)';
    }
    
    // `servers_addons`
    if(!@mysql_query("DELETE FROM servers_addons WHERE srvid='$safe_id'"))
    {
        return 'FAILURE: Failed to delete the server from the database(3)';
    }
    
    
    return true;
}






//
// Check Server Creation Status
//
function gpx_status_create_server($id)
{
    // Escape all given values
    $safe_id  = mysql_real_escape_string($id);

    // Get Server Creation status
    require(GPX_DOCROOT . '/include/functions/remote.php');
    
    if(!$creation_status = @gpx_remote_status_create_server($safe_id))
    {
        return 'FAILURE: Failed to check the status on the Remote Server';
    }
    else
    {
        // If process is complete
        if(trim($creation_status) == 'complete')
        {
            // Update `creation_status` field with current status
            if(!mysql_query("UPDATE servers SET creation_status = 'complete' WHERE id = '$safe_id'"))
            {
                return 'FAILURE: Failed to update the server status in the database';
            }
            
            return 'complete';
        }
        else
        {
            return $creation_status;
        }
    }
}










//
// Check Server Update Status
//
function gpx_status_update_server($serverid)
{
    // Escape all given values
    $safe_id  = mysql_real_escape_string($serverid);

    // Get Server Update status
    require(GPX_DOCROOT . '/include/functions/remote.php');
    
    if(!$update_status = @gpx_remote_status_update_server($safe_id))
    {
        return 'FAILURE: Failed to check the status on the Remote Server';
    }
    else
    {
        // If process is complete
        if(trim($update_status) == 'complete')
        {
            // Update `update_status` field with current status
            if(!mysql_query("UPDATE servers SET update_status = 'complete' WHERE id = '$safe_id'"))
            {
                return 'FAILURE: Failed to update the server status in the database';
            }
            
            return 'complete';
        }
        else
        {
            return $update_status;
        }
    }
}





//
// Check if user owns a server
//
function gpx_check_user_owns($serverid,$userid)
{
    $serverid = mysql_real_escape_string($serverid);
    $userid   = mysql_real_escape_string($userid);
    
    $result_owns  = @mysql_query("SELECT COUNT(id) AS thecount FROM servers WHERE id = '$serverid' AND userid = '$userid' AND status = 'active'");

    while($row_owns = mysql_fetch_array($result_owns))
    {
        $user_owns  = $row_owns['thecount'];
    }

    // Bad
    if($user_owns == 0)
    {
        return false;
    }
    // Okay
    else
    {
        return true;
    }
}













//
// Get an available IP Address / Port combination
//
function gpx_avail_ip_port($server)
{
    $server = mysql_real_escape_string($server);
    
    ####################################################################
    
    // Get load limit from config
    $result_cfg = @mysql_query("SELECT value FROM configuration WHERE setting = 'BalanceLoadLimit'");
    
    while($row_cfg  = mysql_fetch_array($result_cfg))
    {
        $config_load_limit  = $row_cfg['value'];
    }
    
    // Get whether to use default ports or any available
    $result_def = @mysql_query("SELECT value FROM configuration WHERE setting = 'BalanceDefaultPortOnly'");
    
    while($row_def  = mysql_fetch_array($result_def))
    {
        $config_default_port_only  = $row_def['value'];
    }
    
    ####################################################################
    
    // Get load averages of network servers
    $query_avg  = "SELECT 
                      networkid,
                      AVG(cpu) AS cpuload 
                   FROM loadavg 
                   LEFT JOIN network ON 
                      loadavg.networkid = network.id 
                   WHERE network.available = 'Y' 
                   GROUP BY networkid 
                   ORDER BY AVG(cpu) ASC";
    
    $result_avg = @mysql_query($query_avg);

    while($row_avg  = mysql_fetch_array($result_avg))
    {
        $srv_networkid  = $row_avg['networkid'];
        $srv_loadavg    = $row_avg['cpuload'];
        
        // Load is OK; use this server
        if($srv_loadavg <= $config_load_limit)
        {
            $this_networkid = $srv_networkid;
            break;
        }
        else
        {
            die("ERROR: Server load average ($srv_loadavg) is higher than the allowed limit ($config_load_limit).");
        }
    }
    
    // No available servers
    if(empty($this_networkid))
    {
        die('ERROR: No available network server info.  Has the cron job been run (include/cron.php)?');
    }

    ####################################################################
    
    // Get default port for this server
    $result_port  = @mysql_query("SELECT port,reserved_ports FROM cfg WHERE short_name = '$server' ORDER BY id DESC LIMIT 0,1");
    
    while($row_port = mysql_fetch_array($result_port))
    {
        $default_port   = $row_port['port'];
        $reserved_ports = $row_port['reserved_ports'];
    }
    
    ####################################################################
    
    // Loop through the IP's using this network server
    $result_ips = @mysql_query("SELECT id,ip FROM network WHERE id = '$this_networkid' OR parentid = '$this_networkid' AND available = 'Y'");

    while($row_ips  = mysql_fetch_array($result_ips))
    {
        // Current IP Address in the loop
        $this_netid = $row_ips['id'];
        $this_ip    = $row_ips['ip'];
        
        // Check if this IP is available with default port
        $result_avl = @mysql_query("SELECT id FROM servers WHERE networkid = '$this_netid' AND port= '$default_port'");
        $row_avl    = mysql_fetch_row($result_avl);
        $srv_cnt    = $row_avl[0];
        
        //
        // Default Ports ONLY
        // Check for this IP / Default Port availability
        if($config_default_port_only == 'Y')
        {
            // Check default availability
            if(empty($srv_cnt))
            {
                $available_ip   = $this_ip;
                $available_port = $default_port;
                break;
            }
        }
        //
        // Allow Non-Default Ports to be used
        //
        else
        {
            // Check default availability
            if(empty($srv_cnt))
            {
                $available_ip   = $this_ip;
                $available_port = $default_port;
                break;
            }
            
            ############################################################
            
            // Start with 60 ports above the default port to be safe
            $curr_port  = $default_port+60;
            
            // Find an available port (Try up to 50 non-default ports, then try a new IP)
            for($i=0; $i <= 50; $i++)
            {
                $curr_port  = $curr_port+2;
                
                // Don't use any reserved ports
                $arr_reserved = explode(',', $reserved_ports);
                if(!in_array($curr_port, $arr_reserved))
                {
                    // Start list
                    if($i==0)
                    {
                        $ports_list = $curr_port;
                    }
                    // Continue list with commas
                    else
                    {
                        $ports_list .= ',' . $curr_port;
                    }
                }                
            }
            $ports_list .= ",27015";
            
            // Array of non-default ports to try
            $non_default_arr = explode(',', $ports_list);

            ############################################################
            
            // Check for servers using these ports with this IP
            $result_using = @mysql_query("SELECT port FROM servers WHERE networkid = '$this_networkid' AND port IN ($ports_list)");
            $count_ports  = mysql_num_rows($result_using);
            $arr_used = array();
            
            // Get mysql array, before convert
            while($row_using  = mysql_fetch_assoc($result_using))
            {
                $used_ports[] = $row_using;
            }
            
            // Create new array, converted from mysql array
            for($x=0; $x <= $count_ports; $x++)
            {
                $arr_used[$x] = $used_ports[$x]['port'];
            }

            ############################################################

            // Get all non-default ports that are unused for this IP Address
            $unused_ports = array_diff($non_default_arr, $arr_used);
            $new_arr  = array();
            
            // Make a brand new array with new indexes for these ports
            foreach($unused_ports as $portz)
            {
                $new_arr[]  = $portz;
            }
            
            // Set the new combo
            if(!empty($unused_ports))
            {
                // Use the first available port with this IP
                $available_ip   = $this_ip;
                $available_port = $new_arr[0];
                break;
            }
        }
    }
    
    ####################################################################
    
    // Return array with IP/Port
    $avail_arr  = array();
    
    if(!empty($available_ip) && !empty($available_port))
    {
        $avail_arr['available'] = '1';
        $avail_arr['ip']        = $available_ip;
        $avail_arr['port']      = $available_port;
    }
    else
    {
        $avail_arr['available'] = '0';
    }
    

    return $avail_arr;
}

?>
