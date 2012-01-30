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
$smarty->assign('pagetitle', 'Add Ticket');


########################################################################

// Check license variable
if($gpxseckey_T2V1lmkWLli04Z7q3FT != 'F9hJt6up1h80qk9REDD2xyA89TfI185gwtLXJsSMhc61fWv5T33548rLqtW5MWGjkgFl8ISzsoF8491IT2V1lmkWLli04Z7q3FTls169B8PmTx0lRZet777Pr40p7R01FkQFymp1Z629GG5dEW8nI3')
{
    die('Invalid license');
}

########################################################################

// Connect to the database
$db = @mysql_connect($config['sql_host'],$config['sql_user'],$config['sql_pass']) or die('<center><b>Error:</b> <i>addticket.php</i>: Failed to connect to the database!</center>');
@mysql_select_db($config['sql_db']) or die('<center><b>Error:</b> <i>addticket.php</i>: Failed to select the database!</center>');

########################################################################

// Set user's language
require('../include/functions/language.php');
$lang = gpx_language_get();
$smarty->assign('lang', $lang);

########################################################################

// Get Client ID from the URL
$url_clientid = $_GET['id'];

if(!empty($url_clientid) && !is_numeric($url_clientid))
{
    die('<center><b>Error:</b> <i>addticket.php</i>: Invalid ID in the URL!</center>');
}
elseif(!empty($url_clientid))
{
    // Assign Client ID to Smarty
    $smarty->assign('client_id', $url_clientid);
}

########################################################################


//
// 1: Account Setup
//
if(!isset($_POST['create']))
{
    // Get list of users
    $result_users = @mysql_query("SELECT id,username FROM clients ORDER BY id ASC") or die('<center><b>Error:</b> <i>addticket.php:</i> Failed to get user list!</center>');

    // Get the values
    while ($line_users = mysql_fetch_assoc($result_users))
    {
        $value_users[] = $line_users;
    }

    // Smarty mysql loop
    $smarty->assign('user_list', $value_users);
    
    ####################################################################
    
    // Display HTML Page
    $smarty->display($config['Template'] . '/addticket.tpl');
}



########################################################################



//
// 2: Create Account
//
elseif(isset($_POST['create']))
{
    // Connect to the database
    $db = @mysql_connect($config['sql_host'],$config['sql_user'],$config['sql_pass']) or die('<center><b>Error:</b> <i>addticket.php</i>: Failed to connect to the database!</center>');
    @mysql_select_db($config['sql_db']) or die('<center><b>Error:</b> <i>addticket.php</i>: Failed to select the database!</center>');

    // POST Values
    $post_userid      = $_POST['userid'];
    $post_priority    = $_POST['priority'];
    $post_category    = $_POST['category'];
    $post_subject     = $_POST['subject'];
    $post_ticket_text = $_POST['ticket_text'];
    $post_notes       = $_POST['notes'];
    
    ####################################################################

    // Create the Ticket
    require("../include/functions/tickets.php");
    if(!gpx_tickets_create($post_userid,$post_priority,$post_category,$post_subject,$post_ticket_text,$post_notes))
    {
        die('<center><b>Error:</b> <i>addticket.php:</i> Failed to create the ticket!</center>');
    }
  
    // Show box on clients page
    header("Location: tickets.php?info=created");
    exit;
}
