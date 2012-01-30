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
$smarty->assign('pagetitle', 'Add Supported Server Addon');


########################################################################

// Check license variable
if($gpxseckey_T2V1lmkWLli04Z7q3FT != 'F9hJt6up1h80qk9REDD2xyA89TfI185gwtLXJsSMhc61fWv5T33548rLqtW5MWGjkgFl8ISzsoF8491IT2V1lmkWLli04Z7q3FTls169B8PmTx0lRZet777Pr40p7R01FkQFymp1Z629GG5dEW8nI3')
{
    die('Invalid license');
}

########################################################################


// Server ID from the URL
$url_id = $_GET['id'];

// Check malformed ID
if(empty($url_id) || !is_numeric($url_id))
{
    die('<center><b>Error:</b> Invalid id given!</center>');
}

// Safe-ify
$url_id = mysql_real_escape_string($url_id);

// Assign to smarty
$smarty->assign('serverid', $url_id);

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


if(!isset($_POST['create']))
{
    //
    // Get all available 
    //
    $result_netinfo = @mysql_query("SELECT id,ip,description FROM network WHERE physical = 'Y' AND available = 'Y'") or die('<center><b>Error:</b> <i>addtemplate.php:</i> Failed to list available server types!</center>');

    // Smarty loop
    while ($line_netinfo = mysql_fetch_assoc($result_netinfo))
    {
        $value_netinfo[] = $line_netinfo;
    }

    // Smarty mysql loop
    $smarty->assign('network_servers', $value_netinfo);
    
    ####################################################################
    
    // Display HTML Page
    $smarty->display($config['Template'] . '/addsupportedaddon.tpl'); 
}



########################################################################


elseif(isset($_POST['create']))
{
    // Connect to the database
    $db = @mysql_connect($config['sql_host'],$config['sql_user'],$config['sql_pass']) or die('<center><b>Error:</b> <i>addsupportedaddon.php</i>: Failed to connect to the database!</center>');
    @mysql_select_db($config['sql_db']) or die('<center><b>Error:</b> <i>addsupportedaddon.php</i>: Failed to select the database!</center>');


    // POST Values
    $post_type            = mysql_real_escape_string($_POST['type']);
    $post_available       = mysql_real_escape_string($_POST['available']);
    $post_networkid       = mysql_real_escape_string($_POST['networkid']);
    $post_name            = mysql_real_escape_string($_POST['name']);
    $post_description     = mysql_real_escape_string($_POST['description']);
    $post_file_path       = mysql_real_escape_string($_POST['file_path']);
    $post_target          = mysql_real_escape_string($_POST['target']);
    $post_notes           = mysql_real_escape_string($_POST['notes']);


    //
    // Insert addon
    //
    require('../include/functions/addons.php');
    
    if(!gpx_supported_addon_create($url_id,$post_type,$post_available,$post_networkid,$post_name,$post_description,$post_file_path,$post_target,$post_notes))
    {
        die('<center><b>Error:</b> <i>addsupportedaddon.php</i>: Failed to create the addon!</center>');
    }
    
    // Redirect to supportedserveraddons.php
    header("Location: supportedserveraddons.php?id=$url_id&info=updated");
    exit;
}
