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
 *              Game/Voice Server Template Functions
 *
 *********************************************************************** 
*/



//
// Update Template
//
function gpx_template_update($id,$description,$server,$available,$is_default)
{
    // Escape all given values
    $safe_id            = mysql_real_escape_string($id);
    $safe_description   = mysql_real_escape_string($description);
    $safe_server        = mysql_real_escape_string($server);
    $safe_available     = mysql_real_escape_string($available);
    $safe_is_default    = mysql_real_escape_string($is_default);
    
    // Get the Network ID for this template
    $result_netid = @mysql_query("SELECT networkid FROM templates WHERE id = '$safe_id'") or die('<center><b>Error:</b> <i>templates.php:</i> Failed to get the Network ID for this template!</center>');
    
    while($row_netid = mysql_fetch_array($result_netid))
    {
        $template_networkid = $row_netid['networkid'];
    }
    
    // Make sure this is the only default template
    if($safe_is_default == 'Y')
    {
        @mysql_query("UPDATE templates SET is_default = 'N' WHERE networkid = '$template_networkid' AND server = '$safe_server'") or die('<center><b>Error:</b> <i>templates.php:</i> Failed to unset default!</center>');
    }
    
    ####################################################################
    
    // Update query
    $update_query = "UPDATE templates SET server = '$safe_server',available = '$safe_available',is_default = '$safe_is_default',description  = '$safe_description' WHERE id = '$safe_id'";


    // Run the update
    if(!mysql_query($update_query))
    {
        return false;
    }
    else
    {
        // Finish
        return true;
    }
}



########################################################################



//
// Create Template
//
function gpx_template_create($networkid,$cfgid,$is_default,$description,$file_path)
{
    // Escape all given values
    $safe_networkid     = mysql_real_escape_string($networkid);
    $safe_cfgid         = mysql_real_escape_string($cfgid);
    $safe_description   = mysql_real_escape_string($description);
    $safe_file_path     = mysql_real_escape_string($file_path);
    $cur_status         = 'running';
    
    // Make sure this is the only default template if necessary
    if($is_default == 'Y' || $is_default == 1)
    {
        @mysql_query("UPDATE archives SET is_default = '0' WHERE networkid = '$safe_networkid' AND cfgid = '$safe_cfgid'") or die('Failed to unset default archives');
        $is_default = '1';
    }
    else
    {
        $is_default  = '0';
    }
    
    
    // Create the Archive
    @mysql_query("INSERT INTO archives (networkid,cfgid,date_created,is_default,status,description,file_path) VALUES('$safe_networkid','$safe_cfgid',NOW(),'$is_default','$cur_status','$safe_description','$safe_file_path')") or die('Failed to insert the template');
    
    // New archive ID
    $template_id  = mysql_insert_id();
    
    ####################################################################
    
    // Create the archive on the Remote Server
    require(GPX_DOCROOT . '/include/functions/remote.php');
    
    $result_remote  = gpx_remote_create_template($template_id);
    
    // Output
    if($result_remote == 'success')
    {
        // Send notification, 
        require(GPX_DOCROOT . '/include/functions/notifications.php');
        gpx_notify_add(3,$template_id);
        
        return 'success';
    }
    else
    {
        return $result_remote;
    }
    
    /*
    // Failure
    if($result_remote != 'success'
    {
        return 'Failed to create the template on the Remote Server';
    }
    // Success
    else
    {
        // Send notification, 
        require(GPX_DOCROOT . '/include/functions/notifications.php');
        gpx_notify_add(3,$template_id);
        
        return 'success';
    }
    */
}


