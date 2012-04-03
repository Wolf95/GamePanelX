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
error_reporting(E_ERROR);

// If they have a current install, send to update.php
if(file_exists('../include/db.php')) include('../include/db.php');

if(isset($config['sql_host']) && isset($config['sql_user']))
{
    if(!empty($config['sql_user']))
    {
        echo '<div align="center">You seem to have a GamePanelX installation already.<br /><br /><a href="update.php">Click Here to update</a>.</div>';
        exit;
    }
}


// Get version
require('version.php');

########################################################################

$config = array();
$config['Language']   = 'english';
$config['Template']   = 'default';
$config['Theme']      = 'default';

########################################################################

//
// First Page (DB settings, admin user)
//
if(!isset($_POST['checkreq']) && !isset($_POST['step1']) && !isset($_POST['step2']))
{
    require('checkrequired.php');
    exit;
}

//
// Begin installation options (Real 1st page)
//
elseif(isset($_POST['checkreq']) && !isset($_POST['step1']) && !isset($_POST['step2']))
{
    // Current directory
    $current_dir  = getcwd();
    $current_dir  = str_replace('/install', '/', $current_dir); // Remove '/install' at the end
    
    require('step1.php');
    exit;
}


//
// Second page (System settings)
//
elseif(isset($_POST['step1']) && !isset($_POST['step2']) && !isset($_POST['checkreq']))
{
    // POST Values
    $post_install_dir   = base64_encode($_POST['install_dir']);
    $post_db_host       = base64_encode($_POST['db_host']);
    $post_db_name       = base64_encode($_POST['db_name']);
    $post_db_user       = base64_encode($_POST['db_user']);
    $post_db_pass       = base64_encode($_POST['db_pass']);
    $post_admin_user    = base64_encode($_POST['admin_user']);
    $post_admin_pass    = base64_encode($_POST['admin_pass']);
    $post_admin_email   = base64_encode($_POST['admin_email']);
    $post_language      = base64_encode($_POST['language']);
    
    ####################################################################
    
    // Check empty
    if(empty($post_install_dir) || 
    empty($post_db_host) || 
    empty($post_db_name) || 
    empty($post_db_user) || 
    empty($post_db_pass) || 
    empty($post_admin_user) || 
    empty($post_admin_pass) || 
    empty($post_admin_email) || 
    empty($post_language))
    {
        die('<b>Error:</b> Required fields were left blank.');
    }
    
    ####################################################################
    
    // Display Step 2
    require('step2.php');
    exit;
}



