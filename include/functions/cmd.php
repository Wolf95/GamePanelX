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
 *                     Command-Line Functions
 *
 *********************************************************************** 
*/


//
// Parse a raw command line (with % options) and turn it into a readable/executable cmd line
// (ID provided is the ID from the `servers` table)
//
function gpx_cmd_parse($id)
{
    // Escape the ID
    $safe_id = mysql_real_escape_string($id);
    
    // Make sure the ID wasn't left empty
    if(empty($safe_id))
    {
        die('<center><b>Error:</b> <i>cmd.php:</i> No ID given to parse the CMD Line!</center>');
    }
    
    // Query for settings of this server
    $query_parse = "SELECT 
                      servers.log_file,
                      servers.ip,
                      servers.port,
                      servers.max_slots,
                      servers.map,
                      servers.executable,
                      servers.cmd_line,
                      servers.working_dir,
                      servers.setup_dir,
                      cfg.mod_name 
                    FROM servers 
                    LEFT JOIN cfg ON
                      servers.server = cfg.short_name 
                    WHERE servers.id='$safe_id'";
    $result_parse = @mysql_query($query_parse) or die('<center><b>Error:</b> <i>cmd.php:</i> Failed to query to parse the command-line!</center>');
    
    while($row_parse = mysql_fetch_array($result_parse))
    {
        $server_mod_name      = $row_parse['mod_name'];
        $server_log_file      = $row_parse['log_file'];
        $server_ip            = $row_parse['ip'];
        $server_port          = $row_parse['port'];
        $server_max_slots     = $row_parse['max_slots'];
        $server_map           = $row_parse['map'];
        $server_executable    = $row_parse['executable'];
        $server_raw_cmd       = $row_parse['cmd_line'];
        $server_working_dir   = $row_parse['working_dir'];
        $server_setup_dir     = $row_parse['setup_dir'];
    }
    
    
    
    //
    // Replace all % options with real values
    //
    $full_cmd_line = str_replace('%log_file%', $server_log_file, $server_raw_cmd);        // Log File
    $full_cmd_line = str_replace('%ip%', $server_ip, $full_cmd_line);                     // IP Address
    $full_cmd_line = str_replace('%mod_name%', $server_mod_name, $full_cmd_line);         // Game Mod
    $full_cmd_line = str_replace('%port%', $server_port, $full_cmd_line);                 // Port
    $full_cmd_line = str_replace('%max_slots%', $server_max_slots, $full_cmd_line);       // Max Slots
    $full_cmd_line = str_replace('%map%', $server_map, $full_cmd_line);                   // Startup Map
    $full_cmd_line = str_replace('%executable%', $server_executable, $full_cmd_line);     // Executable
    $full_cmd_line = str_replace('%working_dir%', $server_working_dir, $full_cmd_line);   // Working Directory
    $full_cmd_line = str_replace('%setup_dir%', $server_setup_dir, $full_cmd_line);       // Setup Directory
    
    
    // Parse all 10 config options and replace %opt1% etc with real values.
    $result_opts = @mysql_query("SELECT * FROM servers_options WHERE srvid='$safe_id'") or die('<center><b>Error:</b> <i>cmd.php:</i> Failed to get all command-line options!</center>');
    
    $row_opts = mysql_fetch_array($result_opts);

    // Loop through all 10 config options
    for($i=0; $i <= 10; $i++)
    {
        // Option names
        $this_opt   = '%opt' . $i . '%';
        $this_name  = 'opt' . $i . '_name';
        $this_value = 'opt' . $i . '_value';
        $this_edit  = 'opt' . $i . '_edit';
        
        // Option values from the DB
        $row_name   = $row_opts[$this_name];
        $row_value  = $row_opts[$this_value];
        $row_edit   = $row_opts[$this_edit];

        
        // Add single option
        if($row_value == '%switch_Y%')
        {
            $full_cmd_line = str_replace($this_opt, $row_name, $full_cmd_line);
        }
        // Remove option
        elseif($row_value == '%switch_N%')
        {
            $full_cmd_line = str_replace($this_opt, " ", $full_cmd_line);
        }
        // Regular options
        else
        {
            // Replace the raw % option with a real value
            $full_cmd_line = str_replace($this_opt, $row_value, $full_cmd_line);
        }
    }
    
    
    
    // Finish function
    return $full_cmd_line;
}



########################################################################


//
// Take a POST array and use it to update the given server ID
//
function gpx_cmd_update($id,$post_array)
{
    // Escape the variables
    $safe_id = mysql_real_escape_string($id);
    
    // Make sure the ID wasn't left empty
    if(empty($safe_id))
    {
        die('<center><b>Error:</b> <i>cmd.php:</i> No ID given to update the CMD Line!</center>');
    }

    // POST Values
    $post_raw_cmd = $post_array['raw_cmd_line'];

    
    // Begin sql update
    $update_options = "UPDATE servers_options SET ";
    
    
    // Loop through all 10 config options
    for($i = 1; $i <= 10; $i++)
    {
        // Variable names
        $this_opt     = '%opt' . $i . '%';
        $this_name    = 'opt' . $i . '_name';
        $this_value   = 'opt' . $i . '_value';
        $this_edit    = 'opt' . $i . '_edit';
        
        // Values
        $opt_name   = $post_array[$this_name];
        $opt_value  = $post_array[$this_value];
        $opt_edit   = $post_array[$this_edit];
        
        // Parse the optX_edit
        if(!empty($opt_edit) && $opt_edit = 'on')
        {
            $opt_edit_safe = 'Y';
        }
        else
        {
            $opt_edit_safe = 'N';
        }
        
        
        //
        // Add to the update query
        //
        
        // Before 10
        if($i < 10)
        {
            $update_options .= "$this_name='$opt_name',$this_value='$opt_value',$this_edit='$opt_edit_safe',";
        }
        // End at 10
        else
        {
            $update_options .= "$this_name='$opt_name',$this_value='$opt_value',$this_edit='$opt_edit_safe' WHERE srvid='$safe_id'";
        }
    }

    // Run update queries
    @mysql_query("UPDATE servers SET cmd_line='$post_raw_cmd' WHERE id='$safe_id'") or die('<center><b>Error:</b> <i>cmd.php:</i> Failed to update the command line!</center>');
    @mysql_query($update_options) or die('<center><b>Error:</b> <i>cmd.php:</i> Failed to update the command line options!</center>');
    
    
    // Finish
    return true;
}








