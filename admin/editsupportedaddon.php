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
$smarty->assign('pagetitle', 'Edit Addon');


########################################################################

// Check license variable
if($gpxseckey_T2V1lmkWLli04Z7q3FT != 'F9hJt6up1h80qk9REDD2xyA89TfI185gwtLXJsSMhc61fWv5T33548rLqtW5MWGjkgFl8ISzsoF8491IT2V1lmkWLli04Z7q3FTls169B8PmTx0lRZet777Pr40p7R01FkQFymp1Z629GG5dEW8nI3')
{
    die('Invalid license');
}

########################################################################

// ID from the URL
$url_id     = $_GET['id'];
$url_srvid  = $_GET['srvid'];

// Check malformed ID
if(empty($url_id) || !is_numeric($url_id))
{
    die('<center><b>Error:</b> Invalid id given!</center>');
}

// Safe-ify
$url_id     = mysql_real_escape_string($url_id);
$url_srvid  = mysql_real_escape_string($url_srvid);

// Assign to smarty
$smarty->assign('addonid', $url_id);
$smarty->assign('serverid', $url_srvid);

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

//
// URL Actions
//
$url_action = $_GET['a'];

$allowed_actions = array('delete');

if(!empty($url_action) && !in_array($url_action, $allowed_actions))
{
    die('<center><b>Error:</b> <i>editsupportedaddon.php</i>: Invalid URL Action!</center>');
}


########

// Delete
if($url_action == 'delete')
{
    require('../include/functions/addons.php');
    
    // Success
    if(gpx_supported_addon_remove($url_id))
    {
        header("Location: supportedserveraddons.php?id=$url_srvid&info=deleted");
        exit;
    }
    // Failure
    else
    {
        die('<center><b>Error:</b> <i>editsupportedaddon.php</i>: Failed to delete the addon!</center>');
    }
}

########################################################################
// Set user's language
require('../include/functions/language.php');
$lang = gpx_language_get();
$smarty->assign('lang', $lang);

########################################################################


if(!isset($_POST['update']))
{
    // Connect to the database
    $db = @mysql_connect($config['sql_host'],$config['sql_user'],$config['sql_pass']) or die('<center><b>Error:</b> <i>editsupportedaddon.php</i>: Failed to connect to the database!</center>');
    @mysql_select_db($config['sql_db']) or die('<center><b>Error:</b> <i>editsupportedaddon.php</i>: Failed to select the database!</center>');

    //
    // Get addon info
    //
    $query_addon = "SELECT 
                      cfg_addons.id,
                      cfg_addons.type,
                      cfg_addons.available,
                      cfg_addons.status,
                      cfg_addons.name,
                      cfg_addons.description,
                      cfg_addons.addon_hash,
                      cfg_addons.file_path,
                      cfg_addons.target,
                      cfg_addons.notes,
                      network.ip,
                      network.description AS netdesc 
                    FROM cfg_addons 
                    LEFT JOIN network ON 
                      cfg_addons.networkid = network.id 
                    WHERE cfg_addons.id = '$url_id'";
    $result = @mysql_query($query_addon) or die('<center><b>Error:</b> <i>editsupportedaddon.php:</i> Failed to list client accounts!</center>');

    // Smarty loop
    while ($line = mysql_fetch_assoc($result))
    {
        $value[] = $line;
    }

    // Current creation status
    $creation_status = $value[0]['status'];
    
    // Smarty mysql loop
    $smarty->assign('cfg_addons', $value);

    ########################################################################

    //
    // Get remote creation status if DB shows it as still running
    //
    if($creation_status == 'running')
    {
        // SSH into the Remote Server and check status
        require('../include/functions/addons.php');
        $creation_status = gpx_addon_creation_status($url_id);
        
        if(empty($creation_status))
        {
            $creation_status = 'unknown';
        }
    }

    // Assign status to Smarty
    $smarty->assign('creation_status', $creation_status);

    ####################################################################

    // Display HTML Page
    $smarty->display($config['Template'] . '/editsupportedaddon.tpl'); 
}



########################################################################


elseif(isset($_POST['update']))
{
    // Connect to the database
    $db = @mysql_connect($config['sql_host'],$config['sql_user'],$config['sql_pass']) or die('<center><b>Error:</b> <i>editsupportedaddon.php</i>: Failed to connect to the database!</center>');
    @mysql_select_db($config['sql_db']) or die('<center><b>Error:</b> <i>editsupportedaddon.php</i>: Failed to select the database!</center>');


    // POST Values
    $post_type            = mysql_real_escape_string($_POST['type']);
    $post_available       = mysql_real_escape_string($_POST['available']);
    $post_name            = mysql_real_escape_string($_POST['name']);
    $post_description     = mysql_real_escape_string($_POST['description']);
    $post_target          = mysql_real_escape_string($_POST['target']);
    $post_notes           = mysql_real_escape_string($_POST['notes']);


    //
    // Update addon
    //
    $update_addon = "UPDATE cfg_addons SET 
                          type = '$post_type',
                          available = '$post_available',
                          name = '$post_name',
                          description = '$post_description',
                          target = '$post_target',
                          notes = '$post_notes' 
                        WHERE id = '$url_id'";
    
    @mysql_query($update_addon) or die('<center><b>Error:</b> <i>editsupportedaddon.php</i>: Failed to update the addon!</center>');
    
    // Redirect to editsupportedaddon.php
    header("Location: editsupportedaddon.php?id=$url_id&info=updated");
    exit;
}
