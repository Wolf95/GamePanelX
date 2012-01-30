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



/***********************************************************************
 * 
 *                     Accounting Functions
 *
 *********************************************************************** 
*/

//
// Create Account:  Normal Client
//
function gpx_acct_create_client($username,$password,$status,$first_name,$middle_name,$last_name,$company,$phone,$email_address,$address1,$address2,$city,$state,$zip_code,$country,$language,$notes)
{
    // Escape all given values.
    $safe_username          = mysql_real_escape_string(htmlspecialchars($username));
    $safe_password          = mysql_real_escape_string($password);
    $safe_status            = mysql_real_escape_string(htmlspecialchars($status));
    $safe_first_name        = mysql_real_escape_string(htmlspecialchars($first_name));
    $safe_middle_name       = mysql_real_escape_string(htmlspecialchars($middle_name));
    $safe_last_name         = mysql_real_escape_string(htmlspecialchars($last_name));
    $safe_company           = mysql_real_escape_string(htmlspecialchars($company));
    $safe_phone             = mysql_real_escape_string(htmlspecialchars($phone));
    $safe_email_address     = mysql_real_escape_string(htmlspecialchars($email_address));
    $safe_address1          = mysql_real_escape_string(htmlspecialchars($address1));
    $safe_address2          = mysql_real_escape_string(htmlspecialchars($address2));
    $safe_city              = mysql_real_escape_string(htmlspecialchars($city));
    $safe_state             = mysql_real_escape_string(htmlspecialchars($state));
    $safe_zip_code          = mysql_real_escape_string(htmlspecialchars($zip_code));
    $safe_country           = mysql_real_escape_string(htmlspecialchars($country));
    $safe_language          = mysql_real_escape_string(htmlspecialchars($language));
    $safe_notes             = mysql_real_escape_string(htmlspecialchars($notes));
    
    // Make sure this account doesn't exist
    $result_exist = @mysql_query("SELECT COUNT(id) AS thecount FROM clients WHERE username='$safe_username'") or die('<center><b>Error:</b> <i>accounting.php</i>: Failed to check if the client account exists!</center>');
    while($row_exist = mysql_fetch_array($result_exist)) { $count_users = $row_exist['thecount']; }
    
    if($count_users >= 1)
    {
        die('<center><b>Error:</b> <i>accounting.php:</i> This account already exists!</center>');
    }
  
    //
    // Insert client into 'clients' table
    //
    
    $insert_query = "INSERT INTO clients (
                        date_added,
                        username,
                        password,
                        status,
                        first_name,
                        middle_name,
                        last_name,
                        company,
                        phone_number,
                        email_address,
                        street_address1,
                        street_address2,
                        city,
                        state,
                        zip_code,
                        country,
                        language,
                        notes) 
                    VALUES (
                        NOW(),
                        '$safe_username',
                        md5('$safe_password'),
                        '$safe_status',
                        '$safe_first_name',
                        '$safe_middle_name',
                        '$safe_last_name',
                        '$safe_company',
                        '$safe_phone',
                        '$safe_email_address',
                        '$safe_address1',
                        '$safe_address2',
                        '$safe_city',
                        '$safe_state',
                        '$safe_zip_code',
                        '$safe_country',
                        '$safe_language',
                        '$safe_notes')";
    
    // Create the client
    @mysql_query($insert_query);
    
    ####################################################################
    
    // Get the client ID just created
    $result_clid  = @mysql_query("SELECT id FROM clients WHERE username = '$safe_username' AND country = '$safe_country' AND last_name = '$safe_last_name' AND city = '$safe_city' ORDER BY id DESC LIMIT 0,1");
    
    while($row_clid = mysql_fetch_array($result_clid))
    {
        $this_clientid  = $row_clid['id'];
    }
    
    ####################################################################
    
    // Success; add to notifications
    if(is_numeric($this_clientid))
    {
        // Probably coming from the API
        if(!defined('GPX_DOCROOT'))
        {
            require('../include/functions/notifications.php');
        }
        // Normal Stuff
        else
        {
            require(GPX_DOCROOT . '/include/functions/notifications.php');
        }
        
        gpx_notify_add(1,$this_clientid);
        return true;
    }
    // Failure
    else
    {
        return false;
    }
}






########################################################################






