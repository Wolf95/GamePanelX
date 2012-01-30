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
$smarty->assign('pagetitle', 'Manage Template');


########################################################################

// Check license variable
if($gpxseckey_T2V1lmkWLli04Z7q3FT != 'F9hJt6up1h80qk9REDD2xyA89TfI185gwtLXJsSMhc61fWv5T33548rLqtW5MWGjkgFl8ISzsoF8491IT2V1lmkWLli04Z7q3FTls169B8PmTx0lRZet777Pr40p7R01FkQFymp1Z629GG5dEW8nI3')
{
    die('Invalid license');
}

########################################################################

// Connect to the database
$db = @mysql_connect($config['sql_host'],$config['sql_user'],$config['sql_pass']) or die('<center><b>Error:</b> <i>managetemplate.php</i>: Failed to connect to the database!</center>');
@mysql_select_db($config['sql_db']) or die('<center><b>Error:</b> <i>managetemplate.php</i>: Failed to select the database!</center>');

########################################################################


// URL variables
$url_action = $_GET['action'];
$url_id     = mysql_real_escape_string($_GET['id']);

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
    die('<center><b>Error:</b> <i>managetemplate.php:</i> Invalid URL Parameters!</center>');
}

########################################################################


//
// Get remote Supported Server installation status/percentage (automagical) if DB shows as running
//

/*
$result_status = @mysql_query("SELECT id,installation_status FROM archives WHERE id = '$url_id'");

while($row_status = mysql_fetch_array($result_status))
{
    $installation_status  = $row_status['installation_status'];
}
*/

########

/*
//
// Running Installation (automagical)
//
if($installation_status == 'running')
{
    // SSH into the Remote Server and check status
    require_once('../include/functions/supported.php');
    $install_status = @gpx_status_install_supported_server($url_id);

    if(empty($install_status))
    {
        $smarty_status = 'unknown';
    }
    // Check for failure
    elseif(preg_match("/FAILURE\:/", $creation_status))
    {
        $smarty_status = 'unknown';
    }
    
    // Installation percentage
    elseif(is_numeric($install_status))
    {
        $smarty_status  = 'installing'; //'Installing: <b>' . $install_status . '%</b>';
        $smarty_percent = $install_status;
    }
    
    // Running, no percentage
    elseif(preg_match("/running/i", $install_status))
    {
        $smarty_status  = 'running';
    }
    
    else
    {
        $smarty_status  = "";
    }

    // Assign status to Smarty
    $smarty->assign('install_status', $smarty_status);
    $smarty->assign('install_percent', $smarty_percent);
}


//
// Completed automagical Installation
//
elseif($installation_status == 'complete')
{
    $smarty->assign('install_status', 'complete');
}
// No status; probably manually installed
else
{
    $smarty->assign('install_status', 'none');
}
*/

// For now?
$smarty->assign('install_status', $installation_status);

########################################################################

//
// Get template info
//
$template_query = "SELECT 
                      archives.id,
                      archives.networkid,
                      archives.is_default,
                      DATE_FORMAT(archives.date_created, '%c/%e/%Y %H:%i') AS date_created,
                      archives.file_path,
                      archives.description,
                      archives.status,
                      archives.installation_status,
                      cfg.id AS cfgid,
                      cfg.long_name,
                      cfg.short_name,
                      network.ip,
                      network.description AS networkdesc 
                    FROM archives 
                    LEFT JOIN cfg ON 
                      archives.cfgid = cfg.id 
                    LEFT JOIN network ON 
                      archives.networkid = network.id 
                    WHERE 
                      archives.id = '$url_id'";

$result = @mysql_query($template_query) or die('<center><b>Error:</b> <i>managetemplate.php:</i> Failed to get template information!</center>');

// Get the values
while ($line = mysql_fetch_assoc($result))
{
    $value[] = $line;
}

// Current template status
$template_status = $value[0]['status'];

// Smarty mysql loop
$smarty->assign('template_details', $value);
$smarty->assign('archiveid', $url_id);

########################################################################

/*
//
// Get remote template status if DB shows it as still running
//
if($template_status == 'running')
{
    // SSH into the Remote Server and check status
    require('../include/functions/templates.php');
    $template_status = gpx_template_status($url_id);
    
    if(empty($template_status))
    {
        $template_status = 'unknown';
    }
}
*/

// Assign status to Smarty
$smarty->assign('template_status', 'none');

// Current Template
$template = $config['Template'];

########################################################################

/*

//
// URL Actions
//
if(!empty($url_action) && !empty($url_id))
{
    // Delete user
    if($url_action == 'delete')
    {
        // Delete template
        require('../include/functions/templates.php');
        
        if(!gpx_template_delete($url_id))
        {
            die('<center><b>Error:</b> <i>managetemplate.php:</i> Failed to delete the template!</center>');
        }
        
        // Show box on users page
        header("Location: templates.php?info=deleted");
        exit;
    }
}
*/

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

########################################################################

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

########################################################################

// Set user's language
require('../include/functions/language.php');
$lang = gpx_language_get();
$smarty->assign('lang', $lang);

########################################################################

// Display HTML Page
$smarty->display($config['Template'] . '/managetemplate.tpl');
