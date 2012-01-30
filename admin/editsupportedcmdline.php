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
$smarty->assign('pagetitle', 'Edit Command-Line');


########################################################################

// Check license variable
if($gpxseckey_T2V1lmkWLli04Z7q3FT != 'F9hJt6up1h80qk9REDD2xyA89TfI185gwtLXJsSMhc61fWv5T33548rLqtW5MWGjkgFl8ISzsoF8491IT2V1lmkWLli04Z7q3FTls169B8PmTx0lRZet777Pr40p7R01FkQFymp1Z629GG5dEW8nI3')
{
    die('Invalid license');
}

########################################################################

// URL variables
$url_id = $_GET['id'];

// Correct ID
if(empty($url_id) || !is_numeric($url_id))
{
    die('<center><b>Error:</b> Invalid id given!</center>');
}


########################################################################


// Connect to the database
$db = @mysql_connect($config['sql_host'],$config['sql_user'],$config['sql_pass']) or die('<center><b>Error:</b> <i>editsupportedcmdline.php</i>: Failed to connect to the database!</center>');
@mysql_select_db($config['sql_db']) or die('<center><b>Error:</b> <i>editsupportedcmdline.php</i>: Failed to select the database!</center>');

$url_id = mysql_real_escape_string($url_id);
$smarty->assign('srvid', $url_id);

########################################################################

// Set user's language
require('../include/functions/language.php');
$lang = gpx_language_get();
$smarty->assign('lang', $lang);

########################################################################

/*
// Get current server name (CS Source, etc)
$result_srv   = @mysql_query("SELECT max_slots FROM cfg WHERE id = '$url_id'");
$row_srv      = mysql_fetch_row($result_srv);
$cfg_maxslots = $row_srv[0];

// Smarty Max slots
$smarty->assign('maxslots', $cfg_maxslots);
*/

########################################################################

/*
// Get "simple" config items
$query  = "SELECT 
              cfg_items.id,
              cfg_items.simpleid,
              cfg_items.name,
              cfg_items.default_value AS item_value,
              cfg_items.description 
           FROM cfg_items 
           WHERE 
              cfg_items.srvid = '$url_id' 
              AND cfg_items.simpleid > 0";

$result = @mysql_query($query) or die('Failed to get current smp config items');

// Get the values
while ($line = mysql_fetch_assoc($result))
{
    $value[] = $line;
}

// Smarty mysql loop
$smarty->assign('cfg_simple', $value);
$query  = '';
$value  = '';
$line   = '';
$result = '';
*/

########################################################################

// Get all 4 "Simple" values from `cfg`
$query_smp  = "SELECT 
                  cfg.port,
                  cfg.max_slots,
                  cfg.map 
               FROM cfg 
               WHERE 
                  cfg.id = '$url_id'";

$result_smp = @mysql_query($query_smp);
$row_smp    = mysql_fetch_row($result_smp);

// Assign all 4 to Smarty
$smarty->assign('srv_port', $row_smp[1]);
$smarty->assign('srv_maxslots', $row_smp[2]);
$smarty->assign('srv_map', $row_smp[3]);
$smarty->assign('maxslots', $cfg_maxslots);

########################################################################

// Get all available config items for this server
$server_query = "SELECT 
                    id,
                    usr_def,
                    client_edit,
                    simpleid,
                    required,
                    name,
                    default_value AS item_value,
                    description 
                 FROM cfg_items 
                 WHERE 
                    srvid = '$url_id' 
                    AND deleted = '0'";

$result = @mysql_query($server_query) or die('Failed to get available config items');

// Get the values
while ($line = mysql_fetch_assoc($result))
{
    $value[] = $line;
}

// Smarty mysql loop
$smarty->assign('cfg_avail', $value);
$value  = '';
$line   = '';
$result = '';

########################################################################

// Display HTML Page
$smarty->display($config['Template'] . '/editsupportedcmdline.tpl'); 
