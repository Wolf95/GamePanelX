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

//
// Smarty
//
require '../libs/Smarty.class.php';
$smarty = new Smarty;
$smarty->compile_dir  = '../admin/templates_c/';

// Required Files
require('../include/auth.php');
require('../include/config.php');

// Page Title
$smarty->assign('pagetitle', 'Add Network Server');

########################################################################

// Parent ID from the URL
$url_id     = $_GET['id'];

// Check malformed ID
if(!empty($url_id) && !is_numeric($url_id))
{
    die('<center><b>Error:</b> Invalid Parent ID given!</center>');
}


########################################################################

// Set user's language
require('../include/functions/language.php');
$lang = gpx_language_get();
$smarty->assign('lang', $lang);

########################################################################


if(!isset($_POST['create']))
{
    // Get list of available languages
    require('languages.php');

    // Assign physical value to Smarty
    if(empty($url_id))
    {
        $smarty->assign('is_physical', 1);
    }
    else
    {
        $smarty->assign('is_physical', 0);
    }
    
    // Add parent
    $smarty->assign('parentid', $url_id);
    
    // Display HTML Page
    $smarty->display($config['Template'] . '/addnetworkserver.tpl'); 
}

########################################################################


elseif(isset($_POST['create']))
{
    // Connect to the database
    $db = @mysql_connect($config['sql_host'],$config['sql_user'],$config['sql_pass']) or die('<center><b>Error:</b> <i>addnetworkserver.php</i>: Failed to connect to the database!</center>');
    @mysql_select_db($config['sql_db']) or die('<center><b>Error:</b> <i>addnetworkserver.php</i>: Failed to select the database!</center>');

    // POST Values
    $post_available   = $_POST['available'];
    $post_description = $_POST['description'];
    $post_ip          = $_POST['ip'];
    $post_os          = $_POST['os'];
    $post_location    = $_POST['location'];
    $post_datacenter  = $_POST['datacenter'];
    
    // Connection settings
    $post_conn_user   = $_POST['conn_user'];
    $post_conn_pass   = $_POST['conn_pass'];
    $post_conn_port   = $_POST['conn_port'];
    
    // Parent Server (IP Address only)
    $post_parentid    = $_POST['parentid'];
    $post_physical    = $_POST['physical'];

    if($post_physical == 1)
    {
        $is_physical = 'Y';
    }
    else
    {
        $is_physical = 'N';
    }

    //
    // Insert data
    //
    require(GPX_DOCROOT.'/include/functions/network.php');

    //
    // Create Physical Server
    //
    if($is_physical == 'Y')
    {
        $phys_netid = gpx_network_add_server($post_ip,$post_description,$post_available,$is_physical,$post_os,$post_location,$post_datacenter,$post_conn_user,$post_conn_pass,$post_conn_port);
        
        #if(!gpx_network_add_server($post_ip,$post_description,$post_available,$is_physical,$post_os,$post_location,$post_datacenter,$post_conn_user,$post_conn_pass,$post_conn_port))
        
        // Failure
        if(empty($phys_netid) || !is_numeric($phys_netid))
        {
            die('<div align="center"><b>Error:</b> Failed to create the server:<br /><br />' . $phys_netid . '<br /><br /><a href="network.php">Click here to go back</a></div>');
        }
        // Success
        else
        {
            header("Location: editnetworkserver.php?id=$phys_netid&info=updated");
            exit(0);
        }
    }
    
    //
    // Create Single IP Address
    //
    else
    {
        if(!gpx_network_add_ip($post_ip,$post_available,$post_parentid))
        {
            die('<center><b>Error:</b> Failed to create the server!</center>');
        }
        
        header("Location: editnetworkserver.php?id=$url_id&info=updated");
        exit;
    }

    

    /*
    // NEW Physical Server; send to Edit
    if(empty($url_id) && $is_physical == 'Y')
    {
        // Get this ID
        #$this_id = gpx_network_get_id($post_ip);
        
        // Redirect to editnetworkserver.php
        header("Location: editnetworkserver.php?id=$phys_netid&info=updated");
        exit;
    }
    // IP Address; 
    else
    {
        // Redirect to editnetworkserver.php
        header("Location: editnetworkserver.php?id=$url_id&info=updated");
        exit;
    }
    */
}
