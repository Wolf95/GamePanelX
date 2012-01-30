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
 *       Check status of various things on Game/Voice Servers
 *
 *********************************************************************** 
*/


//
// Remote server creation status
// (Get remote Server Creation status if DB shows it as still running)
//
function gpx_getstatus_create_server($serverid)
{
    // Safe-ify
    $serverid = mysql_real_escape_string($serverid);
    
    ####################################################################
    
    // Get database creation status
    $result_create = @mysql_query("SELECT creation_status FROM servers WHERE id = '$serverid'");
    
    while($row_create = mysql_fetch_array($result_create))
    {
        $creation_status = $row_create['creation_status'];
    }
  
    

    // Server is creating
    if($creation_status == 'running')
    {
        // SSH into the Remote Server and check status
        require_once(GPX_DOCROOT . '/include/functions/servers.php');
        $creation_status = @gpx_status_create_server($serverid);
        
        if(empty($creation_status))
        {
            $creation_status = 'unknown';
        }
        // Check for failure
        elseif(preg_match("/FAILURE\:/", $creation_status))
        {
            $creation_status = 'unknown';
        }
    }
    
    
    // Return server creation status
    return $creation_status;
}









//
// Check the status of a Game/Voice server update
//
function gpx_getstatus_update_server($serverid)
{
    // Safe-ify
    $serverid = mysql_real_escape_string($serverid);
    
    ####################################################################
    
    // Get database update status
    $result_update = @mysql_query("SELECT update_status FROM servers WHERE id = '$serverid'");
    
    while($row_update = mysql_fetch_array($result_update))
    {
        $update_status = $row_update['update_status'];
    }
    
    
    // Update is running
    if($update_status == 'running')
    {
        // SSH into the Remote Server and check status
        require_once(GPX_DOCROOT . '/include/functions/servers.php');
        $update_status = @gpx_status_update_server($serverid);
        
        if(empty($update_status))
        {
            $update_status  = 'unknown';
        }
        // Check for failure
        elseif(preg_match("/FAILURE\:/", $update_status))
        {
            $update_status  = 'unknown';
        }
    }
    
    
    // Return server creation status
    return $update_status;
}
