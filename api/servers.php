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

class Servers
{
    // Create a game/voice server
    function create($userid,$server,$total_slots,$billingid)
    {
        $userid       = mysql_real_escape_string($userid);
        $server       = mysql_real_escape_string($server);
        $total_slots  = mysql_real_escape_string($total_slots);
        $billingid    = mysql_real_escape_string($billingid);
        
        ################################################################
        
        //
        // Get default options for this server
        //
        $game_query = "SELECT 
                         cfg.port,
                         cfg.type,
                         cfg.log_file,
                         cfg.map,
                         cfg.executable,
                         cfg.working_dir,
                         cfg.setup_dir,
                         cfg.config_file,
                         cfg.pid_file,
                         cfg.cmd_line,
                         cfg_options.* 
                       FROM cfg 
                       LEFT JOIN cfg_options ON 
                         cfg.id = cfg_options.srvid 
                       WHERE cfg.short_name = '$server'";
        
        $result_game  = @mysql_query($game_query);
        $config_arr   = array();
        
        while($row_game = mysql_fetch_array($result_game))
        {
            // DB Values
            $game_port        = $row_game['port'];
            $game_type        = $row_game['type'];
            $game_log_file    = $row_game['log_file'];
            $game_map         = $row_game['map'];
            $game_exe         = $row_game['executable'];
            $game_work_dir    = $row_game['working_dir'];
            $game_set_dir     = $row_game['setup_dir'];
            $game_config_file = $row_game['config_file'];
            $game_pid_file    = $row_game['pid_file'];
            $game_cmd_line    = $row_game['cmd_line'];
            
            // Loop through 10 config options
            for($i = 1; $i <= 10; $i++)
            {
                // Names
                $this_name  = 'opt' . $i . '_name';
                $this_edit  = 'opt' . $i . '_edit';
                $this_value = 'opt' . $i . '_value';
                
                // Values
                $res_name   = $row_game[$this_name];
                $res_edit   = $row_game[$this_edit];
                $res_value  = $row_game[$this_value];
                
                // Add to config options array
                $config_arr[$this_name]   = $res_name;
                $config_arr[$this_edit]   = $res_edit;
                $config_arr[$this_value]  = $res_value;
            }
        }

        ############################################################
        
        //
        // Get an available IP/Port combination
        //
        require('../include/functions/servers.php');
        $arr_avail_combo = gpx_avail_ip_port($server);
        
        /*
        echo 'INFO::<pre>';
        var_dump($arr_avail_combo);
        echo '</pre>';
        */
        
        // Only if available
        if($arr_avail_combo['available'] == 1)
        {
            $ip   = $arr_avail_combo['ip'];
            $port = $arr_avail_combo['port'];
        }
        else
        {
            return 'ERROR: GamePanelX Pro API: Failed to find an available IP/Port combination.';
        }

        ############################################################
        
        // Default options
        $srv_status     = 'active';
        $description    = 'New Server';
        $show_cmd_line  = 'Y';
        
        
        //
        // Create the server
        //
        $created_serverid = gpx_create_server($game_type,$server,$userid,$srv_status,$description,$ip,$port,$game_log_file,$total_slots,$game_map,$game_exe,$game_work_dir,$game_set_dir,$game_cmd_line,$show_cmd_line,$game_config_file,$config_arr);

        // Success
        if(is_numeric($created_serverid))
        {
            // Add the billing ID (Unique server ID from the external billing)
            @mysql_query("UPDATE servers SET billingid = '$billingid' WHERE id = '$created_serverid'");
            
            // Finish
            return 'success';
        }
        // Failure
        else
        {
            return 'ERROR: GamePanelX Pro API: Failed to create the server (' . $created_serverid . ')';
        }
    }
    
    
    
    
    // Suspend a game/voice server
    function suspend($ext_billingid)
    {
        $ext_billingid  = mysql_real_escape_string($ext_billingid);

        ################################################################
        
        // Get the Server ID of the server with this external Billing ID
        $result_srvid = @mysql_query("SELECT id FROM servers WHERE billingid = '$ext_billingid' ORDER BY id DESC LIMIT 0,1");
        
        while($row_srvid  = mysql_fetch_array($result_srvid))
        {
            $this_srvid = $row_srvid['id'];
        }
        
        ################################################################
        
        // Suspend the server in the DB
        @mysql_query("UPDATE servers SET status = 'suspended' WHERE id = '$this_srvid'");
        
        // Stop the server, suspend it on the gameserver side
        require('../include/functions/remote.php');
        
        if(!gpx_remote_server_stop($this_srvid,true))
        {
            return 'ERROR: GamePanelX Pro API: Failed to suspend the server';
        }
        else
        {
            return 'success';
        }
    }
    
    
    
    
    // Un-Suspend a game/voice server
    function unsuspend($ext_billingid)
    {
        $ext_billingid  = mysql_real_escape_string($ext_billingid);

        ################################################################
        
        // Get the Server ID of the server with this external Billing ID
        $result_srvid = @mysql_query("SELECT id FROM servers WHERE billingid = '$ext_billingid' ORDER BY id DESC LIMIT 0,1");
        
        while($row_srvid  = mysql_fetch_array($result_srvid))
        {
            $this_srvid = $row_srvid['id'];
        }
        
        ################################################################
        
        // Un-Suspend the server in the DB
        @mysql_query("UPDATE servers SET status = 'active' WHERE id = '$this_srvid'");
        
        // Restart the server, un-suspend it on the gameserver side
        require('../include/functions/remote.php');
        
        if(!gpx_remote_server_restart($this_srvid,true))
        {
            return 'ERROR: GamePanelX Pro API: Failed to unsuspend the server';
        }
        else
        {
            return 'success';
        }
    }
    
    
    
    
    // Terminate a game/voice server
    function terminate($ext_billingid)
    {
        $ext_billingid  = mysql_real_escape_string($ext_billingid);

        ################################################################
        
        // Get the Server ID of the server with this external Billing ID
        $result_srvid = @mysql_query("SELECT id FROM servers WHERE billingid = '$ext_billingid' ORDER BY id DESC LIMIT 0,1");
        
        while($row_srvid  = mysql_fetch_array($result_srvid))
        {
            $this_srvid = $row_srvid['id'];
        }

        ################################################################
        
        // Delete server from DB and Remote server
        require('../include/functions/servers.php');

        if(!gpx_delete_server($this_srvid))
        {
            return 'ERROR: GamePanelX Pro API: Failed to terminate the server';
        }
        else
        {
            return 'success';
        }
    }
}
