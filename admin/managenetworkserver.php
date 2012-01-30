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
$smarty->assign('pagetitle', 'Manage Network Server');


########################################################################

// Check license variable
if($gpxseckey_T2V1lmkWLli04Z7q3FT != 'F9hJt6up1h80qk9REDD2xyA89TfI185gwtLXJsSMhc61fWv5T33548rLqtW5MWGjkgFl8ISzsoF8491IT2V1lmkWLli04Z7q3FTls169B8PmTx0lRZet777Pr40p7R01FkQFymp1Z629GG5dEW8nI3')
{
    die('Invalid license');
}

########################################################################






// URL variables
$url_action = $_GET['action'];
$url_id     = $_GET['id'];

// Correct ID
if(empty($url_id) || !is_numeric($url_id))
{
    die('<center><b>Error:</b> Invalid id given!</center>');
}

// List of allowed actions
$allowed_actions = array('delete');

// Correct action
if(!empty($url_action) && !in_array($url_action, $allowed_actions))
{
    die('<center><b>Error:</b> <i>managenetworkserver.php:</i> Invalid URL Parameters!</center>');
}



// Connect to the database
$db = @mysql_connect($config['sql_host'],$config['sql_user'],$config['sql_pass']) or die('<center><b>Error:</b> <i>managenetworkserver.php</i>: Failed to connect to the database!</center>');
@mysql_select_db($config['sql_db']) or die('<center><b>Error:</b> <i>managenetworkserver.php</i>: Failed to select the database!</center>');





########################################################################





//
// URL Actions
//
if(!empty($url_action) && !empty($url_id))
{
    // Delete client
    if($url_action == 'delete')
    {
        // Delete IP from 'network' table
        @mysql_query("DELETE FROM network WHERE id = '$url_id'") or die('<center><b>Error:</b> <i>managenetworkserver.php</i>: Failed to delete the ID!</center>');
        
        // Delete all associated IP Addresses
        @mysql_query("DELETE FROM network WHERE parentid = '$url_id' AND physical != 'Y'") or die('<center><b>Error:</b> <i>managenetworkserver.php</i>: Failed to delete associated IP Addresses!</center>');
        
        // Show box on clients page
        header("Location: network.php?info=deleted");
        exit;
    }
}






########################################################################







//
// Get network server info
//
$result = @mysql_query("SELECT 
                          id,
                          DATE_FORMAT(date_added, '%c/%e/%Y %H:%i') AS date_added,
                          ip,
                          description,
                          available,
                          physical,
                          parentid,
                          os,
                          location,
                          datacenter,
                          accounts_dir 
                        FROM network 
                        WHERE 
                          id='$url_id'") or die('<center><b>Error:</b> <i>managenetworkserver.php:</i> Failed to list network server!</center>');

while ($line = mysql_fetch_assoc($result))
{
    // Bad accounts dir
    if(preg_match("/FAILURE\:\ Login\ to\ the\ Remote\ Server\ failed/", $line['accounts_dir']))
    {
        $line['accounts_dir'] = 'invalid';
    }
    // OK
    elseif(preg_match("/^\//", $line['accounts_dir']) && preg_match("/\/accounts\/$/", $line['accounts_dir']))
    {
        $line['accounts_dir']  = 'valid';
    }
    // Unknown
    else
    {
        $line['accounts_dir']  = 'unknown';
    }
    
    $value[] = $line;
}

// Smarty mysql loop
$smarty->assign('server_details', $value);








########################################################################





// Current Template
$template = $config['Template'];

//
// Get all icons for this page
//
$result_icons = @mysql_query("SELECT href,image,image_text,popup_text FROM pages WHERE page='managenetworkserver.php' AND template='$template' ORDER BY sort_order") or die('<center><b>Error:</b> <i>managenetworkserver.php:</i> Failed go get icon order!</center>');

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
$smarty->display($config['Template'] . '/managenetworkserver.tpl'); 
