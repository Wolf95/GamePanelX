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
$smarty->assign('pagetitle', 'System Configuration');


########################################################################

// Connect to the database
$db = @mysql_connect($config['sql_host'],$config['sql_user'],$config['sql_pass']) or die('<center><b>Error:</b> <i>configuration.php</i>: Failed to connect to the database!</center>');
@mysql_select_db($config['sql_db']) or die('<center><b>Error:</b> <i>configuration.php</i>: Failed to select the database!</center>');

########################################################################


// Assign all config values to smarty variables
$smarty->assign('config_docroot', $config['DocRoot']);
$smarty->assign('config_company', $config['CompanyName']);
$smarty->assign('config_template', $config['Template']);
$smarty->assign('config_email_pri', $config['PrimaryEmail']);
$smarty->assign('config_email_sec', $config['SecondaryEmail']);
$smarty->assign('config_server_qt', $config['ServerQueryTimeout']);
$smarty->assign('config_recs_per_page', $config['RecordsPerPage']);
$smarty->assign('config_language', $config['Language']);
$smarty->assign('config_rm_srv_timeout', $config['RemoteServerTimeout']);
$smarty->assign('config_start_sv_after_create', $config['StartServerAfterCreate']);
$smarty->assign('config_billing_enable', $config['BillingEnabled']);
$smarty->assign('config_version', $config['Version']);
$smarty->assign('config_billing_loadlimit', $config['BalanceLoadLimit']);
$smarty->assign('config_billing_serverlimit', $config['BalanceServerLimit']);
$smarty->assign('config_billing_defports', $config['BalanceDefaultPortOnly']);
$smarty->assign('api_key', $config['api_key']);

########################################################################

// Infobox from the URL
$url_info = $_GET['info'];

// Allowed info
$allowed_info = array('updated');

if(!empty($url_info))
{
    if(in_array($url_info, $allowed_info))
    {
        // Config Updated
        if($url_info == 'updated')
        {
            $info_msg = 'Configuration Successfully Updated!';
            $smarty->assign('infobox', $info_msg);
        }
    }
}

########################################################################


// Current Template
$template = $config['Template'];

//
// Get all icons for this page
//
$result_icons = @mysql_query("SELECT href,image,image_text,popup_text FROM pages WHERE page='configuration.php' AND template='$template' ORDER BY sort_order") or die('<center><b>Error:</b> <i>configuration.php:</i> Failed to get icon order!</center>');

while ($line_icons = mysql_fetch_assoc($result_icons))
{
    $value_icons[] = $line_icons;
}

// Smarty array
$smarty->assign('icons', $value_icons);


########################################################################

// Set user's language
require('../include/functions/language.php');
$lang = gpx_language_get();
$smarty->assign('lang', $lang);

// Languages dropdown
require('languages.php');

########################################################################


//
// First Page
//
if(!isset($_POST['submit']))
{
    $smarty->display($config['Template'] . '/configuration.tpl'); 
}



//
// First Page
//
elseif(isset($_POST['submit']))
{
    // POST Values
    $post_docroot         = mysql_real_escape_string($_POST['docroot']);
    $post_company         = mysql_real_escape_string($_POST['company']);
    $post_template        = mysql_real_escape_string($_POST['template']);
    $post_prim_email      = mysql_real_escape_string($_POST['prim_email']);
    $post_sec_email       = mysql_real_escape_string($_POST['sec_email']);
    $post_query_timeout   = mysql_real_escape_string($_POST['query_timeout']);
    $post_records         = mysql_real_escape_string($_POST['records_per_page']);
    $post_language        = mysql_real_escape_string($_POST['lang']);
    $post_remote_timeout  = mysql_real_escape_string($_POST['remote_timeout']);
    $post_start_sv        = mysql_real_escape_string($_POST['start_sv_after_create']);
    $post_billing_on      = mysql_real_escape_string($_POST['billing_enable']);
    $post_server_limit    = mysql_real_escape_string($_POST['server_limit']);
    $post_load_limit      = mysql_real_escape_string($_POST['load_limit']);
    $post_def_ports_only  = mysql_real_escape_string($_POST['default_ports_only']);


    // Update configuration values
    @mysql_query("UPDATE configuration SET value = '$post_docroot' WHERE setting = 'DocRoot'");
    @mysql_query("UPDATE configuration SET value = '$post_company' WHERE setting = 'CompanyName'");
    @mysql_query("UPDATE configuration SET value = '$post_template' WHERE setting = 'Template'");
    @mysql_query("UPDATE configuration SET value = '$post_language' WHERE setting = 'Language'");
    @mysql_query("UPDATE configuration SET value = '$post_query_timeout' WHERE setting = 'ServerQueryTimeout'");
    @mysql_query("UPDATE configuration SET value = '$post_prim_email' WHERE setting = 'PrimaryEmail'");
    @mysql_query("UPDATE configuration SET value = '$post_sec_email' WHERE setting = 'SecondaryEmail'");
    @mysql_query("UPDATE configuration SET value = '$post_remote_timeout' WHERE setting = 'RemoteServerTimeout'");
    @mysql_query("UPDATE configuration SET value = '$post_start_sv' WHERE setting = 'StartServerAfterCreate'");
    @mysql_query("UPDATE configuration SET value = '$post_billing_on' WHERE setting = 'BillingEnabled'");
    @mysql_query("UPDATE configuration SET value = '$post_server_limit' WHERE setting = 'BalanceServerLimit'");
    @mysql_query("UPDATE configuration SET value = '$post_load_limit' WHERE setting = 'BalanceLoadLimit'");
    @mysql_query("UPDATE configuration SET value = '$post_def_ports_only' WHERE setting = 'BalanceDefaultPortOnly'");
    
    // Show success
    header("Location: configuration.php?info=updated");
    exit;
}
