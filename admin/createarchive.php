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
$smarty->assign('pagetitle', 'Create Template');

########################################################################

// Check license variable
if($gpxseckey_T2V1lmkWLli04Z7q3FT != 'F9hJt6up1h80qk9REDD2xyA89TfI185gwtLXJsSMhc61fWv5T33548rLqtW5MWGjkgFl8ISzsoF8491IT2V1lmkWLli04Z7q3FTls169B8PmTx0lRZet777Pr40p7R01FkQFymp1Z629GG5dEW8nI3')
{
    die('Invalid license');
}

########################################################################

// Connect to the database
$db = @mysql_connect($config['sql_host'],$config['sql_user'],$config['sql_pass']) or die('<center><b>Error:</b> <i>addtemplate.php</i>: Failed to connect to the database!</center>');
@mysql_select_db($config['sql_db']) or die('<center><b>Error:</b> <i>addtemplate.php</i>: Failed to select the database!</center>');

########################################################################

/*
 * WTF, this is not needed here
 * 
// Get current template ID from the URL
$url_id = mysql_real_escape_string($_GET['id']);

if(!empty($url_id) && !is_numeric($url_id))
{
    die('<center><b>Error:</b> <i>addtemplate.php</i>: Invalid ID in the URL!</center>');
}
*/

// Set user's language
require('../include/functions/language.php');
$lang = gpx_language_get();
$smarty->assign('lang', $lang);

########################################################################


//
// Get all available games
//
$result_servers = @mysql_query("SELECT id,long_name FROM cfg ORDER BY long_name ASC") or die('<center><b>Error:</b> <i>addtemplate.php:</i> Failed to list available server types!</center>');

// Smarty loop
while ($line_servers = mysql_fetch_assoc($result_servers))
{
    $value_servers[] = $line_servers;
}

// Smarty mysql loop
$smarty->assign('servers', $value_servers);

####################################################################

//
// Get all available Network Servers
//
$result_netinfo = @mysql_query("SELECT id,ip,description FROM network WHERE physical = 'Y' AND available = 'Y' ORDER BY ip ASC") or die('<center><b>Error:</b> <i>addtemplate.php:</i> Failed to list available server types!</center>');

// Smarty loop
while ($line_netinfo = mysql_fetch_assoc($result_netinfo))
{
    $value_netinfo[] = $line_netinfo;
}

// Smarty mysql loop
$smarty->assign('network_servers', $value_netinfo);

####################################################################

// Get list of available languages
require('languages.php');


// Display HTML Page
$smarty->display($config['Template'] . '/createarchive.tpl');
