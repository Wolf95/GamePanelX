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
require 'libs/Smarty.class.php';
$smarty = new Smarty;
$smarty->compile_dir  = 'templates_c/';

// Required Files
require('include/auth.php');
require('include/config.php');

// Page Title
$smarty->assign('pagetitle', 'Add Ticket');


########################################################################

// Connect to the database
$db = @mysql_connect($config['sql_host'],$config['sql_user'],$config['sql_pass']) or die('<center><b>Error:</b> <i>addticket.php</i>: Failed to connect to the database!</center>');
@mysql_select_db($config['sql_db']) or die('<center><b>Error:</b> <i>addticket.php</i>: Failed to select the database!</center>');

########################################################################

// Set user's language
require('include/functions/language.php');
$lang = gpx_language_get();
$smarty->assign('lang', $lang);

########################################################################

$smarty->assign('client_id', $_SESSION['gpx_userid']);

########################################################################


//
// 1: Account Setup
//
if(!isset($_POST['create']))
{
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
    $this_userid      = $_SESSION['gpx_userid']; //$_POST['userid'];
    $post_priority    = $_POST['priority'];
    $post_category    = $_POST['category'];
    $post_subject     = $_POST['subject'];
    $post_ticket_text = $_POST['ticket_text'];
    
    ####################################################################

    // Create the Ticket
    require("include/functions/tickets.php");
    
    #if(!gpx_tickets_create($post_userid,$post_priority,$post_category,$post_subject,$post_ticket_text,$post_notes))
    if(!gpx_tickets_create_user($this_userid,$post_priority,$post_category,$post_subject,$post_ticket_text))
    {
        die('<center><b>Error:</b> <i>addticket.php:</i> Failed to create the ticket!</center>');
    }
  
    // Show box on clients page
    header("Location: tickets.php?info=created");
    exit;
}
