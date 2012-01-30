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
$smarty->assign('pagetitle', 'Manage Client Addons');


########################################################################

// Check license variable
if($gpxseckey_T2V1lmkWLli04Z7q3FT != 'F9hJt6up1h80qk9REDD2xyA89TfI185gwtLXJsSMhc61fWv5T33548rLqtW5MWGjkgFl8ISzsoF8491IT2V1lmkWLli04Z7q3FTls169B8PmTx0lRZet777Pr40p7R01FkQFymp1Z629GG5dEW8nI3')
{
    die('Invalid license');
}

########################################################################

// Connect to the database
$db = @mysql_connect($config['sql_host'],$config['sql_user'],$config['sql_pass']) or die('<center><b>Error:</b> <i>manageserveraddons.php</i>: Failed to connect to the database!</center>');
@mysql_select_db($config['sql_db']) or die('<center><b>Error:</b> <i>manageserveraddons.php</i>: Failed to select the database!</center>');


########################################################################


// URL variables
$url_id   = $_GET['id'];
$url_type = $_GET['type'];


// Correct ID
if(empty($url_id) || !is_numeric($url_id))
{
    die('<center><b>Error:</b> Invalid id given!</center>');
}


// Correct URL type
$allowed_types = array('mod','mappack');

if(!empty($url_type) && !in_array($url_type, $allowed_types))
{
    die('<center><b>Error:</b> <i>manageserveraddons.php</i>: Invalid type in the URL!</center>');
}

// Safe-ify
$url_id     = mysql_real_escape_string($url_id);
$url_type   = mysql_real_escape_string($url_type);

// Assign to smarty
$smarty->assign('server_id', $url_id);
$smarty->assign('addon_type', $url_type);

########################################################################


// Get Server short_name from `servers`
$query_srv  = "SELECT 
                  servers.server,
                  network.id AS networkid 
              FROM servers 
              LEFT JOIN network ON 
                  servers.ip = network.ip 
              WHERE servers.id = '$url_id'";

$result_srv = @mysql_query($query_srv);

while($row_srv = mysql_fetch_array($result_srv))
{
    $this_short_name  = $row_srv['server'];
    $this_networkid   = $row_srv['networkid'];
}

########################################################################


//
// Get Addons for this game (then check if they are installed)
//
$query_cfg  = "SELECT 
                    cfg_addons.id AS addonid,
                    cfg_addons.srvid AS cfgid,
                    cfg_addons.type AS addontype,
                    cfg_addons.name,
                    cfg_addons.description 
                  FROM cfg 
                  LEFT JOIN cfg_addons ON 
                    cfg.id = cfg_addons.srvid 
                  WHERE cfg.short_name = '$this_short_name' 
                  AND cfg_addons.type = '$url_type' 
                  AND cfg_addons.networkid = '$this_networkid' 
                  ORDER BY cfg_addons.name ASC";

$result_cfg   = @mysql_query($query_cfg);
$num_cfg      = mysql_num_rows($result_cfg);


// Setup counter/smarty array
$count_addons = "0";
$smarty_arr   = array();

while($row_cfg = mysql_fetch_array($result_cfg))
{
    $addon_id     = $row_cfg['addonid'];
    $addon_cfgid  = $row_cfg['cfgid'];
    $addon_type   = $row_cfg['addontype'];
    $addon_name   = $row_cfg['name'];
    $addon_desc   = $row_cfg['description'];
    
    ####################################################################
    
    // Check if the user has this addon.
    $result_hasaddon = @mysql_query("SELECT id FROM servers_addons WHERE addonid = '$addon_id'");
    $row_hasaddon = mysql_fetch_array($result_hasaddon);

    ####################################################################

    // Set to installed or not
    if(!empty($row_hasaddon['id']))
    {
        $addon_installed = 'Y';
    }
    else
    {
        $addon_installed = 'N';
    }
    

    ####################################################################
    
    // Setup Smarty array
    $smarty_arr[$count_addons]['addonid']         = $addon_id;
    $smarty_arr[$count_addons]['cfgid']           = $addon_cfgid;
    $smarty_arr[$count_addons]['addontype']       = $addon_type;
    $smarty_arr[$count_addons]['name']            = $addon_name;
    $smarty_arr[$count_addons]['description']     = $addon_desc;
    $smarty_arr[$count_addons]['installed']       = $addon_installed;
    $smarty_arr[$count_addons]['srv_addonid']     = $row_hasaddon['id'];
    
    // Add to counter
    $count_addons++;
}

// Assign full array to Smarty
$smarty->assign('addon_details', $smarty_arr);

########################################################################

// Set user's language
require('../include/functions/language.php');
$lang = gpx_language_get();
$smarty->assign('lang', $lang);

########################################################################


// Infobox from the URL
$url_info = $_GET['info'];

// Allowed info
$allowed_info = array('installed','removed');

if(!empty($url_info))
{
    if(in_array($url_info, $allowed_info))
    {
        // Installed
        if($url_info == 'installed')
        {
            $info_msg = 'Successfully installed the addon!';
            $smarty->assign('infobox', $info_msg);
        }
        
        // Removed
        if($url_info == 'removed')
        {
            $info_msg = 'Successfully removed the addon!';
            $smarty->assign('infobox', $info_msg);
        }
    }
}


########################################################################


//
// POST Update Stuff
//
$server_id    = $_GET['id'];
$addon_id     = $_GET['addonid'];
$url_action   = $_GET['a'];
$this_addonid = $_GET['thisid'];


// Allowed actions
$allowed_actions = array('install','remove');

if(!empty($url_action) && in_array($url_action, $allowed_actions))
{
    // Install Addon
    if($url_action == 'install')
    {
        require('../include/functions/addons.php');
        gpx_addon_install($server_id,$addon_id);
        
        header("Location: manageserveraddons.php?id=$server_id&type=$url_type&info=installed");
        exit;
    }

    // Remove Addon
    elseif($url_action == 'remove')
    {
        require('../include/functions/addons.php');
        gpx_addon_remove($server_id,$this_addonid);
        
        header("Location: manageserveraddons.php?id=$server_id&type=$url_type&info=removed");
        exit;
    }
}

########################################################################

// Display HTML Page
$smarty->display($config['Template'] . '/manageserveraddons.tpl'); 
