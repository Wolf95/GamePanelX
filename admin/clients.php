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
$smarty->assign('pagetitle', 'User Accounts');

########################################################################

//
// Sort By from URL
//
if(isset($_GET['order']))
{
    $get_order    = $_GET['order'];
    $allowed_ord  = array('id','username','first_name','last_name','email_address','status');
    
    if(!in_array($get_order, $allowed_ord)) die('Invalid order by!');

    $order_by = 'clients.' . $get_order;
    
    if(isset($_GET['sort']))
    {
        $sort_by  = strtoupper($_GET['sort']);
        if($sort_by == 'ASC' || $sort_by == 'DESC')
          $order_by .= ' ' . $sort_by . ' ';
    }
    else $order_by .= ' ASC ';
}
// Default to ID
else
{
    $order_by = 'clients.id DESC';
}

// Assign order and sort
$smarty->assign('s_order', $get_order);
$smarty->assign('s_sort', strtolower($sort_by));

########################################################################

// Hardcoded for now
$per_page = 30;

//
// Paging
//
if(isset($_GET['p']) && is_numeric($_GET['p']))
{
    $start_page = $_GET['p'];
    
    // Subtract 1 so pages start at 1, now 0
    if($start_page >= 1) $start_page--;
    
    $sql_limit  = $start_page * $per_page . ',' . $per_page;
}
// Default
else $sql_limit = '0,' . $per_page;

########################################################################

// Connect to the database
$db = @mysql_connect($config['sql_host'],$config['sql_user'],$config['sql_pass']) or die('<center><b>Error:</b> <i>clients.php</i>: Failed to connect to the database!</center>');
@mysql_select_db($config['sql_db']) or die('<center><b>Error:</b> <i>clients.php</i>: Failed to select the database!</center>');

// Show all user accounts
$result = @mysql_query("SELECT 
                          id,
                          username,
                          status,
                          first_name,
                          last_name,
                          email_address 
                        FROM clients 
                        WHERE 
                          status != 'closed' 
                        ORDER BY 
                          $order_by 
                        LIMIT 
                          $sql_limit") or die('<center><b>Error:</b> <i>clients.php:</i> Failed to list user accounts!</center>');

// Total Clients
$result_found = @mysql_query('SELECT FOUND_ROWS()');
$row_found    = mysql_fetch_row($result_found);
$total_found  = $row_found[0];

while ($line = mysql_fetch_assoc($result))
{
    $value[] = $line;
}

// Smarty mysql loop
$smarty->assign('users', $value);

// Total pages
$total_pages  = round($total_found / $per_page);
$start_page   = $start_page + 1;

$smarty->assign('page', $start_page);
$smarty->assign('total_rows', $total_found);
$smarty->assign('total_pages', $total_pages);

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
            $info_msg = 'Account successfully created!';
            $smarty->assign('infobox', $info_msg);
        }
        
        // Delete Account
        elseif($url_info == 'deleted')
        {
            $info_msg = 'Account successfully deleted!';
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
$result_icons = @mysql_query("SELECT href,image,image_text,popup_text FROM pages WHERE page='clients.php' AND template='$template' ORDER BY sort_order") or die('<center><b>Error:</b> <i>clients.php:</i> Failed to get icon order!</center>');

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

########################################################################

// Display HTML Page
$smarty->display($config['Template'] . '/clients.tpl'); 