//
// Second page (Install)
//
elseif(isset($_POST['step2']) && !isset($_POST['step1']) && !isset($_POST['checkreq']))
{
    // DB Info
    $post_db_host       = stripslashes(base64_decode($_POST['db_host']));
    $post_db_name       = stripslashes(base64_decode($_POST['db_name']));
    $post_db_user       = stripslashes(base64_decode($_POST['db_user']));
    $post_db_pass       = stripslashes(base64_decode($_POST['db_pass']));
    
    ####################################################################
    
    // Connect to the database with the given values
    $db = @mysql_connect($post_db_host,$post_db_user,$post_db_pass) or die('<center><b>Error:</b> <i>install</i>: Failed to connect to the database!</center>');
    @mysql_select_db($post_db_name) or die('<center><b>Error:</b> <i>install</i>: Failed to select the database!</center>');
    
    ####################################################################
    
    // POST Values
    $post_install_dir   = mysql_real_escape_string(base64_decode($_POST['install_dir']));
    $post_admin_user    = mysql_real_escape_string(base64_decode($_POST['admin_user']));
    $post_admin_pass    = mysql_real_escape_string(base64_decode($_POST['admin_pass']));
    $post_admin_email   = mysql_real_escape_string(base64_decode($_POST['admin_email']));
    $post_language      = mysql_real_escape_string(base64_decode($_POST['language']));
    $post_os            = mysql_real_escape_string($_POST['os']);
    $post_ip            = mysql_real_escape_string($_POST['ip']);
    $post_description   = mysql_real_escape_string($_POST['description']);
    $post_location      = mysql_real_escape_string($_POST['location']);
    $post_datacenter    = mysql_real_escape_string($_POST['datacenter']);
    $post_conn_user     = mysql_real_escape_string($_POST['conn_user']);
    $post_conn_pass     = mysql_real_escape_string($_POST['conn_pass']);
    $post_conn_port     = mysql_real_escape_string($_POST['conn_port']);
    
    ####################################################################

    // Check empty
    if(empty($post_install_dir) || 
    empty($post_db_host) || 
    empty($post_db_name) || 
    empty($post_db_user) || 
    empty($post_db_pass) || 
    empty($post_admin_user) || 
    empty($post_admin_pass) || 
    empty($post_admin_email) || 
    empty($post_language) || 
    empty($post_os) || 
    empty($post_ip) || 
    empty($post_conn_user) || 
    empty($post_conn_pass) || 
    empty($post_conn_port))
    {
        die('<b>Error:</b> Required fields were left blank.');
    }
    
    ####################################################################
    
    //
    // Install database tables
    //
    if(file_exists('versions/tables.php'))
    {
        require('versions/tables.php');
    }
    else
    {
        die('<b>Error:</b> versions/tables.php file not found');
    }
    
    /*
     * OLD [version].php files
     * DEPRECATED as of 1.0.2
     * 
    $version_file = 'versions/' . GPX_VERSION . '.php';
    
    if(file_exists($version_file))
    {
        require($version_file);
    }
    else
    {
        die('Install file "' . $version_file . '" does not exist.');
    }
    */
    
    ####################################################################
    
    //
    // Generate a random API key and Encryption key
    //
    function gpx_gen_random_str($length)
    {
        if(empty($length))
        {
            $length = 10;
        }
        
        $characters = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $stringz = "";
        
        for ($p = 0; $p < $length; $p++)
        {
            $stringz .= $characters[mt_rand(0, strlen($characters))];
        }
        return $stringz;
    }
    
    // Create an API Key
    $random_api_key = gpx_gen_random_str(128);
    
    // Create an encryption key
    $random_enc_key = gpx_gen_random_str(64);
    
    ####################################################################
    
    //
    // Create config file (db.php)
    //
    $fh = fopen('../include/db.php', 'w') or die('Failed opening "include/db.php".  Rename "include/db.php.new" to "include/db.php" and try again.  Otherwise check for file write permissions.');

    // Add the config items
    fwrite($fh, '<?php' . "\n");
    fwrite($fh, '// This file was automatically generated by the GamePanelX Pro installer' . "\n");
    fwrite($fh, 'error_reporting(E_ERROR);' . "\n");
    fwrite($fh, '$config[\'sql_host\']  = \'' . $post_db_host . '\';' . "\n");
    fwrite($fh, '$config[\'sql_user\']  = \'' . $post_db_user . '\';' . "\n");
    fwrite($fh, '$config[\'sql_pass\']  = \'' . $post_db_pass . '\';' . "\n");
    fwrite($fh, '$config[\'sql_db\']  = \'' . $post_db_name . '\';' . "\n");
    fwrite($fh, '$config[\'encrypt_key\']  = \'' . $random_enc_key . '\';' . "\n");
    fwrite($fh, '$config[\'api_key\']  = \'' . $random_api_key . '\';' . "\n");
    fwrite($fh, '?>' . "\n");
    
    // Close the file
    fclose($fh);

    ####################################################################
    
    //
    // Create admin user
    //
    $admin_status = 'active';
    $admin_notes  = 'Created by the GamePanelX Pro installer';
    
    @mysql_query("INSERT INTO admins (date_added,status,notes,username,password,email_address,language) VALUES(NOW(),'$admin_status','$admin_notes','$post_admin_user',MD5('$post_admin_pass'),'$post_admin_email','$post_language')") or die('Failed to add to the admins table');
    
    ####################################################################

    //
    // Create network server
    //
    $net_available  = 'Y';
    $net_physical   = 'Y';

    @mysql_query("INSERT INTO network (available,physical,os,date_added,conn_user,conn_pass,conn_port,ip,location,datacenter,description) VALUES('$net_available','$net_physical','$post_os',NOW(),AES_ENCRYPT('$post_conn_user','$random_enc_key'),AES_ENCRYPT('$post_conn_pass','$random_enc_key'),AES_ENCRYPT('$post_conn_port','$random_enc_key'),'$post_ip','$post_location','$post_datacenter','$post_description')") or die('Failed to add to the network table');
    
    // Get that network ID
    $this_netid = mysql_insert_id();
    
    ####################################################################
    
    // Current Version
    $this_version = GPX_VERSION;
    
    //
    // Insert main configuration
    //
    $query_insert_config  = "INSERT INTO `configuration` (`setting`, `value`) VALUES
('CompanyName', 'GamePanelX'),
('Template', 'default'),
('Language', '$post_language'),
('PrimaryEmail', '$post_admin_email'),
('SecondaryEmail', ''),
('StartServerAfterCreate', 'Y'),
('DocRoot', '$post_install_dir'),
('DefaultSlotNum', '12'),
('RemoteServerTimeout', '6'),
('ServerQueryTimeout', '200'),
('BalanceServerLimit', '16'),
('BalanceLoadLimit', '4'),
('BalanceDefaultPortOnly', 'N'),
('EmailNewClients', 'Y'),
('Version', '$this_version')";

    @mysql_query($query_insert_config) or die('Failed to add to the configuration table');
    
    ####################################################################
    
    //
    // Update parentid to itself (for FTP server's query to work properly)
    //
    @mysql_query("UPDATE network SET parentid = '$this_netid' WHERE id = '$this_netid'") or die('Failed to update the parent ID!');
    
    
    // Set doc root temporarily
    if(!defined('GPX_DOCROOT'))
    {
        define('GPX_DOCROOT', '../');
    }
    
    if($this_netid)
    {
        // Attempt to get remote home directory
        require(GPX_DOCROOT.'/include/functions/remote.php');
        
        $remote_home  = @gpx_remote_get_home($this_netid);
        
        // Update dir
        if(!empty($remote_home))
        {
            // Strip newline, add /accounts
            $remote_home .= '/accounts/';
            
            if(!preg_match("/Unable\ to\ connect/i",$remote_home))
            {
                @mysql_query("UPDATE network SET accounts_dir = '$remote_home' WHERE id = '$this_netid'") or die('Failed to update the accounts directory!');
            }
        }
    }
    
    ####################################################################

    // Display success
    require('success.php');
    exit;
}

?>