//
// Create Account:  Administrator
//
function gpx_acct_create_admin($username,$password,$status,$first_name,$middle_name,$last_name,$email_address,$language,$notes)
{
    // Escape all given values.
    $safe_username          = mysql_real_escape_string(htmlspecialchars($username));
    $safe_password          = mysql_real_escape_string($password);
    $safe_status            = mysql_real_escape_string(htmlspecialchars($status));
    $safe_first_name        = mysql_real_escape_string(htmlspecialchars($first_name));
    $safe_middle_name       = mysql_real_escape_string(htmlspecialchars($middle_name));
    $safe_last_name         = mysql_real_escape_string(htmlspecialchars($last_name));
    $safe_email_address     = mysql_real_escape_string(htmlspecialchars($email_address));
    $safe_language          = mysql_real_escape_string(htmlspecialchars($language));
    $safe_notes             = mysql_real_escape_string(htmlspecialchars($notes));

    // Make sure this account doesn't exist
    $result_exist = @mysql_query("SELECT COUNT(id) AS thecount FROM admins WHERE username='$safe_username'") or die('<center><b>Error:</b> <i>accounting.php</i>: Failed to check if the admin account exists!</center>');
    while($row_exist = mysql_fetch_array($result_exist)) { $count_users = $row_exist['thecount']; }
    
    if($count_users >= 1)
    {
        die('<center><b>Error:</b> <i>accounting.php:</i> This account already exists!</center>');
    }

    //
    // Insert admin into 'admins' table
    //
    $insert_query = "INSERT INTO admins (
                        date_added,
                        username,
                        password,
                        first_name,
                        middle_name,
                        last_name,
                        email_address,
                        status,
                        language,
                        notes) 
                    VALUES (
                        NOW(),
                        '$safe_username',
                        md5('$safe_password'),
                        '$safe_first_name',
                        '$safe_middle_name',
                        '$safe_last_name',
                        '$safe_email_address',
                        '$safe_status',
                        '$safe_language',
                        '$safe_notes')";

    // Success
    if(mysql_query($insert_query))
    {
        return true;
    }
    // Failure
    else
    {
        return false;
    }
}






########################################################################






//
// Update Client Account
//
function gpx_acct_update_client($id,$username,$status,$first_name,$middle_name,$last_name,$company,$phone,$email_address,$address1,$address2,$city,$state,$zip_code,$country,$language,$notes)
{
    // Escape all given values
    $safe_id                = mysql_real_escape_string(htmlspecialchars($id));
    $safe_username          = mysql_real_escape_string(htmlspecialchars($username));
    $safe_status            = mysql_real_escape_string(htmlspecialchars($status));
    $safe_first_name        = mysql_real_escape_string(htmlspecialchars($first_name));
    $safe_middle_name       = mysql_real_escape_string(htmlspecialchars($middle_name));
    $safe_last_name         = mysql_real_escape_string(htmlspecialchars($last_name));
    $safe_company           = mysql_real_escape_string(htmlspecialchars($company));
    $safe_phone             = mysql_real_escape_string(htmlspecialchars($phone));
    $safe_email_address     = mysql_real_escape_string(htmlspecialchars($email_address));
    $safe_address1          = mysql_real_escape_string(htmlspecialchars($address1));
    $safe_address2          = mysql_real_escape_string(htmlspecialchars($address2));
    $safe_city              = mysql_real_escape_string(htmlspecialchars($city));
    $safe_state             = mysql_real_escape_string(htmlspecialchars($state));
    $safe_zip_code          = mysql_real_escape_string(htmlspecialchars($zip_code));
    $safe_country           = mysql_real_escape_string(htmlspecialchars($country));
    $safe_language          = mysql_real_escape_string(htmlspecialchars($language));
    $safe_notes             = mysql_real_escape_string(htmlspecialchars($notes));


    // Update settings
    if(mysql_query("UPDATE clients SET username='$safe_username',status='$safe_status',first_name='$safe_first_name',middle_name='$safe_middle_name',last_name='$safe_last_name',company='$safe_company',email_address='$safe_email_address',phone_number='$safe_phone',street_address1='$safe_address1',street_address2='$safe_address2',city='$safe_city',state='$safe_state',country='$safe_country',zip_code='$safe_zip_code',language='$safe_language',notes='$safe_notes' WHERE id='$safe_id'"))
    {
        return true;
    }
    else
    {
        return false;
    }

}