/*
function gpx_template_create($networkid,$type,$available,$is_default,$server,$description,$file_path)
{
    // Escape all given values
    $safe_networkid     = mysql_real_escape_string($networkid);
    $safe_type          = mysql_real_escape_string($type);
    $safe_available     = mysql_real_escape_string($available);
    $safe_is_default    = mysql_real_escape_string($is_default);
    $safe_server        = mysql_real_escape_string($server);
    $safe_description   = mysql_real_escape_string($description);
    $safe_file_path     = mysql_real_escape_string($file_path);
    $cur_status         = 'running';
    
    // Make sure this is the only default template if necessary
    if($safe_is_default == 'Y')
    {
        @mysql_query("UPDATE templates SET is_default = 'N' WHERE networkid = '$safe_networkid' AND server = '$safe_server'") or die('<center><b>Error:</b> <i>templates.php:</i> Failed to unset default!</center>');
    }
    
    // Insert query
    $insert_query = "INSERT INTO templates (networkid,date_created,type,available,is_default,status,server,description,file_path) VALUES('$safe_networkid',NOW(),'$safe_type','$safe_available','$safe_is_default','$cur_status','$safe_server','$safe_description','$safe_file_path')";
    @mysql_query($insert_query) or die('<center><b>Error:</b> <i>templates.php:</i> Failed to create the template!</center>');
    
    ####################################################################
    
    // Get the ID of the template just created
    $result_tplid = @mysql_query("SELECT id FROM templates WHERE networkid = '$safe_networkid' AND available = '$safe_available' AND server = '$safe_server' AND description = '$safe_description' ORDER BY id DESC LIMIT 0,1") or die('<center><b>Error:</b> <i>templates.php:</i> Failed to get the template ID!</center>');
    
    while($row_tplid = mysql_fetch_array($result_tplid))
    {
        $template_id = $row_tplid['id'];
    }
    
    ####################################################################
    
    // Create the template on the Remote Server
    require(GPX_DOCROOT . '/include/functions/remote.php');
    
    // Failure
    if(!gpx_remote_create_template($template_id))
    {
        return 'Failed to create the template on the Remote Server';
    }
    // Success
    else
    {
        // Send notification, 
        require(GPX_DOCROOT . '/include/functions/notifications.php');
        gpx_notify_add(3,$template_id);
        
        return 'success';
    }
}
*/


########################################################################



//
// Create template (Exiting Automagical templates)
//
function gpx_template_create_am($templateid,$available,$is_default,$description)
{
    // Escape all given values
    $safe_templateid    = mysql_real_escape_string($templateid);
    $safe_available     = mysql_real_escape_string($available);
    $safe_is_default    = mysql_real_escape_string($is_default);
    $safe_description   = mysql_real_escape_string($description);

    ####################################################################
    
    // Get server and networkid for this template
    $result_info = @mysql_query("SELECT networkid,server FROM templates WHERE id = '$safe_templateid'");
    
    while($row_info = mysql_fetch_array($result_info))
    {
        $this_networkid = $row_info['networkid'];
        $this_server    = $row_info['server'];
    }
    
    ####################################################################
    
    // Make sure this is the only default template if necessary
    if($safe_is_default == 'Y')
    {
        @mysql_query("UPDATE templates SET is_default = 'N' WHERE networkid = '$this_networkid' AND server = '$this_server'") or die('<center><b>Error:</b> <i>templates.php:</i> Failed to unset default!</center>');
    }
    
    // Update this template with the new settings
    @mysql_query("UPDATE templates SET automagical = 'Y',status = 'running',available = '$safe_available',is_default = '$safe_is_default',description = '$safe_description' WHERE id = '$safe_templateid'");

    ####################################################################

    // Create the template on the Remote Server
    require(GPX_DOCROOT . '/include/functions/remote.php');
    
    if(!gpx_remote_create_template($safe_templateid))
    {
        die('<center><b>Error:</b> <i>templates.php:</i> Failed to create the template on the Remote Server!</center>');
    }
    else
    {    
        return true;
    }
}



########################################################################




//
// Delete Template
//
function gpx_template_delete($id)
{
    // Escape all given values
    $safe_id  = mysql_real_escape_string($id);


    // Delete on the Remote Server
    require(GPX_DOCROOT . '/include/functions/remote.php');
    $result_rm  = gpx_remote_delete_template($safe_id);
    
    if($result_rm != 'success')
    {
        return 'Failed to delete the template from the Remote Server: ' . $result_rm;
    }
    
    // Delete the template
    @mysql_query("DELETE FROM archives WHERE id = '$safe_id'") or die('Failed to delete from the database');
    
    
    return 'success';
}



########################################################################



//
// Check Template Status
//
function gpx_template_status($id)
{
    // Escape all given values
    $safe_id  = mysql_real_escape_string($id);

    // Get template status
    require(GPX_DOCROOT . '/include/functions/remote.php');
    $template_status = gpx_remote_status_template($safe_id);
    
    // If process is complete
    if(trim($template_status) == 'complete')
    {
        // Update `status` field with current template status
        @mysql_query("UPDATE archives SET status = 'complete' WHERE id = '$safe_id'") or die('Failed to update the template status');
        
        return 'complete';
    }
    else
    {
        return $template_status;
    }
}


?>
