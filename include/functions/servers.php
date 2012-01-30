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
        return 'FAILURE: Invalid type given';
    }
    
    ####################################################################
    
    // Insert into `servers` table
    $create_query = "INSERT INTO servers (userid,port,max_slots,date_created,type,status,show_cmd_line,server,ip,log_file,description,map,executable,cmd_line,working_dir,setup_dir,config_file,notes) VALUES('$safe_userid','$safe_port','$safe_max_slots',NOW(),'$safe_type','$safe_status','$safe_show_cmd_line','$safe_server','$safe_ip','$safe_log_file','$safe_desc','$safe_map','$safe_exe','$safe_cmd_line','$safe_working_dir','$safe_setup_dir','$safe_config_file','$safe_notes')";

    // Run the query
    if(!mysql_query($create_query))
    {
        return 'FAILURE: Failed to add the server to the database';
    }

    // Get the server ID of the server we just created
    if(!$result_serverid = @mysql_query("SELECT id FROM servers WHERE ip='$safe_ip' AND port='$safe_port' ORDER BY id DESC LIMIT 0,1"))
    {
        return 'FAILURE: Failed to get server info';
    }
    
    while($row_serverid = mysql_fetch_array($result_serverid))
    {
        $this_serverid = $row_serverid['id'];
    }


    // Begin insert config query
    $insert_config = "INSERT INTO servers_options VALUES('','$this_serverid',";
    
    // Loop through all 10 config options, insert into `servers_options` table
    for($i = 1; $i <= 10; $i++)
    {
        // Names
        $this_name  = 'opt' . $i . '_name';
        $this_edit  = 'opt' . $i . '_edit';
        $this_value = 'opt' . $i . '_value';
        
        // Values
        $res_name   = $config_array[$this_name];
        $res_edit   = $config_array[$this_edit];
        $res_value  = $config_array[$this_value];
        
        // Fix the 'edit'
        if($res_edit == 'on')
        {
            $nice_edit = 'Y';
        }
        else
        {
            $nice_edit = '';
        }
        
        // Add to the query
        if($i == 10)
        {
            $insert_config .= "'$res_name','$nice_edit','$res_value')";
        }
        else
        {
            $insert_config .= "'$res_name','$nice_edit','$res_value',";
        }
    }
    
    // Insert into `server_options` table
    if(!@mysql_query($insert_config))
    {
        return 'FAILURE: Failed to add the server to the database(2)';
    }

    ####################################################################
    
    //
    // Create on Remote Server
    //

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
    
    // Failure
    if(!gpx_remote_create_server($this_serverid,$start_server))
    {
        return 'FAILURE: Failed to create on the Remote Server';
    }
    // Success
    else
    {
        //
        // Send notification
        //
        
        // Probably coming from the API
        if(!defined('GPX_DOCROOT'))
        {
            require_once('../include/functions/notifications.php');
        }
        // Normal pages
        else
        {
            require_once(GPX_DOCROOT . '/include/functions/notifications.php');
        }
        
        // Add new server notification
        gpx_notify_add(2,$this_serverid);

        // Finish, return the server id that was created.
        return $this_serverid;
    }
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
    }
    
    // No available servers
    if(empty($this_networkid))
    {
        die('ERROR: No available servers');
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
    $result_ips = @mysql_query("SELECT ip FROM network WHERE id = '$this_networkid' OR parentid = '$this_networkid' AND available = 'Y'");

    while($row_ips  = mysql_fetch_array($result_ips))
    {
        // Current IP Address in the loop
        $this_ip  = $row_ips['ip'];
        
        //
        // Default Ports ONLY
        // Check for this IP / Default Port availability
        if($config_default_port_only == 'Y')
        {
            // Check if this IP is available with default port
            $result_avl = @mysql_query("SELECT COUNT(id) AS thecount FROM servers WHERE ip = '$this_ip' AND port= '$default_port'");
            while($row_avl  = mysql_fetch_array($result_avl))
            {
                $srv_cnt = $row_avl['thecount'];
            }
            
            // Check default availability
            if($srv_cnt == 0)
            {
                $available_ip   = $this_ip;
                $available_port = $default_port;
                break;
            }
        }
        //
        // Allow NON-DEFAULT Ports to be used
        //
        else
        {
            // First, check if this IP is available with default port
            $result_avl = @mysql_query("SELECT COUNT(id) AS thecount FROM servers WHERE ip = '$this_ip' AND port= '$default_port'");
            while($row_avl  = mysql_fetch_array($result_avl))
            {
                $srv_cnt = $row_avl['thecount'];
            }
            
            // Check default availability
            if($srv_cnt == 0)
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
            $result_using = @mysql_query("SELECT port FROM servers WHERE ip = '$this_ip' AND port IN ($ports_list)");
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
