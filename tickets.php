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
require('include/authuser.php');
require('include/config.php');

// Page Title
$smarty->assign('pagetitle', 'Support Tickets');


########################################################################

// Check license variable
if($gpxseckey_T2V1lmkWLli04Z7q3FT != 'F9hJt6up1h80qk9REDD2xyA89TfI185gwtLXJsSMhc61fWv5T33548rLqtW5MWGjkgFl8ISzsoF8491IT2V1lmkWLli04Z7q3FTls169B8PmTx0lRZet777Pr40p7R01FkQFymp1Z629GG5dEW8nI3')
{
    die('Invalid license');
}

########################################################################

// This client ID
$this_clientid = $_SESSION['gpx_userid'];

########################################################################

// Get Ticket Status from the URL
$url_status = $_GET['status'];

if(empty($url_status))
{
    $url_status = 'open';
}

$allowed_status = array('open','closed','all');

if(!in_array($url_status, $allowed_status))
{
    die('<center><b>Error:</b> <i>tickets.php</i>: Invalid Status in the URL!</center>');
}

if($url_status == 'all')
{
    $sql_where = "WHERE tickets.threadid = '0' AND userid = '$this_clientid'";
}
else
{
    $sql_where = "WHERE tickets.status = '$url_status' AND tickets.threadid = '0' AND userid = '$this_clientid'";
}

// Assign status to Smarty
$smarty->assign('ticket_status', $url_status);

########################################################################


// Connect to the database
$db = @mysql_connect($config['sql_host'],$config['sql_user'],$config['sql_pass']) or die('<center><b>Error:</b> <i>tickets.php</i>: Failed to connect to the database!</center>');
@mysql_select_db($config['sql_db']) or die('<center><b>Error:</b> <i>tickets.php</i>: Failed to select the database!</center>');

// Show all open support tickets
$query_tickets = "SELECT 
                    tickets.id,
                    DATE_FORMAT(tickets.date_added, '%c/%e/%Y %l:%i %p') AS date_added,
                    tickets.priority,
                    tickets.category,
                    tickets.subject,
                    clients.username 
                  FROM tickets 
                  LEFT JOIN clients ON 
                    tickets.userid = clients.id 
                  $sql_where 
                  ORDER BY tickets.last_updated DESC";

$result_tickets = @mysql_query($query_tickets) or die('<center><b>Error:</b> <i>tickets.php:</i> Failed to list open tickets!</center>');

while ($line_tickets = mysql_fetch_assoc($result_tickets))
{
    $value_tickets[] = $line_tickets;
}

// Smarty mysql loop
$smarty->assign('tickets', $value_tickets);


########################################################################

// Infobox from the URL
$url_info = $_GET['info'];

// Allowed info
$allowed_info = array('created','deleted');

if(!empty($url_info))
{
    if(in_array($url_info, $allowed_info))
    {
        // Create account
        if($url_info == 'created')
        {
            $info_msg = 'Ticket successfully created!';
            $smarty->assign('infobox', $info_msg);
        }
        
        // Delete Account
        elseif($url_info == 'deleted')
        {
            $info_msg = 'Ticket successfully deleted!';
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
$result_icons = @mysql_query("SELECT href,image,image_text,popup_text FROM pages WHERE page='tickets.php' AND template='$template' ORDER BY sort_order") or die('<center><b>Error:</b> <i>tickets.php:</i> Failed to get icon order!</center>');

while ($line_icons = mysql_fetch_assoc($result_icons))
{
    $value_icons[] = $line_icons;
}

// Smarty array
$smarty->assign('icons', $value_icons);


########################################################################

// Set user's language
require('include/functions/language.php');
$lang = gpx_language_get();
$smarty->assign('lang', $lang);

########################################################################

// Display HTML Page
$smarty->display($config['Template'] . '/tickets.tpl'); 
