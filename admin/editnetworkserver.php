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
$smarty->assign('pagetitle', 'Edit Network Server');


########################################################################

// Check license variable
if($gpxseckey_T2V1lmkWLli04Z7q3FT != 'F9hJt6up1h80qk9REDD2xyA89TfI185gwtLXJsSMhc61fWv5T33548rLqtW5MWGjkgFl8ISzsoF8491IT2V1lmkWLli04Z7q3FTls169B8PmTx0lRZet777Pr40p7R01FkQFymp1Z629GG5dEW8nI3')
{
    die('Invalid license');
}

########################################################################

// Encryption Key
$enc_key = $config['encrypt_key'];

########################################################################

// ID from the URL
$url_id     = $_GET['id'];

// Check malformed ID
if(empty($url_id) || !is_numeric($url_id))
{
    die('<center><b>Error:</b> Invalid id given!</center>');
}

########################################################################

// Infobox from the URL
$url_info = $_GET['info'];

// Allowed info
$allowed_info = array('updated');

if(!empty($url_info))
{
    if(in_array($url_info, $allowed_info))
    {
        // Update Account
        if($url_info == 'updated')
        {
            $info_msg = 'Server successfully updated!';
            $smarty->assign('infobox', $info_msg);
        }
    }
}

########################################################################

// Set user's language
require('../include/functions/language.php');
$lang = gpx_language_get();
$smarty->assign('lang', $lang);

########################################################################



//
// First Page
//
if(!isset($_POST['update']) && !isset($_POST['delete']))
{
    // Connect to the database
    $db = @mysql_connect($config['sql_host'],$config['sql_user'],$config['sql_pass']) or die('<center><b>Error:</b> <i>editnetworkserver.php</i>: Failed to connect to the database!</center>');
    @mysql_select_db($config['sql_db']) or die('<center><b>Error:</b> <i>editnetworkserver.php</i>: Failed to select the database!</center>');

    
    //
    // Get server info
    //
    $query_info = "SELECT 
                      id,
                      DATE_FORMAT(date_added, '%c/%e/%Y %H:%i') AS date_added,
                      ip,
                      description,
                      available,
                      physical,
                      parentid,
                      os,
                      location,
                      datacenter,
                      linux_flavor,
                      AES_DECRYPT(conn_user, '$enc_key') AS conn_user,
                      AES_DECRYPT(conn_pass, '$enc_key') AS conn_pass,
                      AES_DECRYPT(conn_port, '$enc_key') AS conn_port 
                  FROM network 
                  WHERE id = '$url_id'";
    
    $result = @mysql_query($query_info) or die('<center><b>Error:</b> <i>editnetworkserver.php:</i> Failed to list IP Addresses!</center>');

    // Smarty loop
    while ($line = mysql_fetch_assoc($result))
    {
        $value[] = $line;
    }

    // Smarty mysql loop
    $smarty->assign('server_details', $value);
    
    
    //
    // Get list of IP Addresses that use this as a parent
    // (only if this id is a parent)
    //
    $this_ip = $value[0]['ip'];
    
    $result_parent = @mysql_query("SELECT id,ip,available FROM network WHERE parentid='$url_id' ORDER BY ip ASC") or die('<center><b>Error:</b> <i>editnetworkserver.php</i>: Failed to get ip addresses!</center>');
    
    // Smarty loop
    while ($line_parent = mysql_fetch_assoc($result_parent))
    {
        $value_parent[] = $line_parent;
    }
    
    // Smarty mysql loop
    $smarty->assign('ips', $value_parent);
    
    
    
    
    // Get list of available languages
    require('languages.php');
    
    
    
    // Display HTML Page
    $smarty->display($config['Template'] . '/editnetworkserver.tpl'); 
}









//
// Save Network Details
//
elseif(isset($_POST['update']) && !isset($_POST['delete']))
{
    // Connect to the database
    $db = @mysql_connect($config['sql_host'],$config['sql_user'],$config['sql_pass']) or die('<center><b>Error:</b> <i>editnetworkserver.php</i>: Failed to connect to the database!</center>');
    @mysql_select_db($config['sql_db']) or die('<center><b>Error:</b> <i>editnetworkserver.php</i>: Failed to select the database!</center>');

    // URL ID
    $url_id = $_GET['id'];

    // POST Values
    $post_available   = $_POST['available'];
    $post_description = $_POST['description'];
    $post_ip          = $_POST['ip'];
    $post_location    = $_POST['location'];
    $post_datacenter  = $_POST['datacenter'];
    $post_physical    = $_POST['physical'];
    $post_os_flavor   = $_POST['os_flavor'];
    
    // Connection settings
    $post_conn_user   = $_POST['conn_user'];
    $post_conn_pass   = $_POST['conn_pass'];
    $post_conn_port   = $_POST['conn_port'];

    //
    // Update client info
    //
    require_once("../include/functions/network.php");

    // Update server
    if(!gpx_network_update($url_id,$post_ip,$post_description,$post_available,$post_physical,$post_location,$post_datacenter,$post_os_flavor,$post_conn_user,$post_conn_pass,$post_conn_port))
    {
        die('<center><b>Error:</b> Failed to update the IP Address!</center>');
    }
    
    // Redirect to editnetworkserver.php
    header("Location: editnetworkserver.php?id=$url_id&info=updated");
    exit;
}








//
// Delete Network Servers
//
elseif(isset($_POST['delete']) && !isset($_POST['update']))
{
    require_once('../include/functions/network.php');

    // List of servers to delete
    $server_list = $_POST['del_srv'];
    
    if(empty($server_list))
    {
        die('<center><b>Error:</b> <i>editnetworkserver.php</i>: No servers selected!</center>');
    }
    
    foreach($server_list as $single_server)
    {
        if(!gpx_network_delete_server($single_server))
        {
            die('<center><b>Error:</b> <i>editnetworkserver.php</i>: Failed to delete the Network Server!</center>');
        }
    }
    
    // Redirect to editnetworkserver.php
    header("Location: editnetworkserver.php?id=$url_id&info=updated");
    exit;
}
