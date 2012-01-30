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


require 'libs/Smarty.class.php';
$smarty = new Smarty;
$smarty->compile_dir  = 'templates_c/';

// Required Files
require('include/config.php');


// Don't display the navigation
$smarty->assign('nonav', '1');


// Page Title
$smarty->assign('pagetitle', 'Client Login');


########################################################################

// Check license variable
if($gpxseckey_T2V1lmkWLli04Z7q3FT != 'F9hJt6up1h80qk9REDD2xyA89TfI185gwtLXJsSMhc61fWv5T33548rLqtW5MWGjkgFl8ISzsoF8491IT2V1lmkWLli04Z7q3FTls169B8PmTx0lRZet777Pr40p7R01FkQFymp1Z629GG5dEW8nI3')
{
    die('Invalid license');
}

########################################################################

// Connect to the database
$db = @mysql_connect($config['sql_host'],$config['sql_user'],$config['sql_pass']) or die('<center><b>Error:</b> <i>login.php</i>: Failed to connect to the database!</center>');
@mysql_select_db($config['sql_db']) or die('<center><b>Error:</b> <i>login.php</i>: Failed to select the database!</center>');

########################################################################

/*
// Set user's language
require('include/functions/language.php');
$lang = gpx_language_get();
$smarty->assign('lang', $lang);
*/

// Use language from config
if(!empty($config['Language'])) $user_lang  = strtolower($config['Language']);
else $user_lang = 'english';

// Require this specific language file
$language_file = GPX_DOCROOT . '/languages/' . $user_lang . '.php';

// Make sure file exists
if(file_exists($language_file))
{
    require_once($language_file);
}
else
{
    die('<center><b>Error:</b> Failed to find the \'' . $user_lang . '\' language file!</center>');
}

// Assign language
$smarty->assign('lang', $lang);

########################################################################



// Already logged-in user
session_start();
if(isset($_SESSION['gpx_username']) && !empty($_SESSION['gpx_username']))
{
    header('Location: main.php');
    exit;
}



    
    
//
// First page
//
if(!isset($_POST['login']))
{
    // Display Login Page
    $smarty->display($config['Template'] . '/login.tpl');
}


//
// Second Page
// 
elseif(isset($_POST['login']))
{
    // Post Values
    $post_username = mysql_real_escape_string($_POST['gpx_username']);
    $post_password = mysql_real_escape_string($_POST['gpx_password']);
    
    // Check login
    $db = @mysql_connect($config['sql_host'],$config['sql_user'],$config['sql_pass']) or die('<center><b>Error:</b> <i>login.php</i>: Failed to connect to the database!</center>');
    @mysql_select_db($config['sql_db']) or die('<center><b>Error:</b> <i>login.php</i>: Failed to select the database!</center>');
    
    $result_check = @mysql_query("SELECT COUNT(id) AS logincount FROM clients WHERE username='$post_username' AND password=MD5('$post_password')") or die('<center><b>Error:</b> Failed to query clients table!</center>');
    
    while($row_check = mysql_fetch_array($result_check))
    {
        $count_login = $row_check['logincount'];
    }
    
    // Successful Login
    if($count_login >= 1)
    {
        //
        // Check if this account is active or suspended
        //
        $result_status = @mysql_query("SELECT status FROM clients WHERE username='$post_username' AND password=MD5('$post_password')") or die('<center><b>Error:</b> Failed to query clients table for status!</center>');
    
        while($row_status = mysql_fetch_array($result_status))
        {
            $user_status = $row_status['status'];
        }
        
        // Suspended Account
        if($user_status == 'suspended')
        {
            die('<center><b>Error:</b> This account is currently <u>suspended</u>.  Please contact us for further assistance.</center>');
        }
        // Closed Account
        elseif($user_status == 'closed')
        {
            die('<center><b>Error:</b> This account is currently <u>closed</u>.  Please contact us for further assistance.</center>');
        }
    
        
        
        // Get id,last login date,ip and host
        $result_last = @mysql_query("SELECT id,orig_ip,last_login,last_ip,last_host FROM clients WHERE username='$post_username' AND password=MD5('$post_password')") or die('<center><b>Error:</b> Failed to query clients table for last login!</center>');
    
        while($row_last = mysql_fetch_array($result_last))
        {
            $user_id    = $row_last['id'];
            $orig_ip    = $row_last['orig_ip'];
            $last_login = $row_last['last_login'];
            $last_ip    = $row_last['last_ip'];
            $last_host  = $row_last['last_host'];
        }
        
        
        //
        // Get current user's IP/Host info
        //
        $user_ip    = $_SERVER['REMOTE_ADDR'];
        $user_host  = gethostbyaddr($user_ip);
    
        // Setup PHP Session
        session_start();
        $_SESSION['gpx_userid']   = $user_id;
        $_SESSION['gpx_username'] = $post_username;
        $_SESSION['login_time']   = date('Y-m-d');
        $_SESSION['gpx_isclient'] = 1;
        
        // Set last logins to this session for later use
        $_SESSION['last_login'] = $last_login;
        $_SESSION['last_ip']    = $last_ip;
        $_SESSION['last_host']  = $last_host;
        
        ################################################################
        
        // Browser Detection
        require_once('include/browser.php');
        $browser = new Browser();
        $_SESSION['browser_name']     = $browser->getBrowser();
        $_SESSION['browser_version']  = $browser->getVersion();

        ################################################################
        
        // Current Session ID
        if(function_exists('session_id'))
        {
            $this_sessid  = session_id();
        }
        
        // If first time logging in, update orig_login and last_login
        if(empty($orig_ip))
        {
            @mysql_query("UPDATE clients SET logged_in = 'Y',session_id = '$this_sessid',orig_login=NOW(),orig_ip='$user_ip',orig_host='$user_host',last_login=NOW(),last_ip='$user_ip',last_host='$user_host' WHERE username='$post_username' AND password=MD5('$post_password')") or die('<center><b>Error:</b> Failed to update the clients table!</center>');
        }
        // They've been here before; just update last logins
        else
        {
            @mysql_query("UPDATE clients SET logged_in = 'Y',session_id = '$this_sessid',last_login=NOW(),last_ip='$user_ip',last_host='$user_host' WHERE username='$post_username' AND password=MD5('$post_password')") or die('<center><b>Error:</b> Failed to update the clients table!</center>');
        }

        // Forward to main page
        header('Location: main.php');
        exit;
    }
    
    // Invalid Login
    else
    {
        // Error message
        $smarty->assign('error', 'Invalid username/password given');
        
        // Display login oage
        $smarty->display($config['Template'] . '/login.tpl');
    }
}

?>
