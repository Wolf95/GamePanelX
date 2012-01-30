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
$smarty->assign('pagetitle', 'Edit Config File Settings');


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

// Assign supported server ID
$smarty->assign('srvid', $url_srvid);

########################################################################


//
// URL actions
//
$url_action = $_GET['action'];

// List of allowed actions
$allowed_actions = array('delete');

// Correct action
if(!empty($url_action) && !in_array($url_action, $allowed_actions))
{
    die('<center><b>Error:</b> <i>editsupportedconfig.php:</i> Invalid URL Parameters!</center>');
}


########


// Delete
if($url_action == 'delete')
{
    // Delete the config
    @mysql_query("DELETE FROM cfg_configs WHERE id = '$url_id'");
    
    // Header
    header("Location: supportedserverconfigs.php?id=$url_srvid&info=deleted");
    exit;
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


if(!isset($_POST['update']))
{
    // Connect to the database
    $db = @mysql_connect($config['sql_host'],$config['sql_user'],$config['sql_pass']) or die('<center><b>Error:</b> <i>editsupportedconfig.php</i>: Failed to connect to the database!</center>');
    @mysql_select_db($config['sql_db']) or die('<center><b>Error:</b> <i>editsupportedconfig.php</i>: Failed to select the database!</center>');

    //
    // Get client info
    //
    $result = @mysql_query("SELECT id,srvid,name,dir,description,rmcmd1,rmcmd2,rmcmd3,rmcmd4,rmcmd5,rmcmd6,rmcmd7,rmcmd8,rmcmd9,rmcmd10 FROM cfg_configs WHERE id = '$url_id'") or die('<center><b>Error:</b> <i>editsupportedconfig.php:</i> Failed to list client accounts!</center>');

    // Smarty loop
    while ($line = mysql_fetch_assoc($result))
    {
        $value[] = $line;
    }

    // Smarty mysql loop
    $smarty->assign('cfg_details', $value);
    
    
    // Display HTML Page
    $smarty->display($config['Template'] . '/editsupportedconfig.tpl'); 
}



########################################################################


elseif(isset($_POST['update']))
{
    // Connect to the database
    $db = @mysql_connect($config['sql_host'],$config['sql_user'],$config['sql_pass']) or die('<center><b>Error:</b> <i>editsupportedconfig.php</i>: Failed to connect to the database!</center>');
    @mysql_select_db($config['sql_db']) or die('<center><b>Error:</b> <i>editsupportedconfig.php</i>: Failed to select the database!</center>');

    // POST Values
    $post_dir             = mysql_real_escape_string($_POST['dir']);
    $post_file            = mysql_real_escape_string($_POST['file']);
    $post_description     = mysql_real_escape_string($_POST['description']);
    
    // Forbidden Commands
    $post_rmcmd1          = mysql_real_escape_string($_POST['rmcmd1']);
    $post_rmcmd2          = mysql_real_escape_string($_POST['rmcmd2']);
    $post_rmcmd3          = mysql_real_escape_string($_POST['rmcmd3']);
    $post_rmcmd4          = mysql_real_escape_string($_POST['rmcmd4']);
    $post_rmcmd5          = mysql_real_escape_string($_POST['rmcmd5']);
    $post_rmcmd6          = mysql_real_escape_string($_POST['rmcmd6']);
    $post_rmcmd7          = mysql_real_escape_string($_POST['rmcmd7']);
    $post_rmcmd8          = mysql_real_escape_string($_POST['rmcmd8']);
    $post_rmcmd9          = mysql_real_escape_string($_POST['rmcmd9']);
    $post_rmcmd10         = mysql_real_escape_string($_POST['rmcmd10']);
 

    //
    // Update config
    //
    $update_config = "UPDATE cfg_configs SET 
                          name = '$post_file',
                          dir = '$post_dir',
                          description = '$post_description',
                          rmcmd1 = '$post_rmcmd1',
                          rmcmd2 = '$post_rmcmd2',
                          rmcmd3 = '$post_rmcmd3',
                          rmcmd4 = '$post_rmcmd4',
                          rmcmd5 = '$post_rmcmd5',
                          rmcmd6 = '$post_rmcmd6',
                          rmcmd7 = '$post_rmcmd7',
                          rmcmd8 = '$post_rmcmd8',
                          rmcmd9 = '$post_rmcmd9',
                          rmcmd10 = '$post_rmcmd10' 
                        WHERE id = '$url_id'";
    
    @mysql_query($update_config) or die('<center><b>Error:</b> <i>editsupportedconfig.php</i>: Failed to update the config!</center>');
    
    // Redirect to editsupportedconfig.php
    header("Location: editsupportedconfig.php?id=$url_id&info=updated");
    exit;
}
