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
$smarty->assign('pagetitle', 'Templates');


########################################################################

// Check license variable
if($gpxseckey_T2V1lmkWLli04Z7q3FT != 'F9hJt6up1h80qk9REDD2xyA89TfI185gwtLXJsSMhc61fWv5T33548rLqtW5MWGjkgFl8ISzsoF8491IT2V1lmkWLli04Z7q3FTls169B8PmTx0lRZet777Pr40p7R01FkQFymp1Z629GG5dEW8nI3')
{
    die('Invalid license');
}

########################################################################


// Connect to the database
$db = @mysql_connect($config['sql_host'],$config['sql_user'],$config['sql_pass']) or die('<center><b>Error:</b> <i>templates.php</i>: Failed to connect to the database!</center>');
@mysql_select_db($config['sql_db']) or die('<center><b>Error:</b> <i>templates.php</i>: Failed to select the database!</center>');


########################################################################

// Get 'type' from the URL
$url_type = $_GET['type'];

// Parse type
if($url_type != 'game' && $url_type != 'voice' && $url_type != 'other' && !empty($url_type))
{
    die('<center><b>Error:</b> <i>templates.php:</i> Invalid type in the URL!</center>');
}

########################################################################

// Infobox from the URL
$url_info = $_GET['info'];

// Allowed info
$allowed_info = array('updated','created','deleted');

if(!empty($url_info))
{
    if(in_array($url_info, $allowed_info))
    {
        // Updated
        if($url_info == 'updated')
        {
            $info_msg = 'Template Successfully updated!';
            $smarty->assign('infobox', $info_msg);
        }
        // Created
        elseif($url_info == 'created')
        {
            $info_msg = 'Template Successfully created!';
            $smarty->assign('infobox', $info_msg);
        }
        // Deleted
        elseif($url_info == 'deleted')
        {
            $info_msg = 'Template Successfully deleted!';
            $smarty->assign('infobox', $info_msg);
        }
    }
}

########################################################################

// Current Template
$template = $config['Template'];

// Set user's language
require('../include/functions/language.php');
$lang = gpx_language_get();
$smarty->assign('lang', $lang);

########################################################################

//
// Get all templates
//


// Type from the URL
if(!empty($url_type))
{
    $sql_where = "WHERE templates.type = '$url_type'";
    $smarty->assign('type', $url_type);
}

// Loop through templates
$query_templates = "SELECT 
                      archives.id,
                      archives.is_default,
                      DATE_FORMAT(archives.date_created, '%m/%d/%Y') AS date_created,
                      archives.description,
                      archives.status,
                      archives.installation_status,
                      cfg.short_name,
                      cfg.long_name 
                    FROM archives 
                    LEFT JOIN cfg ON 
                      archives.cfgid = cfg.id 
                    $sql_where 
                    ORDER BY 
                      archives.is_default DESC,
                      archives.id DESC";

$result_templates = @mysql_query($query_templates) or die('Failed to query for archives');

while ($line_templates = mysql_fetch_assoc($result_templates))
{
    $value_templates[] = $line_templates;
}

// Smarty array
$smarty->assign('archives', $value_templates);

########################################################################

// Display HTML
$smarty->display($config['Template'] . '/archives.tpl'); 
