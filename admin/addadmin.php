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
$smarty->assign('pagetitle', 'Add Admin Account');

// Current page
$current_page = basename($_SERVER['PHP_SELF']);

########################################################################

// Check license variable
if($gpxseckey_T2V1lmkWLli04Z7q3FT != 'F9hJt6up1h80qk9REDD2xyA89TfI185gwtLXJsSMhc61fWv5T33548rLqtW5MWGjkgFl8ISzsoF8491IT2V1lmkWLli04Z7q3FTls169B8PmTx0lRZet777Pr40p7R01FkQFymp1Z629GG5dEW8nI3')
{
    die('Invalid license');
}

########################################################################

// Connect to the database
$db = @mysql_connect($config['sql_host'],$config['sql_user'],$config['sql_pass']) or die('<center><b>Error:</b> <i>' . $current_page . '</i>: Failed to connect to the database!</center>');
@mysql_select_db($config['sql_db']) or die('<center><b>Error:</b> <i>' . $current_page . '</i>: Failed to select the database!</center>');

########################################################################


// Set user's language
require('../include/functions/language.php');
$lang = gpx_language_get();
$smarty->assign('lang', $lang);


########################################################################



//
// 1: Account Setup
//
if(!isset($_POST['create']))
{
    // Get list of available languages
    require('languages.php');
    
    // Display HTML Page
    $smarty->display($config['Template'] . '/addadmin.tpl');
}



########################################################################



//
// 2: Create Account
//
elseif(isset($_POST['create']))
{
    // Connect to the database
    $db = @mysql_connect($config['sql_host'],$config['sql_user'],$config['sql_pass']) or die('<center><b>Error:</b> <i>addadmin.php</i>: Failed to connect to the database!</center>');
    @mysql_select_db($config['sql_db']) or die('<center><b>Error:</b> <i>addadmin.php</i>: Failed to select the database!</center>');

    // POST Values
    $post_username      = $_POST['username'];
    $post_password      = $_POST['password'];
    $post_pass_conf     = $_POST['password_confirm'];
    $post_first_name    = $_POST['first_name'];
    $post_middle_name   = $_POST['middle_name'];
    $post_last_name     = $_POST['last_name'];
    $post_email         = $_POST['email_address'];
    $post_status        = $_POST['status'];
    $post_language      = $_POST['language'];
    $post_notes         = $_POST['notes'];

########################################################################


    
    // Check Required Fields
    $required_fields = array('username','password','password_confirm');
    
    foreach($required_fields as $single_field)
    {
        // If field was empty
        if(empty($_POST[$single_field]))
        {
            die('<center><b>Error:</b> The required field \'' . $single_field . '\' was left empty!</center>');
        }
    }

    // Make sure passwords match
    if($post_password != $post_pass_conf)
    {
        die('<center><b>Error:</b> Your passwords do not match!</center>');
    }



########################################################################


// Accounting functions
require("../include/functions/accounting.php");


// Create Account
if(!gpx_acct_create_admin($post_username,$post_password,$post_status,$post_first_name,$post_middle_name,$post_last_name,$post_email,$post_language,$post_notes))
{
    die('<center><b>Error:</b> Failed to create admin account!</center>');
}



########################################################################
    
  
    // Show box on users page
    header("Location: admins.php?info=created");
    exit;
}
