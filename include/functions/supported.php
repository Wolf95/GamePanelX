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
 *                     Supported Server functions
 *
 *********************************************************************** 
*/

//
// Delete a Supported Server
//
function gpx_supported_delete($id)
{
    $safe_id  = mysql_real_escape_string($id);
    
    // Delete this Supported Server in all related tables
    @mysql_query("DELETE FROM cfg WHERE id = '$safe_id'");
    @mysql_query("DELETE FROM cfg_addons WHERE srvid = '$safe_id'");
    @mysql_query("DELETE FROM cfg_options WHERE srvid = '$safe_id'");
    @mysql_query("DELETE FROM cfg_rcon WHERE srvid = '$safe_id'");


    return true;
}





//
// Install a Supported Server
//
function gpx_supported_install($networkid,$cfgid,$filename,$description,$is_default)
{
    // Safeify
    $safe_id        = mysql_real_escape_string($cfgid);
    $safe_networkid = mysql_real_escape_string($networkid);
    $install_cmd    = "";
    $description    = mysql_real_escape_string($description);
    $is_default     = mysql_real_escape_string($is_default);
    
    ####################################################################
    
    // Get server install info
    $result_info = @mysql_query("SELECT is_steam,short_name,steam_name,type,setup_dir,is_punkbuster,setup_cmd FROM cfg WHERE id = '$safe_id'");
    
    while($row_info = mysql_fetch_array($result_info))
    {
        $cfg_is_steam       = $row_info['is_steam'];
        $cfg_short_name     = $row_info['short_name'];
        $cfg_steam_name     = $row_info['steam_name'];
        $cfg_type           = $row_info['type'];
        $cfg_setup_dir      = $row_info['setup_dir'];
        $cfg_is_punkbuster  = $row_info['is_punkbuster'];
        $cfg_setup_cmd      = $row_info['setup_cmd'];
    }

    ####################################################################
    
    //
    // User-set 'setup_cmd' overrides any punkbuster/steam install commands
    //
    if(!empty($cfg_setup_cmd))
    {
        $install_cmd = $cfg_setup_cmd;
    }
    
    //
    // Use default install methods
    //
    else
    {
        //
        // Steam-based
        //
        /*
        if($cfg_is_steam == 'Y')
        {
            
            require(GPX_DOCROOT . '/include/automagical/steam.php');
            
            $steam_filename     = $setup['filename'];
            $steam_checksum     = $setup['checksum'];
            $install_cmd        = $setup['cmd'];
            
            $filename = $steam_filename;
        }
        */
        
        ################################################################
        
        //
        // Punkbuster
        //
        if($cfg_is_punkbuster == 'Y')
        {
            require(GPX_DOCROOT . '/include/automagical/punkbuster.php');
            
            $pb_filename        = $setup['filename'];
            $pb_checksum        = $setup['checksum'];
            $install_cmd       .= 'echo "" ; ' . $setup['cmd'];
        }
    }
    
    // Insert values
    
    $tpl_install_status   = 'running';
    $tpl_template_status  = 'running';
    
    // Update other default servers
    if($is_default)
    {
        @mysql_query("UPDATE archives SET is_default = '0' WHERE networkid = '$safe_networkid' AND cfgid = '$safe_id'");
    }
    
    // Create archive
    @mysql_query("INSERT INTO archives 
                    (supported,networkid,cfgid,date_created,is_default,status,installation_status,description) 
                  VALUES('1','$safe_networkid','$safe_id',NOW(),'$is_default','$tpl_template_status','$tpl_install_status','$description')") or die('Failed to insert the archive');
    
    $this_template_id = mysql_insert_id();
    $tpl_file_path    = '~/tmp/' . $this_template_id . '/';
    
    // Add file path with archive id
    @mysql_query("UPDATE archives SET file_path = '$tpl_file_path' WHERE id = '$this_template_id'");
    
    ####################################################################
    
    // Convert any % variables into usable stuff
    $install_cmd  = str_replace('%steam_name%', $cfg_steam_name, $install_cmd);
    $install_cmd  = str_replace('%setup_dir%', $cfg_setup_dir, $install_cmd);
    $install_cmd  = str_replace('%tmp_dir%', $tpl_file_path, $install_cmd);
    
    
    // Escape double-quotes
    $install_cmd  = str_replace("'", "\'", $install_cmd);
    
    //
    // Install on Remote Server
    //
    require(GPX_DOCROOT . '/include/functions/remote.php');
    
    
    // Steam Installer
    if($cfg_is_steam == 'Y')
    {
        $result_install = gpx_remote_supported_install($this_template_id,$safe_networkid,'','',$cfg_steam_name);
    }
    // All other installs
    else
    {
        $result_install = gpx_remote_supported_install($this_template_id,$safe_networkid,$filename,$install_cmd,'');
    }   
    
    
    if($result_install == 'success')
    {
        return 'success';
    }
    else
    {
        // Update the template as failed
        @mysql_query("UPDATE archives SET installation_status = 'failed' WHERE id = '$this_template_id'");
        
        return $result_install;
    }
}











//
// Check Supported Server Install Status
//
function gpx_status_install_supported_server($templateid)
{
    // Escape all given values
    $safe_id  = mysql_real_escape_string($templateid);
    
    ####################################################################
    
    // Get template info
    $query_tpl  = "SELECT 
                      cfg.size
                    FROM cfg 
                    LEFT JOIN templates ON 
                      cfg.short_name = templates.server 
                    WHERE templates.id = '$safe_id'";
    $result_tpl = @mysql_query($query_tpl);
    
    while($row_tpl = mysql_fetch_array($result_tpl))
    {
        $cfg_installed_size = $row_tpl['size'];
    }
    
    ####################################################################
    
    // Get Server Creation status
    require(GPX_DOCROOT . '/include/functions/remote.php');
    
    $creation_status = @gpx_remote_status_install_supported_server($safe_id);
    
    // If process is complete
    if(trim($creation_status) == 'complete')
    {
        // Update 'installation_status' field
        if(!mysql_query("UPDATE archives SET installation_status = 'complete' WHERE id = '$safe_id'"))
        {
            return 'FAILURE: Failed to update the server status in the database';
        }
        
        return 'complete';
    }
    // Still running, with percentage
    elseif(preg_match("/running\,/", $creation_status))
    {
        // Get the percentage
        $arr_percent = explode(',', $creation_status);
        
        // Divide the current percent by the fully installed size (in bytes)
        $pre_percentage = $arr_percent[1] / $cfg_installed_size * 100;
        
        // Lose the decimal
        $arr_per = explode('.', $pre_percentage);
        $install_percentage = $arr_per[0];
        
        
        // Return the percentage
        return $install_percentage;
    }
    else
    {
        return $creation_status;
    }
}

?>