//
// Update Client Account (regular user)
//
function gpx_acct_update_client_user($id,$first_name,$middle_name,$last_name,$company,$phone,$email_address,$address1,$address2,$city,$state,$zip_code,$country,$language)
{
    // Escape all given values
    $safe_id                = mysql_real_escape_string(htmlspecialchars($id));
    $safe_first_name        = mysql_real_escape_string(htmlspecialchars($first_name));
    $safe_middle_name       = mysql_real_escape_string(htmlspecialchars($middle_name));
    $safe_last_name         = mysql_real_escape_string(htmlspecialchars($last_name));
    $safe_company           = mysql_real_escape_string(htmlspecialchars($company));
    $safe_phone             = mysql_real_escape_string(htmlspecialchars($phone));
    $safe_email_address     = mysql_real_escape_string(htmlspecialchars($email_address));
    $safe_address1          = mysql_real_escape_string(htmlspecialchars($address1));
    $safe_address2          = mysql_real_escape_string(htmlspecialchars($address2));
    $safe_city              = mysql_real_escape_string(htmlspecialchars($city));
    $safe_state             = mysql_real_escape_string(htmlspecialchars($state));
    $safe_zip_code          = mysql_real_escape_string(htmlspecialchars($zip_code));
    $safe_country           = mysql_real_escape_string(htmlspecialchars($country));
    $safe_language          = mysql_real_escape_string(htmlspecialchars($language));
    
    // Update settings
    if(mysql_query("UPDATE clients SET first_name='$safe_first_name',middle_name='$safe_middle_name',last_name='$safe_last_name',company='$safe_company',email_address='$safe_email_address',phone_number='$safe_phone',street_address1='$safe_address1',street_address2='$safe_address2',city='$safe_city',state='$safe_state',country='$safe_country',zip_code='$safe_zip_code',language='$safe_language' WHERE id = '$safe_id'"))
    {
        return true;
    }
    else
    {
        return false;
    }

}


########################################################################






//
// Update Admin Account
//
function gpx_acct_update_admin($id,$username,$status,$first_name,$middle_name,$last_name,$email_address,$language,$notes)
{
    // Escape all given values.
    $safe_id                = mysql_real_escape_string(htmlspecialchars($id));
    $safe_username          = mysql_real_escape_string(htmlspecialchars($username));
    $safe_status            = mysql_real_escape_string(htmlspecialchars($status));
    $safe_first_name        = mysql_real_escape_string(htmlspecialchars($first_name));
    $safe_middle_name       = mysql_real_escape_string(htmlspecialchars($middle_name));
    $safe_last_name         = mysql_real_escape_string(htmlspecialchars($last_name));
    $safe_email_address     = mysql_real_escape_string(htmlspecialchars($email_address));
    $safe_language          = mysql_real_escape_string(htmlspecialchars($language));
    $safe_notes             = mysql_real_escape_string(htmlspecialchars($notes));
    
    // Update settings
    if(mysql_query("UPDATE admins SET username='$safe_username',first_name='$safe_first_name',middle_name='$safe_middle_name',last_name='$safe_last_name',email_address='$safe_email_address',status='$safe_status',language='$safe_language',notes='$safe_notes' WHERE id='$safe_id'"))
    {
        return true;
    }
    else
    {
        return false;
    }

}






########################################################################



//
// Change Password
//
function gpx_acct_change_password($id,$type,$pass_new,$pass_conf)
{
    // Escape everything
    $safe_id          = mysql_real_escape_string($id);
    $safe_type        = mysql_real_escape_string($type);
    $safe_pass_new    = mysql_real_escape_string($pass_new);
    $safe_pass_conf   = mysql_real_escape_string($pass_conf);
    
    // Make sure type is correct
    if($safe_type != 'user' && $safe_type != 'admin')
    {
        die('<center><b>Error:</b> <i>accounting.php:</i> Invalid user type given!');
    }
    
    // Make sure passwords match
    if($safe_pass_new != $safe_pass_conf)
    {
        die('<center><b>Error:</b> <i>accounting.php:</i> Your passwords do not match!</center>');
    }


    //
    // Administrators
    //
    if($safe_type == 'admin')
    {
        if(mysql_query("UPDATE admins SET password = MD5('$safe_pass_new') WHERE id='$safe_id'"))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    //
    // Regular Users
    //
    if($safe_type == 'user')
    {
        if(mysql_query("UPDATE clients SET password = MD5('$safe_pass_new') WHERE id='$safe_id'"))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
}







//
// Suspend Client Account (just suspends login access, not any servers)
//
function gpx_acct_suspend($clientid)
{
    $clientid = mysql_real_escape_string($clientid);
    
    ####################################################################
    
    if(mysql_query("UPDATE clients SET status = 'suspended' WHERE id = '$clientid'"))
    {
        return true;
    }
    else
    {
        return false;
    }
}

?>
