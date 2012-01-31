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
$smarty->assign('pagetitle', 'Edit Settings');

// This client ID
$this_clientid = $_SESSION['gpx_userid'];

########################################################################


// Infobox from the URL
$url_info = $_GET['info'];

// Allowed info
$allowed_info = array('updated');

if(!empty($url_info))
{
    if(in_array($url_info, $allowed_info))
    {
        // Update Account
        if($url_info == 'updated')
        {
            $info_msg = 'Account successfully updated!';
            $smarty->assign('infobox', $info_msg);
        }
    }
}

########################################################################

// Set user's language
require('include/functions/language.php');
$lang = gpx_language_get();
$smarty->assign('lang', $lang);

########################################################################


if(!isset($_POST['update']))
{
    // Connect to the database
    $db = @mysql_connect($config['sql_host'],$config['sql_user'],$config['sql_pass']) or die('<center><b>Error:</b> <i>editsettings.php</i>: Failed to connect to the database!</center>');
    @mysql_select_db($config['sql_db']) or die('<center><b>Error:</b> <i>editsettings.php</i>: Failed to select the database!</center>');

    //
    // Get client info
    //
    $result = @mysql_query("SELECT id,DATE_FORMAT(date_added, '%c/%e/%Y %H:%i') AS date_added,orig_ip,orig_host,last_login,last_ip,last_host,username,status,first_name,middle_name,last_name,email_address,company,phone_number,city,street_address1,street_address2,city,state,country,zip_code,language,notes FROM clients WHERE id='$this_clientid'") or die('<center><b>Error:</b> <i>editsettings.php:</i> Failed to list client accounts!</center>');

    // Smarty loop
    while ($line = mysql_fetch_assoc($result))
    {
        $value[] = $line;
    }

    // Smarty mysql loop
    $smarty->assign('user_details', $value);
    
    
    // Get list of available languages
    require('languages.php');
    
    
    
    // Display HTML Page
    $smarty->display($config['Template'] . '/editsettings.tpl'); 
}

########################################################################


elseif(isset($_POST['update']))
{
    // Connect to the database
    $db = @mysql_connect($config['sql_host'],$config['sql_user'],$config['sql_pass']) or die('<center><b>Error:</b> <i>editsettings.php</i>: Failed to connect to the database!</center>');
    @mysql_select_db($config['sql_db']) or die('<center><b>Error:</b> <i>editsettings.php</i>: Failed to select the database!</center>');


    // POST Values (escape all of them)    
    $post_cur_pass      = $_POST['cur_password'];
    $post_new_pass      = $_POST['new_password'];
    $post_new_pass_conf = $_POST['new_password_confirm'];

    $post_first_name    = $_POST['first_name'];
    $post_middle_name   = $_POST['middle_name'];
    $post_last_name     = $_POST['last_name'];
    $post_company       = $_POST['company'];

    $post_email         = $_POST['email_address'];
    $post_phone         = $_POST['phone_number'];
    $post_address1      = $_POST['street_address1'];
    $post_address2      = $_POST['street_address2'];
    $post_city          = $_POST['city'];
    $post_state         = $_POST['state'];
    $post_country       = $_POST['country'];
    $post_zip           = $_POST['zip_code'];

    $post_language      = $_POST['language'];
        

    //
    // Update client info
    //
    require("include/functions/accounting.php");
    
    // Update password if necessary
    if(!empty($post_new_pass) && !empty($post_new_pass_conf))
    {
        // Check if the current password is OK
        $cur_pass_esc     = mysql_real_escape_string($post_cur_pass);
        $result_curpass   = @mysql_query("SELECT COUNT(id) AS thecount FROM clients WHERE id = '$this_clientid' AND password = MD5('$cur_pass_esc')");
        
        while($row_curpass = mysql_fetch_array($result_curpass))
        {
            $match = $row_curpass['thecount'];
        }
        
        // Check
        if($match < 1)
        {
            // ERROR
            $smarty->assign('url_back', "editsettings.php");
            $smarty->assign('error', 'Invalid current password entered.');
            $smarty->display($config['Template'] . '/error.tpl');
            exit;
        }
        
        ################################################################
        
        // User Account
        $type = 'user';
        
        if(!gpx_acct_change_password($this_clientid,$type,$post_new_pass,$post_new_pass_conf))
        {
            die('<center><b>Error:</b> Failed to change your password!</center>');
        }
    }
    
    // Update account
    if(!gpx_acct_update_client_user($this_clientid,$post_first_name,$post_middle_name,$post_last_name,$post_company,$post_phone,$post_email,$post_address1,$post_address2,$post_city,$post_state,$post_zip,$post_country,$post_language))
    {
        die('<center><b>Error:</b> Failed to update your client info!</center>');
    }
    

    
    // Redirect to editsettings.php
    header("Location: editsettings.php?info=updated");
    exit;
}
