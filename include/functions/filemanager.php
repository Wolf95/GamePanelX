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
 *                     File Manager functions
 *
 *********************************************************************** 
*/

//
// List contents of a directory
//
function gpx_file_list($server_id,$server_dir)
{
    require(GPX_DOCROOT . '/include/functions/remote.php');
    
    // Get remote file list
    $file_list = gpx_remote_file_list($server_id,$server_dir);
    
    return $file_list;
}


// Same as above, but specific for Network Servers, listing a home dir and not a gameserver root
function gpx_file_list_net($network_id,$server_dir)
{
    require(GPX_DOCROOT . '/include/functions/remote.php');
    
    // Get remote file list
    $file_list = gpx_remote_file_list_net($network_id,$server_dir);
    
    return $file_list;
}





//
// Create a Directory
//
function gpx_file_create_dir($server_id,$server_dir,$dir_name)
{
    require_once(GPX_DOCROOT . '/include/functions/remote.php');
    
    $result_create  = gpx_remote_file_create_dir($server_id,$server_dir,$dir_name);
    
    if($result_create == 'success')
    {
        return 'success';
    }
    else
    {
        return $result_create;
    }
    
    /*
    // Success
    if(gpx_remote_file_create_dir($server_id,$server_dir,$dir_name))
    {
        return true;
    }
    // Failure
    else
    {
        return false;
    }
    */
}
