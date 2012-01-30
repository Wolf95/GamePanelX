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
 *                     Network / IP Address functions
 *
 *********************************************************************** 
*/

//
// Add Full Network Server
//
function gpx_network_add_server($ip,$description,$available,$physical,$os,$location,$datacenter,$conn_user,$conn_pass,$conn_port)
{
    require(GPX_DOCROOT . '/include/db.php');

    // Encryption Key
    $enc_key = $config['encrypt_key'];

    // Escape all given values.
    $safe_ip            = mysql_real_escape_string($ip);
    $safe_description   = mysql_real_escape_string($description);
    $safe_available     = mysql_real_escape_string($available);
    $safe_physical      = mysql_real_escape_string($physical);
    $safe_os            = mysql_real_escape_string($os);
    $safe_location      = mysql_real_escape_string($location);
    $safe_datacenter    = mysql_real_escape_string($datacenter);
    $safe_conn_method   = mysql_real_escape_string($conn_method);
    $safe_conn_user     = mysql_real_escape_string($conn_user);
    $safe_conn_pass     = mysql_real_escape_string($conn_pass);
    $safe_conn_port     = mysql_real_escape_string($conn_port);
    
    
    // Make sure this IP Address doesn't exist
    $result_exist = @mysql_query("SELECT COUNT(id) AS thecount FROM network WHERE ip = '$safe_ip'") or die('<center><b>Error:</b> <i>network.php</i>: Failed to check if the IP Address exists!</center>');
    while($row_exist = mysql_fetch_array($result_exist)) { $count_ips = $row_exist['thecount']; }
    
    if($count_ips >= 1)
    {
        die('<center><b>Error:</b> <i>network.php:</i> This IP Address already exists!</center>');
    }
    
    
    //
    // Insert network server
    //
    @mysql_query("INSERT INTO network (date_added,ip,description,available,physical,os,location,datacenter,conn_user,conn_pass,conn_port) VALUES(NOW(),'$safe_ip','$safe_description','$safe_available','$safe_physical','$safe_os','$safe_location','$safe_datacenter',AES_ENCRYPT('$safe_conn_user', '$enc_key'),AES_ENCRYPT('$safe_conn_pass', '$enc_key'),AES_ENCRYPT('$safe_conn_port', '$enc_key'))");
    
    ####################################################################
    
    // Get the network ID just created
    $result_id  = @mysql_query("SELECT id FROM network WHERE ip = '$safe_ip' AND description = '$safe_description' AND physical = '$safe_physical' AND os = '$safe_os' ORDER BY id DESC LIMIT 0,1");
    
    while($row_id = mysql_fetch_array($result_id))
    {
        $this_networkid = $row_id['id'];
    }

    // Update parentid to itself (for FTP server's query to work properly)
    @mysql_query("UPDATE network SET parentid = '$this_networkid' WHERE id = '$this_networkid'");
    
    ####################################################################
    
    //
    // Store remote home directory for FTP use, etc
    //
    require(GPX_DOCROOT . '/include/functions/remote.php');
    $remote_home  = gpx_remote_get_home($this_networkid);

    if(empty($remote_home))
    {
        // First, delete the networkid we just created
        @mysql_query("DELETE FROM network WHERE id = '$this_networkid'");
        
        // Kill the page
        die('<center><b>Error:</b> Failed to get the remote home directory!  Check the connection details and try again.</center>');
    }
    else
    {
        // Strip newline, add /accounts
        $remote_home = trim($remote_home) . '/accounts/';
        
        // Update the network server with the new ID
        @mysql_query("UPDATE network SET accounts_dir = '$remote_home' WHERE id = '$this_networkid'");
        return true;
    }
}



########################################################################



//
// Add IP Address
//
function gpx_network_add_ip($ip,$available,$parentid)
{
    // Escape all given values.
    $safe_ip            = mysql_real_escape_string($ip);
    $safe_available     = mysql_real_escape_string($available);
    $safe_parentid      = mysql_real_escape_string($parentid);

    
    // Make sure this IP Address doesn't exist
    $result_exist = @mysql_query("SELECT COUNT(id) AS thecount FROM network WHERE ip='$safe_ip'") or die('<center><b>Error:</b> <i>network.php</i>: Failed to check if the IP Address exists!</center>');
    while($row_exist = mysql_fetch_array($result_exist)) { $count_ips = $row_exist['thecount']; }
    
    if($count_ips >= 1)
    {
        die('<center><b>Error:</b> <i>network.php:</i> This IP Address already exists!</center>');
    }
    
    
    //
    // Insert Parent Server
    //
    if(mysql_query("INSERT INTO network (date_added,ip,available,physical,parentid) VALUES(NOW(),'$safe_ip','$safe_available','N','$safe_parentid')"))
    {
        return true;
    }
    else
    {
        return false;
    }
}



