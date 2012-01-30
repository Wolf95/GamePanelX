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
 *                     Server Addons Functions
 *
 *********************************************************************** 
*/

//
// Install Addon
//
function gpx_addon_install($srvid,$addonid)
{
    // Escape all given values
    $safe_srvid     = mysql_real_escape_string($srvid);
    $safe_addonid   = mysql_real_escape_string($addonid);


    //
    // Insert addon
    //
    
    // Success
    if(!mysql_query("INSERT INTO servers_addons (date_added,is_installed,srvid,addonid) VALUES(NOW(),'Y','$safe_srvid','$safe_addonid')"))
    {
        return "FAILURE: Failed to add the addon to the database";
    }
    
    
    // Install on Remote Server
    require(GPX_DOCROOT . '/include/functions/remote.php');
    if(!$addon_result = gpx_remote_addon_install($safe_srvid,$safe_addonid))
    {
        return $addon_result;
    }
    
    
    // Success
    return true;
}




//
// Remove Addon
//
function gpx_addon_remove($serverid,$addonid)
{
    // Escape all given values
    $safe_id        = mysql_real_escape_string($serverid);
    $safe_addonid   = mysql_real_escape_string($addonid);

    //
    // Delete the Addon
    //
    
    // Delete on the Remote Server
    require(GPX_DOCROOT . '/include/functions/remote.php');
    if(!gpx_remote_addon_remove($safe_id,$safe_addonid))
    {
        die('failed');
    }
    
    
    // Success
    if(!mysql_query("DELETE FROM servers_addons WHERE id='$safe_addonid'"))
    {
        return "FAILURE: Failed to remove the addon from the database";
    }
    
    
    // Success
    return true;
}





########################################################################

//
// Remove Supported Addon
//
function gpx_supported_addon_remove($addonid)
{
    // Escape all given values
    $safe_addonid   = mysql_real_escape_string($addonid);

    //
    // Delete the Addon
    //
    

    // Delete on the Remote Server
    require(GPX_DOCROOT . '/include/functions/remote.php');
    if(!$addon_result = gpx_remote_supported_addon_remove($safe_addonid))
    {
        return $addon_result;
    }
    
    
    // Success
    if(!mysql_query("DELETE FROM cfg_addons WHERE id = '$safe_addonid'"))
    {
        return "FAILURE: Failed to remove the addon from the database";
    }
    
    
    // Success
    return true;
}






//
// Create Supported Addon
//
function gpx_supported_addon_create($serverid,$type,$available,$networkid,$name,$description,$file_path,$target,$notes)
{
    //
    // No ".." on the filename or target
    //
    if(preg_match("/\.\./", $location))
    {
        return "FAILURE: Invalid location given";
        exit;
    }
    elseif(preg_match("/\.\./", $target))
    {
        return "FAILURE: Invalid target given";
        exit;
    }

    ####################################################################
    
    // Insert query
    $insert_addon = "INSERT INTO cfg_addons (
                    srvid,
                    date_added,
                    type,
                    available,
                    networkid,
                    name,
                    description,
                    file_path,
                    target,
                    notes) 
                VALUES(
                    '$serverid',
                    NOW(),
                    '$type',
                    '$available',
                    '$networkid',
                    '$name',
                    '$description',
                    '$file_path',
                    '$target',
                    '$notes')";
    
    // Insert
    if(!mysql_query($insert_addon))
    {
        return "FAILURE: Failed to insert the addon into the database";
    }
    
    ####################################################################
    
    // Get the addon id that was just created
    $result_addonid = @mysql_query("SELECT id FROM cfg_addons WHERE srvid = '$serverid' AND type = '$type' AND networkid = '$networkid' AND name = '$name' AND description = '$description' AND file_path = '$file_path' ORDER BY id DESC LIMIT 0,1");
    
    while($row_addonid = mysql_fetch_array($result_addonid))
    {
        $this_addonid = $row_addonid['id'];
    }
    
    ####################################################################
    
    // Setup on the Remote Server
    require(GPX_DOCROOT . '/include/functions/remote.php');
    if(!$addon_result = gpx_remote_supported_addon_create($this_addonid))
    {
        return $addon_result;
    }
    
    
    // Success
    return true;
}







//
// Check Addon creation Status
//
function gpx_addon_creation_status($id)
{
    // Escape all given values
    $safe_id  = mysql_real_escape_string($id);

    // Get creation status
    require(GPX_DOCROOT . '/include/functions/remote.php');
    
    if(!$creation_status = gpx_remote_status_addon_create($safe_id))
    {
        die('<center><b>Error:</b> <i>addons.php:</i> Failed to check the creation status!</center>');
    }
    else
    {
        // If process is complete
        if(trim($creation_status) == 'complete')
        {
            // Update `status` field with current status
            if(!mysql_query("UPDATE cfg_addons SET status = 'complete' WHERE id = '$safe_id'"))
            {
                die('<center><b>Error:</b> <i>addons.php:</i> Failed to update the addon status!</center>');
            }
            
            return 'complete';
        }
        else
        {
            return $creation_status;
        }
    }
}
?>