//
// Normal Users: Take a POST array and use it to update the given server ID
//
function gpx_cmd_update_user($id,$post_array)
{
    // Escape the variables
    $safe_id = mysql_real_escape_string($id);
    
    // Make sure the ID wasn't left empty
    if(empty($safe_id))
    {
        die('<center><b>Error:</b> <i>cmd.php:</i> No ID given to update the CMD Line!</center>');
    }

    // POST Values
    $post_raw_cmd = $post_array['raw_cmd_line'];

    ########################################################################
    
    //
    // Get all 10 "client-editable" field values
    // (only let the client update what they're allowed)
    //
    $result_cledit  = @mysql_query("SELECT * FROM servers_options WHERE srvid = '$safe_id'");
    $arr_edit       = array();
    
    while($row_cledit = mysql_fetch_assoc($result_cledit))
    {
        $arr_edit[] = $row_cledit;
    }
    
    ########################################################################

    // Begin sql update
    $update_options = "UPDATE servers_options SET ";

    // Loop through all 10 config options
    for($i = 1; $i <= 10; $i++)
    {
        // Variable names
        $this_opt     = '%opt' . $i . '%';
        $this_name    = 'opt' . $i . '_name';
        $this_value   = 'opt' . $i . '_value';
        $this_edit    = 'opt' . $i . '_edit';
        
        // Values
        $opt_name   = $post_array[$this_name];
        $opt_value  = $post_array[$this_value];
        $opt_edit   = $post_array[$this_edit];
        
        // Parse the optX_edit
        if(!empty($opt_edit) && $opt_edit = 'on')
        {
            $opt_edit_safe = 'Y';
        }
        else
        {
            $opt_edit_safe = 'N';
        }
        
        
        //
        // Add JUST the value to the update query (ONLY if it's client-editable)
        //
        
        // Before 10
        if($i < 10)
        {
            // Only if client-editable
            if($arr_edit[0]['opt' . $i . '_edit'] == 'Y')
            {
                $update_options .= "$this_value='$opt_value',";
            }
        }
        // End at 10
        else
        {
            // Only if client-editable
            if($arr_edit[0]['opt' . $i . '_edit'] == 'Y')
            {
                $update_options .= "$this_value='$opt_value' WHERE srvid='$safe_id'";
            }
        }
        
        // END it
        if($i == 10 && !preg_match("/\ WHERE\ srvid/",$update_options))
        {
            // Strip the last comma off
            $update_options = substr($update_options, 0, -1);
            
            // Add the 'WHERE' into the SQL
            $update_options .= " WHERE srvid='$safe_id'";
        }
    }


    //
    // Update just the cmd-line options table
    //
    @mysql_query($update_options) or die('<center><b>Error:</b> <i>cmd.php:</i> Failed to update the command line options!</center>');
    
    
    // Finish
    return true;
}


########################################################################


//
// Supported Server config options update
//
function gpx_supported_cmd_update($id,$post_array)
{  
    // Escape the variables
    $safe_id = mysql_real_escape_string($id);
    
    // Make sure the ID wasn't left empty
    if(empty($safe_id))
    {
        die('<center><b>Error:</b> <i>cmd.php:</i> No ID given to update the CMD Line!</center>');
    }

    // POST Values
    $post_raw_cmd = $post_array['raw_cmd_line'];

    
    // Begin sql update
    $update_options = "UPDATE cfg_options SET ";
    
    
    // Loop through all 10 config options
    for($i = 1; $i <= 10; $i++)
    {
        // Variable names
        $this_opt     = '%opt' . $i . '%';
        $this_name    = 'opt' . $i . '_name';
        $this_value   = 'opt' . $i . '_value';
        $this_edit    = 'opt' . $i . '_edit';
        
        // Values
        $opt_name   = $post_array[$this_name];
        $opt_value  = $post_array[$this_value];
        $opt_edit   = $post_array[$this_edit];
        
        // Parse the optX_edit
        if(!empty($opt_edit) && $opt_edit = 'on')
        {
            $opt_edit_safe = 'Y';
        }
        else
        {
            $opt_edit_safe = 'N';
        }
        
        
        //
        // Add to the update query
        //
        
        // Before 10
        if($i < 10)
        {
            $update_options .= "$this_name='$opt_name',$this_value='$opt_value',$this_edit='$opt_edit_safe',";
        }
        // End at 10
        else
        {
            $update_options .= "$this_name='$opt_name',$this_value='$opt_value',$this_edit='$opt_edit_safe' WHERE srvid='$safe_id'";
        }
    }

    // Run update queries
    @mysql_query("UPDATE cfg SET cmd_line='$post_raw_cmd' WHERE id='$safe_id'") or die('<center><b>Error:</b> <i>cmd.php:</i> Failed to update the command line!</center>');
    @mysql_query($update_options) or die('<center><b>Error:</b> <i>cmd.php:</i> Failed to update the command line options!</center>');
    
    
    // Finish
    return true;
}

?>