########################################################################



//
// Update IP Address
//
function gpx_network_update($id,$ip,$description,$available,$physical,$location,$datacenter,$os_flavor,$conn_user,$conn_pass,$conn_port)
{
    require(GPX_DOCROOT . '/include/db.php');

    // Encryption Key
    $enc_key = $config['encrypt_key'];

    // Escape all given values
    $safe_id            = mysql_real_escape_string($id);
    $safe_ip            = mysql_real_escape_string($ip);
    $safe_description   = mysql_real_escape_string($description);
    $safe_available     = mysql_real_escape_string($available);
    $safe_physical      = mysql_real_escape_string($physical);
    $safe_parent        = mysql_real_escape_string($parent);
    $safe_location      = mysql_real_escape_string($location);
    $safe_datacenter    = mysql_real_escape_string($datacenter);
    $safe_os_flavor     = mysql_real_escape_string($os_flavor);
    $safe_conn_user     = mysql_real_escape_string($conn_user);
    $safe_conn_pass     = mysql_real_escape_string($conn_pass);
    $safe_conn_port     = mysql_real_escape_string($conn_port);


    // Make sure this IP Address doesn't exist
    $result_exist = @mysql_query("SELECT COUNT(id) AS thecount FROM network WHERE ip='$safe_ip' AND id != '$safe_id'") or die('<center><b>Error:</b> <i>network.php</i>: Failed to check if the IP Address exists!</center>');
    while($row_exist = mysql_fetch_array($result_exist)) { $count_ips = $row_exist['thecount']; }
    
    if($count_ips >= 1)
    {
        die('<center><b>Error:</b> <i>network.php:</i> This IP Address already exists!</center>');
    }


    //
    // Parent Server
    //
    if($safe_physical == 'Y')
    {
        $phys_query = "UPDATE network SET 
                         ip='$safe_ip',
                         description='$safe_description',
                         available='$safe_available',
                         physical='Y',
                         parentid='$safe_id',
                         location='$safe_location',
                         datacenter='$safe_datacenter',
                         linux_flavor='$safe_os_flavor',
                         conn_user = AES_ENCRYPT('$safe_conn_user', '$enc_key'),
                         conn_pass = AES_ENCRYPT('$safe_conn_pass', '$enc_key'),
                         conn_port = AES_ENCRYPT('$safe_conn_port', '$enc_key') 
                       WHERE id='$safe_id'";
        if(mysql_query($phys_query))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
    
    //
    // Regular IP Address
    //
    else
    {
        if(mysql_query("UPDATE network SET ip='$safe_ip',available='$safe_available',physical='N' WHERE id='$safe_id'"))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

}



########################################################################



//
// Delete Network Server
//
function gpx_network_delete_server($id)
{
    // Escape all given values.
    $safe_id = mysql_real_escape_string($id);
    
    // Check empty
    if(empty($safe_id))
    {
        die('<center><b>Error:</b> <i>network.php:</i> No ID given!</center>');
    }

    // Delete this IP Address
    if(mysql_query("DELETE FROM network WHERE id = '$safe_id'"))
    {
        return true;
    }
    else
    {
        return false;
    }
}



########################################################################



//
// Get ID of a newly created server from an IP Address
//
function gpx_network_get_id($ip_address)
{
    // Escape all given values.
    $safe_ip = mysql_real_escape_string($ip_address);
    
    // Check empty
    if(empty($safe_ip))
    {
        die('<center><b>Error:</b> <i>network.php:</i> No IP Address given!</center>');
    }

    $result_ip = @mysql_query("SELECT id FROM network WHERE ip = '$safe_ip' ORDER BY id DESC LIMIT 0,1");

    while($row_ip = mysql_fetch_array($result_ip))
    {
        $this_id = $row_ip['id'];
    }
    
    return $this_id;
}
?>
