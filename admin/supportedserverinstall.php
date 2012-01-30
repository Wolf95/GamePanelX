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
$smarty->assign('pagetitle', 'Install Supported Server');


########################################################################

// Check license variable
if($gpxseckey_T2V1lmkWLli04Z7q3FT != 'F9hJt6up1h80qk9REDD2xyA89TfI185gwtLXJsSMhc61fWv5T33548rLqtW5MWGjkgFl8ISzsoF8491IT2V1lmkWLli04Z7q3FTls169B8PmTx0lRZet777Pr40p7R01FkQFymp1Z629GG5dEW8nI3')
{
    die('Invalid license');
}

########################################################################

// Connect to the database
$db = @mysql_connect($config['sql_host'],$config['sql_user'],$config['sql_pass']) or die('<center><b>Error:</b> <i>supportedserverinstall.php</i>: Failed to connect to the database!</center>');
@mysql_select_db($config['sql_db']) or die('<center><b>Error:</b> <i>supportedserverinstall.php</i>: Failed to select the database!</center>');

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
        if($url_info == 'created')
        {
            $info_msg = 'Server successfully created!';
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


if(!isset($_POST['install']))
{
    /*
     * Old query for supported servers that don't have templates
     * 
    $query_servers = "SELECT 
                        cfg.id,
                        cfg.long_name 
                      FROM cfg 
                      LEFT JOIN templates ON 
                        cfg.short_name = templates.server 
                      WHERE templates.id IS NULL 
                        AND cfg.is_steam = 'Y' 
                      ORDER BY cfg.long_name ASC";
                      */
    
    //
    // Get all steam games
    //
    $query_servers = "SELECT 
                        id,
                        long_name 
                      FROM cfg 
                      WHERE is_steam = 'Y' 
                      ORDER BY long_name ASC";
                      
    $result_servers = @mysql_query($query_servers);
    
    while ($line_servers = mysql_fetch_assoc($result_servers))
    {
        $value_servers[] = $line_servers;
    }

    // Smarty array
    $smarty->assign('servers', $value_servers);
    
    
    ####################################################################
    
    // List of available Network Servers
    $result_ip = @mysql_query("SELECT id,ip,description FROM network WHERE available = 'Y' AND physical = 'Y' ORDER BY ip ASC") or die('<center><b>Error:</b> <i>supportedserverinstall.php:</i> Failed to get Network Server list!</center>');

    // Get the values
    while ($line_ip = mysql_fetch_assoc($result_ip))
    {
        $value_ip[] = $line_ip;
    }

    // Smarty mysql loop
    $smarty->assign('avail_ips', $value_ip);
    
    ####################################################################
    
    // Display HTML Page
    $smarty->display($config['Template'] . '/supportedserverinstall.tpl'); 
}

########################################################################


elseif(isset($_POST['install']))
{
    // Basic Setup
    $post_networkid       = mysql_real_escape_string($_POST['networkid']);
    $post_cfgid           = mysql_real_escape_string($_POST['server']);
    $post_uploaded        = mysql_real_escape_string($_POST['uploaded']);
    // $post_filename        = mysql_real_escape_string($_POST['filename']);
    
    //
    // !! HARDCODED to 'hldsupdatetool.bin', steam games only for now
    //
    $post_filename        = 'hldsupdatetool.bin';
    

    if(!$post_uploaded)
    {
        die('You must upload your file to ~/uploads/.');
    }
    
    // !!!!!
    // If $post_uploaded == 1, file is already on the server in ~/uploads/filename.
    // If not 1, user can upload to the master, and have the master take care of the rest.
    // DO LATER.
    
    // Install supported Server
    require(GPX_DOCROOT . '/include/functions/supported.php');
    
    if(!$result_tpl_id = gpx_supported_install($post_networkid,$post_cfgid,$post_filename))
    {
        die('<center><b>Error:</b> <i>supportedserverinstall.php:</i> ' . $result_tpl_id . '</center>');
    }
    else
    {
        // Check for an ID
        if(!is_numeric($result_tpl_id))
        {
            // ERROR
            $smarty->assign('url_back', 'supportedserverinstall.php');
            $smarty->assign('error', $result_tpl_id);
            $smarty->display($config['Template'] . '/error.tpl');
            exit;
        }
    }
    
    ####################################################################
    
    // Redirect to managetemplate.php
    header("Location: managetemplate.php?id=$result_tpl_id");
    exit;
}
