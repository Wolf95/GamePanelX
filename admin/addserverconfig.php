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
$smarty->assign('pagetitle', 'Add Server Config');


########################################################################

// Check license variable
if($gpxseckey_T2V1lmkWLli04Z7q3FT != 'F9hJt6up1h80qk9REDD2xyA89TfI185gwtLXJsSMhc61fWv5T33548rLqtW5MWGjkgFl8ISzsoF8491IT2V1lmkWLli04Z7q3FTls169B8PmTx0lRZet777Pr40p7R01FkQFymp1Z629GG5dEW8nI3')
{
    die('Invalid license');
}

########################################################################

// Connect to the database
$db = @mysql_connect($config['sql_host'],$config['sql_user'],$config['sql_pass']) or die('<center><b>Error:</b> <i>addserverconfig.php</i>: Failed to connect to the database!</center>');
@mysql_select_db($config['sql_db']) or die('<center><b>Error:</b> <i>addserverconfig.php</i>: Failed to select the database!</center>');

########################################################################

// URL ID
$url_id = mysql_real_escape_string($_GET['id']);

// Assign server ID
$smarty->assign('serverid', $url_id);

########################################################################

// Set user's language
require('../include/functions/language.php');
$lang = gpx_language_get();
$smarty->assign('lang', $lang);

########################################################################


if(!isset($_POST['update']))
{
    // Display HTML Page
    $smarty->display($config['Template'] . '/addserverconfig.tpl'); 
}

########################################################################


elseif(isset($_POST['update']))
{
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
        
    ####################################################################
    
    //
    // Create Supported Server
    //
    $insert_config  = "INSERT INTO cfg_configs 
                           (srvid,
                           name,
                           dir,
                           description,
                           rmcmd1,
                           rmcmd2,
                           rmcmd3,
                           rmcmd4,
                           rmcmd5,
                           rmcmd6,
                           rmcmd7,
                           rmcmd8,
                           rmcmd9,
                           rmcmd10) 
                        VALUES ( 
                          '$url_id',
                          '$post_file',
                          '$post_dir',
                          '$post_description',
                          '$post_rmcmd1',
                          '$post_rmcmd2',
                          '$post_rmcmd3',
                          '$post_rmcmd4',
                          '$post_rmcmd5',
                          '$post_rmcmd6',
                          '$post_rmcmd7',
                          '$post_rmcmd8',
                          '$post_rmcmd9',
                          '$post_rmcmd10')";
    
    @mysql_query($insert_config) or die('<center><b>Error:</b> <i>addserverconfig.php</i>: Failed to add the Server Config!</center>');
    
    ####################################################################
    
    
    // Redirect to supportedservers.php
    header("Location: supportedserverconfigs.php?id=$url_id&info=created");
    exit;
}
