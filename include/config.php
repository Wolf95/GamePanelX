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

// PHP Values
error_reporting(E_ERROR);
set_time_limit(12);


// Begin Session
if(!isset($_SESSION['gpx_userid']))
{
    session_start();
}


//
// Regular client trying to view admin directory
//
if(!isset($_SESSION['gpx_isadmin']) && isset($_SESSION['gpx_userid']))
{
    if(basename(getcwd()) == 'admin')
    {
        die('<b>Error:</b> You are not authorized to view this page.');
    }
}




// Config Array
$config = array();

// Database Details
require_once('db.php');

// Connect to the database
$db = @mysql_connect($config['sql_host'],$config['sql_user'],$config['sql_pass']) or die('<center><b>Error:</b> <i>config.php</i>: Failed to connect to the database!</center>');
@mysql_select_db($config['sql_db']) or die('<center><b>Error:</b> <i>config.php</i>: Failed to select the database!</center>');

// Generic query error
$query_error = '<center><b>Error:</b> <i>config.php:</i> Failed to query configuration table!</center>';

// Get all config options
$result_configs = @mysql_query("SELECT setting,value FROM configuration") or die ($query_error);

while($row_configs = mysql_fetch_array($result_configs))
{
    // DB Values
    $config_setting = $row_configs['setting'];
    $config_value   = $row_configs['value'];


    // Template/Theme
    if($config_setting == 'Template')
    {
        $config['Theme']    = $config_value;
        $config['Template'] = $config_value;
    }
    
    // Primary Email
    elseif($config_setting == 'PrimaryEmail')
    {
        $config['PrimaryEmail'] = $config_value;
    }
    
    // Secondary Email
    elseif($config_setting == 'SecondaryEmail')
    {
        $config['SecondaryEmail'] = $config_value;
    }
    
    // Company Name
    elseif($config_setting == 'CompanyName')
    {
        $config['CompanyName'] = $config_value;
    }
    
    // Server Query Timeout
    elseif($config_setting == 'ServerQueryTimeout')
    {
        $config['ServerQueryTimeout'] = $config_value;
    }
    
    // SQL Records Per Page
    elseif($config_setting == 'RecordsPerPage')
    {
        $config['RecordsPerPage'] = $config_value;
    }
    
    // Language
    elseif($config_setting == 'Language')
    {
        $config['Language'] = $config_value;
    }

    // Remote Server Timeout
    elseif($config_setting == 'RemoteServerTimeout')
    {
        $config['RemoteServerTimeout'] = $config_value;
    }
    
    // Version
    elseif($config_setting == 'Version')
    {
        $config['Version'] = $config_value;
        
        if(!defined('GPX_VERSION'))
        {
            define('GPX_VERSION', $config_value);
        }
    }
    
    // Start Server after creation
    elseif($config_setting == 'StartServerAfterCreate')
    {
        $config['StartServerAfterCreate'] = $config_value;
        
        if(!defined('GPX_CFG_START_SRV_AFTER_CREATE'))
        {
            define('GPX_CFG_START_SRV_AFTER_CREATE', $config_value);
        }
    }
    
    // Billing System
    elseif($config_setting == 'BillingEnabled')
    {
        $config['BillingEnabled'] = $config_value;
        
        if(!defined('GPX_CFG_BILLING_ENABLED'))
        {
            define('GPX_CFG_BILLING_ENABLED', $config_value);
        }
    }
    
    // Document Root
    elseif($config_setting == 'DocRoot')
    {
        $config['DocRoot'] = $config_value;
        define('GPX_DOCROOT', $config_value);
    }
    
    // Billing - Load Limit
    elseif($config_setting == 'BalanceLoadLimit')
    {
        $config['BalanceLoadLimit'] = $config_value;
    }
    
    // Billing - Server Limit
    elseif($config_setting == 'BalanceServerLimit')
    {
        $config['BalanceServerLimit'] = $config_value;
    }
    
    // Billing - Use default ports only
    elseif($config_setting == 'BalanceDefaultPortOnly')
    {
        $config['BalanceDefaultPortOnly'] = $config_value;
    }
}

// Set script path (deprecated)
$config['ScriptPath'] = dirname(GPX_DOCROOT);

########################################################################

// Update client login time
if(isset($_SESSION['gpx_userid']) && !isset($_SESSION['gpx_isadmin']) && isset($_SESSION['gpx_isclient']))
{
    $this_userid  = $_SESSION['gpx_userid'];
    @mysql_query("UPDATE clients SET last_response = NOW() WHERE id = '$this_userid'");
}

########################################################################

// Make encryption key constant
define('GPX_ENCKEY', $config['encrypt_key']);


//
// Smarty
//
if($smarty)
{
    $smarty->assign('script_path', $config['ScriptPath']);
    $smarty->assign('company', $config['CompanyName']);
    $smarty->assign('template', $config['Template']);
    $smarty->assign('template_path', $config['ScriptPath'] . "/templates/" . $config['Template'] . "/");

    // Current User Variables
    $smarty->assign('userid', $_SESSION['gpx_userid']);
    $smarty->assign('username', $_SESSION['gpx_username']);
    $smarty->assign('login_time', $_SESSION['login_time']);
    
    // Browser Detection (Works best PHP 5+; has issues on PHP 4)
    if(isset($_SESSION['browser_name']) && !empty($_SESSION['browser_name']))
    {
        $smarty->assign('browser', $_SESSION['browser_name']);
    }
    if(isset($_SESSION['browser_version']) && !empty($_SESSION['browser_version']))
    {
        $smarty->assign('browser_ver', $_SESSION['browser_version']);
    }
}

########################################################################

// Old school license thing, leaving here until I go through and update all pages to not need it
$gpxseckey_T2V1lmkWLli04Z7q3FT = 'F9hJt6up1h80qk9REDD2xyA89TfI185gwtLXJsSMhc61fWv5T33548rLqtW5MWGjkgFl8ISzsoF8491IT2V1lmkWLli04Z7q3FTls169B8PmTx0lRZet777Pr40p7R01FkQFymp1Z629GG5dEW8nI3';

?>
