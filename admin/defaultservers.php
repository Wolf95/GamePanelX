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
$smarty->assign('pagetitle', 'Supported Servers');

########################################################################

// Get 'type' from the URL
$url_type = $_GET['type'];

// Parse type
if($url_type != 'game' && $url_type != 'voip' && $url_type != 'other' && !empty($url_type))
{
    die('<center><b>Error:</b> <i>defaultservers.php:</i> Invalid type in the URL!</center>');
}

// Default to game
if(empty($url_type))
{
    $url_type = 'game';
}

$smarty->assign('type', $url_type);


// Assign type to Smarty
$smarty->assign('server_type', $url_type);

// ID in the URL
if(isset($_GET['id']))
{
    $url_id = mysql_real_escape_string($_GET['id']);
    $smarty->assign('newid', $url_id);
}

########################################################################


// Connect to the database
$db = @mysql_connect($config['sql_host'],$config['sql_user'],$config['sql_pass']) or die('<center><b>Error:</b> <i>defaultservers.php</i>: Failed to connect to the database!</center>');
@mysql_select_db($config['sql_db']) or die('<center><b>Error:</b> <i>defaultservers.php</i>: Failed to select the database!</center>');

// Show all Supported Servers
$query_sup  = "SELECT 
                    cfg.id,
                    cfg.short_name,
                    cfg.long_name,
                    archives.status,
                    archives.installation_status 
                FROM cfg 
                LEFT JOIN archives ON 
                    cfg.id = archives.cfgid 
                WHERE 
                    cfg.type = '$url_type' 
                    AND (archives.deleted = '0' OR archives.deleted IS NULL) 
                    AND (archives.is_default = '1' OR  archives.is_default IS NULL) 
                ORDER BY 
                    archives.status DESC,
                    cfg.long_name ASC,
                    cfg.id DESC";

## $result = @mysql_query("SELECT id,short_name,long_name,available FROM cfg $sql_where ORDER BY long_name ASC") or die('<center><b>Error:</b> <i>defaultservers.php:</i> Failed to list user accounts!</center>');
$result = @mysql_query($query_sup) or die('Failed to list default servers');

while ($line = mysql_fetch_assoc($result))
{
    $value[] = $line;
}

// Smarty mysql loop
$smarty->assign('supported_servers', $value);

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
            $info_msg = 'Server successfully created!';
            $smarty->assign('infobox', $info_msg);
        }
        
        // Delete Account
        elseif($url_info == 'deleted')
        {
            $info_msg = 'Server successfully deleted!';
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
$result_icons = @mysql_query("SELECT href,image,image_text,popup_text FROM pages WHERE page='defaultservers.php' AND template='$template' ORDER BY sort_order") or die('<center><b>Error:</b> <i>defaultservers.php:</i> Failed to get icon order!</center>');

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
$smarty->display($config['Template'] . '/defaultservers.tpl'); 
