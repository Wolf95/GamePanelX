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
 *                     XML File Import/Export functions
 *
 *********************************************************************** 
*/

//
// GamePanelX Pro import
//
function gpx_xml_import($xml_filename)
{
    // Check empty
    if(empty($xml_filename))
    {
        return false;
    }
    
    ####################################################################
    
    // Load XML file
    $xml = simplexml_load_file($xml_filename);
        
    // System-Specific variables
    $server_type          = mysql_real_escape_string($xml->type);
    $server_short_name    = mysql_real_escape_string($xml->short_name);
    $server_long_name     = mysql_real_escape_string($xml->long_name);
    $server_steam_name    = mysql_real_escape_string($xml->steam_name);
    $server_query_name    = mysql_real_escape_string($xml->query_name);
    $server_available     = mysql_real_escape_string($xml->available);
    $server_style         = mysql_real_escape_string($xml->style);
    $server_log_file      = mysql_real_escape_string($xml->log_file);
    $server_port          = mysql_real_escape_string($xml->port);
    $server_res_ports     = mysql_real_escape_string($xml->reserved_ports);
    $server_tcp_ports     = mysql_real_escape_string($xml->tcp_ports);
    $server_udp_ports     = mysql_real_escape_string($xml->udp_ports);
    $server_executable    = mysql_real_escape_string($xml->executable);
    $server_max_slots     = mysql_real_escape_string($xml->max_slots);
    $server_map           = mysql_real_escape_string($xml->map);
    $server_setup_cmd     = mysql_real_escape_string($xml->setup_cmd);
    $server_cmd_line      = mysql_real_escape_string($xml->cmd_line);
    $server_working_dir   = mysql_real_escape_string($xml->working_dir);
    $server_setup_dir     = mysql_real_escape_string($xml->setup_dir);
    $server_opts          = mysql_real_escape_string($xml->game_opts);
    $server_based_on      = mysql_real_escape_string($xml->based_on);
    $server_notes         = mysql_real_escape_string($xml->notes);
    $server_description   = mysql_real_escape_string($xml->description);
    $server_mod_name      = mysql_real_escape_string($xml->mod_name);
    $server_nickname      = mysql_real_escape_string($xml->nickname);
    $server_config_file   = mysql_real_escape_string($xml->config_file);
    $server_cfg_ip        = mysql_real_escape_string($xml->cfg_ip);
    $server_cfg_port      = mysql_real_escape_string($xml->cfg_port);
    $server_cfg_max_slots = mysql_real_escape_string($xml->cfg_max_slots);
    $server_cfg_map       = mysql_real_escape_string($xml->cfg_map);
    $server_cfg_password  = mysql_real_escape_string($xml->cfg_password);
    $server_cfg_internet  = mysql_real_escape_string($xml->cfg_internet);
    $server_is_steam      = mysql_real_escape_string($xml->is_steam);
    $server_is_punkbuster = mysql_real_escape_string($xml->is_punkbuster);
    $server_pid_file      = mysql_real_escape_string($xml->pid_file);
    
    ####################################################################
    
    // Check server type
    if($server_type != 'game' && $server_type != 'voip' && $server_type != 'other' && $server_type != '')
    {
        die('<center><b>Error:</b> Invalid XML file!  The server type was incorrect. You must use "game","voip", or "other".  Please reformat the document and try again.</center>');
    }
    
    // Make sure required options weren't empty
    if(empty($server_short_name) || empty($server_long_name) || empty($server_executable))
    {
        die('<center><b>Error:</b> Invalid XML file!  A required option was left empty (short name,long name,executable). Please reformat the document and try again.</center>');
    }
    
    //
    // Defaults --
    //
    
    // Default to 'game' type
    if(empty($server_type))
    {
        $server_type = 'game';
    }
    
    // Format 'game available'
    if($server_available == 'yes')
    {
        $server_available = 'Y';
    }
    else
    {
        $server_available = 'N';
    }
    
    
    ####################################################################
    
    // Check if this server type is already setup
    $result_set = @mysql_query("SELECT id FROM cfg WHERE short_name = '$server_short_name' AND type = '$server_type' LIMIT 0,1");
    $row_set    = mysql_fetch_row($result_set);
    $cfg_set    = $row_set[0];
    
    // If set, return the ID of the server
    if($cfg_set)
    {
        return 'existing ' . $cfg_set;
    }
    
    ####################################################################
    
    //
    // Insert into `cfg`
    //
    @mysql_query("INSERT INTO cfg  
                       (date_added,type,short_name,long_name,steam_name,query_name,style,log_file,port,reserved_ports,
                       tcp_ports,udp_ports,executable,max_slots,map,setup_cmd,working_dir,
                       setup_dir,based_on,notes,description,cmd_line,mod_name,nickname,config_file,
                       cfg_ip,cfg_port,cfg_max_slots,cfg_map,cfg_password,cfg_internet,is_steam,is_punkbuster,pid_file) 
                    VALUES(NOW(),'$server_type','$server_short_name','$server_long_name','$server_steam_name','$server_query_name','$server_style','$server_log_file','$server_port','$server_res_ports',
                    '$server_tcp_ports','$server_udp_ports','$server_executable','$server_max_slots','$server_map','$server_setup_cmd','$server_working_dir',
                    '$server_setup_dir','$server_based_on','$server_notes','$server_description','$server_cmd_line','$server_mod_name','$server_nickname','$server_config_file',
                    '$server_cfg_ip','$server_cfg_port','$server_cfg_max_slots','$server_cfg_map','$server_cfg_password','$server_cfg_internet',
                    '$server_is_steam','$server_is_punkbuster','$server_pid_file')") or die('<center><b>Error:</b> <i>addsupportedserver.php</i>: Failed to add the Supported Server!</center>');

    
    // Get the ID of the server just created
    $this_id  = mysql_insert_id();
    
    ####################################################################
    
    // Loop through CMD-Line Options
    foreach ($xml->cmditems->item as $item)
    {
        $item_required      = mysql_real_escape_string($item->required);
        $item_client_edit   = mysql_real_escape_string($item->client_edit);
        $item_simpleid      = mysql_real_escape_string($item->simpleid);
        $item_name          = mysql_real_escape_string($item->name);
        $item_value         = mysql_real_escape_string($item->value);
        $item_description   = mysql_real_escape_string($item->description);
        
        @mysql_query("INSERT INTO cfg_items (srvid,required,client_edit,simpleid,name,default_value,description) VALUES('$this_id','$item_required','$item_client_edit','$item_simpleid','$item_name','$item_value','$item_description')") or die('Failed to insert the cmdline item');
    }
      
    #return $itemz;
    
    /*    
    // Create insert query
    $insert_query = "INSERT INTO cfg_options VALUES('','$this_id',";
    
    
    //
    // Loop through all 10 game options
    //
    for($i=1; $i <= 10; $i++)
    {
        // Option names
        $opt_name   = 'opt' . $i . '_name';
        $opt_value  = 'opt' . $i . '_value';
        $opt_edit   = 'opt' . $i . '_client_edit';
        
        // Current values
        $this_opt_name    = $xml->game_opts->$opt_name;
        $this_opt_value   = $xml->game_opts->$opt_value;
        $this_opt_edit    = $xml->game_opts->$opt_edit;
        
        // Add these options to the insert query
        if($i == 10)
        {
            $insert_query .= "'$this_opt_name','$this_opt_edit','$this_opt_value')";
        }
        else
        {
            $insert_query .= "'$this_opt_name','$this_opt_edit','$this_opt_value',";
        }
    }

    
    // Insert into `cfg_options`
    @mysql_query($insert_query) or die('<b>Error:</b> Failed to insert server into database!');
    */
    
    // Return new server ID
    return 'success ' . $this_id;
}
















/*
//
// GamePanelX Open import
//
function gpx_xml_import_gpxopen($xml_filename)
{
    // Check empty
    if(empty($xml_filename))
    {
        return false;
    }
    
    ####################################################################
    
    // Load XML file
    $xml = simplexml_load_file($xml_filename);
    
    // System-Specific variables
    $server_type          = $xml->type;
    $server_short_name    = $xml->short_name;
    $server_long_name     = $xml->long_name;
    $server_available     = $xml->available;
    $server_style         = $xml->style;
    $server_log_file      = $xml->log_file;
    $server_port          = $xml->port;
    $server_res_ports     = $xml->reserved_ports;
    $server_tcp_ports     = $xml->tcp_ports;
    $server_udp_ports     = $xml->udp_ports;
    $server_executable    = $xml->executable;
    $server_max_slots     = $xml->max_slots;
    $server_map           = $xml->map;
    $server_setup_cmd     = $xml->setup_cmd;
    $server_cmd_line      = $xml->cmd_line;
    $server_working_dir   = $xml->working_dir;
    $server_setup_dir     = $xml->setup_dir;
    $server_opts          = $xml->game_opts;
    
    
    ####################################################################
    
    // Check server type
    if($server_type != 'game' && $server_type != 'voip' && $server_type != 'other' && $server_type != '')
    {
        die('<center><b>Error:</b> Invalid XML file!  The server type was incorrect. You must use "game","voip", or "other".  Please reformat the document and try again.</center>');
    }
    
    // Make sure required options weren't empty
    if(empty($server_short_name) || empty($server_long_name) || empty($server_executable) || empty($server_cmd_line))
    {
        die('<center><b>Error:</b> Invalid XML file!  A required option was left empty (short name,long name,executable,cmd line). Please reformat the document and try again.</center>');
    }
    
    //
    // Defaults --
    //
    
    // Default to 'game' type
    if(empty($server_type))
    {
        $server_type = 'game';
    }
    
    // Default to 'available'
    if(empty($server_available))
    {
        $server_available = 'Y';
    }
    
    // Format 'game available'
    if($server_available == 'yes')
    {
        $server_available = 'Y';
    }
    else
    {
        $server_available = 'N';
    }
    
    ####################################################################
    
    //
    // Insert into `cfg`
    //
    $insert_supp  = "INSERT INTO cfg (date_added,type,short_name,long_name,available,style,log_file,port,reserved_ports,tcp_ports,udp_ports,executable,max_slots,map,setup_cmd,cmd_line,working_dir,setup_dir) VALUES(NOW(),'$server_type','$server_short_name','$server_long_name','$server_available','$server_style','$server_log_file','$server_port','$server_res_ports','$server_tcp_ports','$server_udp_ports','$server_executable','$server_max_slots','$server_map','$server_setup_cmd','$server_cmd_line','$server_working_dir','$server_setup_dir')";

    @mysql_query($insert_supp) or die('<center><b>Error:</b> <i>addsupportedserver.php</i>: Failed to add the Supported Server!</center>');

    
    // Get the ID of the server just created
    $result_getid = @mysql_query("SELECT id FROM cfg WHERE short_name = '$server_short_name' AND long_name = '$server_long_name' ORDER BY id DESC LIMIT 0,1");
    
    while($row_getid = mysql_fetch_array($result_getid))
    {
        $this_id = $row_getid['id'];
    }
    
    ####################################################################
    
    
    // Create insert query
    $insert_query = "INSERT INTO cfg_options VALUES('','$this_id',";
    
    
    //
    // Loop through all 10 game options
    //
    for($i=1; $i <= 10; $i++)
    {
        // Option names
        $opt_name   = 'opt' . $i . '_name';
        $opt_value  = 'opt' . $i . '_value';
        $opt_edit   = 'opt' . $i . '_client_edit';
        
        // Current values
        $this_opt_name    = $xml->game_opts->$opt_name;
        $this_opt_value   = $xml->game_opts->$opt_value;
        $this_opt_edit    = $xml->game_opts->$opt_edit;
        
        // Add these options to the insert query
        if($i == 10)
        {
            $insert_query .= "'$this_opt_name','$this_opt_edit','$this_opt_value')";
        }
        else
        {
            $insert_query .= "'$this_opt_name','$this_opt_edit','$this_opt_value',";
        }
    }

    
    // Insert into `cfg_options`
    @mysql_query($insert_query) or die('<b>Error:</b> Failed to insert server into database!');
    
    
    return true;
}

*/










/*









//
// TCAdmin import
//
function gpx_xml_import_tcadmin($xml_filename)
{
    // Check empty
    if(empty($xml_filename))
    {
        return false;
    }
    
    ####################################################################
    
    // Load XML file
    $xml = simplexml_load_file($xml_filename);
    
    // System-Specific variables
    $server_long_name     = $xml->NAME;
    $server_short_name    = $xml->SHORTNAME;
    $server_port          = $xml->DEFAULTPORT;
    $server_executable    = $xml->RELATIVEEXECUTABLE;
    $server_cmd_line      = $xml->DEFAULTCMDLINE;
    $server_user_cmd_line = $xml->CMDBUILDERUSERACCESS;
    
    // Game or Voip server
    $server_is_voice_srv  = $xml->IS_VOICE_SERVER;
    
    if($server_is_voice_srv == 'True' || $server_is_voice_srv == 'true')
    {
        $server_type = 'voip';
    }
    else
    {
        $server_type = 'game';
    }
    
    // CMD-Line Parameters
    $cmd_line_params      = $xml->COMMANDLINEPARAMETERS;
    $single_param         = $cmd_line_params->COMMANDLINEPARAMETER;
    $this_cmd             = $single_param->COMMAND;
    
    
    $server_available     = 'Y';

    ####################################################################
    
    // Check server type
    if($server_type != 'game' && $server_type != 'voip' && $server_type != 'other' && $server_type != '')
    {
        die('<center><b>Error:</b> Invalid XML file!  The server type was incorrect. You must use "game","voip", or "other".  Please reformat the document and try again.</center>');
    }
    
    // Make sure required options weren't empty
    if(empty($server_short_name) || empty($server_long_name) || empty($server_executable) || empty($server_cmd_line))
    {
        die('<center><b>Error:</b> Invalid XML file!  A required option was left empty (short name,long name,executable,cmd line). Please reformat the document and try again.</center>');
    }
    
    ####################################################################
    
    // Insert into `cfg`
    $insert_supp_srv = "INSERT INTO cfg (available,type,based_on,long_name,short_name,description,executable,map,style,log_file,setup_cmd,working_dir,setup_dir,cmd_line,port,date_added,reserved_ports,tcp_ports,udp_ports,cfg_ip,cfg_port,cfg_max_slots,cfg_map,notes) VALUES('$post_available','$server_type','$post_based_on','$post_long_name','$post_short_name','$post_mod_name','$post_nickname','$post_description','$post_executable','$post_map','$post_style','$post_log_file','$post_setup_cmd','$post_working_dir','$post_setup_dir','$post_config_file','$post_cmd_line','$post_port',NOW(),'$post_reserved_ports','$post_tcp_ports','$post_udp_ports','$post_cfg_ip','$post_cfg_port','$post_cfg_max_slots','$post_cfg_map','$post_notes')";
    @mysql_query($insert_supp_srv) or die('<center><b>Error:</b> <i>addsupportedserver.php</i>: Failed to add the Supported Server!</center>');


    return true;
}
*/















/*

//
// OpenGamePanel (OGP) import
//
function gpx_xml_import_ogp($xml_filename)
{
    // Check empty
    if(empty($xml_filename))
    {
        return false;
    }
    
    
     * <game_config>
        <game_key>cssource</game_key>
        <query_name>cssource</query_name>
        <game_name>Counter-Strike Source</game_name>
        <server_exec_name>srcds_run</server_exec_name>
        <cli_template>%GAME_TYPE% %PID_FILE% %MAP% %IP% %PORT% %PLAYERS%</cli_template>
        <cli_params>
          <cli_param cli_id="PID_FILE" cli_string="-pidfile" options="s" />
          <cli_param cli_id="MAP" cli_string="+map" options="s" />
          <cli_param cli_id="IP" cli_string="+ip" options="s" />
          <cli_param cli_id="PORT" cli_string="+port" options="s" />
          <cli_param cli_id="PLAYERS" cli_string="+maxplayers" options="s" />
          <cli_param cli_id="GAME_TYPE" cli_string="-game" options="s" />
        </cli_params>
        <maps_location>Maps</maps_location>
        <exe_location>css</exe_location>
        <map_list>cstrike/mapcycle.txt</map_list>
        <max_player_amount>64</max_player_amount>
        <mods>
          <mod mod_key="cstrike">cstrike</mod>
          <mod mod_key=""></mod>
          <mod mod_key=""></mod>
          <mod mod_key=""></mod>
        </mods>
        <game_params>
          <param key="-restart"></param>
        </game_params>
      </game_config>
    
    ####################################################################
    
    // Load XML file
    $xml = simplexml_load_file($xml_filename);
    
    // System-Specific variables
    $server_type          = $xml->type;
    $server_short_name    = $xml->short_name;
    $server_long_name     = $xml->long_name;
    $server_available     = $xml->available;
    $server_style         = $xml->style;
    $server_log_file      = $xml->log_file;
    $server_port          = $xml->port;
    $server_res_ports     = $xml->reserved_ports;
    $udp_game_ports       = $xml->tcp_ports;
    $tcp_game_ports       = $xml->udp_ports;
    $server_executable    = $xml->executable;
    $server_max_slots     = $xml->max_slots;
    $server_map           = $xml->map;
    $server_setup_cmd     = $xml->setup_cmd;
    $server_cmd_line      = $xml->cmd_line;
    $server_working_dir   = $xml->working_dir;
    $server_setup_dir     = $xml->setup_dir;
    $server_opts          = $xml->game_opts;
    
    ####################################################################
    
    // Check server type
    if($server_type != 'game' && $server_type != 'voip' && $server_type != 'other' && $server_type != '')
    {
        die('<center><b>Error:</b> Invalid XML file!  The server type was incorrect. You must use "game","voip", or "other".  Please reformat the document and try again.</center>');
    }
    
    // Make sure required options weren't empty
    if(empty($server_short_name) || empty($server_long_name) || empty($server_executable) || empty($server_cmd_line))
    {
        die('<center><b>Error:</b> Invalid XML file!  A required option was left empty (short name,long name,executable,cmd line). Please reformat the document and try again.</center>');
    }
    
    ####################################################################
    
    // Insert into `cfg`
    $insert_supp_srv = "INSERT INTO cfg (available,type,based_on,long_name,short_name,description,executable,map,style,log_file,setup_cmd,working_dir,setup_dir,cmd_line,port,date_added,reserved_ports,tcp_ports,udp_ports,cfg_ip,cfg_port,cfg_max_slots,cfg_map,notes) VALUES('$post_available','$server_type','$post_based_on','$post_long_name','$post_short_name','$post_mod_name','$post_nickname','$post_description','$post_executable','$post_map','$post_style','$post_log_file','$post_setup_cmd','$post_working_dir','$post_setup_dir','$post_config_file','$post_cmd_line','$post_port',NOW(),'$post_reserved_ports','$post_tcp_ports','$post_udp_ports','$post_cfg_ip','$post_cfg_port','$post_cfg_max_slots','$post_cfg_map','$post_notes')";
    @mysql_query($insert_supp_srv) or die('<center><b>Error:</b> <i>addsupportedserver.php</i>: Failed to add the Supported Server!</center>');


    return true;
}
*/

?>
